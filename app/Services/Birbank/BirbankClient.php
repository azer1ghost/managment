<?php

namespace App\Services\Birbank;

use App\Models\BirbankCredential;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BirbankClient
{
    protected int $companyId;
    protected string $env;
    protected ?BirbankCredential $credential = null;
    protected string $baseUrl;

    public function __construct(int $companyId, ?string $env = null)
    {
        $this->companyId = $companyId;
        $this->env = $env ?? config('birbank.default_env', 'test');
        $this->baseUrl = $this->getBaseUrl();
    }

    /**
     * Get base URL based on environment.
     */
    protected function getBaseUrl(): string
    {
        return $this->env === 'prod'
            ? config('birbank.base_url_prod')
            : config('birbank.base_url_test');
    }

    /**
     * Get or create credential record for this company and environment.
     */
    protected function getCredential(): BirbankCredential
    {
        if ($this->credential) {
            return $this->credential;
        }

        $this->credential = BirbankCredential::firstOrNew([
            'company_id' => $this->companyId,
            'env' => $this->env,
        ]);

        return $this->credential;
    }

    /**
     * Perform login and store tokens.
     *
     * @param string|null $username Override username from credential
     * @param string|null $password Override password from credential
     * @return array Response data with user/client info (without tokens)
     * @throws BirbankApiException
     */
    public function login(?string $username = null, ?string $password = null): array
    {
        $credential = $this->getCredential();

        $username = $username ?? $credential->username;
        $password = $password ?? $credential->password;

        if (!$username || !$password) {
            throw new BirbankApiException('Username and password are required for login', 400);
        }

        $endpoint = config('birbank.endpoints.login');
        $url = rtrim($this->baseUrl, '/') . $endpoint;

        $this->logInfo('Login attempt', [
            'url' => $url,
            'username' => $username,
            'company_id' => $this->companyId,
            'env' => $this->env,
        ]);

        try {
            $response = Http::timeout(config('birbank.timeout', 30))
                ->withOptions([
                    'verify' => config('birbank.verify_ssl', true),
                    'connect_timeout' => config('birbank.connect_timeout', 10),
                ])
                ->asJson()
                ->acceptJson()
                ->post($url, [
                    // Əvvəlki B2B login parametrləri
                    'username' => $username,
                    'password' => $password,
                    // Kapital Bank-ın göndərdiyi yeni BirPay / POS məlumatları
                    // (sənədində "Post details" bölməsində göstərilən struktur)
                    'client-id' => config('birbank.client_id'),
                    'client-secret' => config('birbank.client_secret'),
                    'posDetail' => [
                        'merchantId' => config('birbank.merchant_id'),
                        'terminalId' => config('birbank.terminal_id'),
                    ],
                ]);

            $statusCode = $response->status();
            $responseData = $response->json() ?? [];
            $responseBody = $response->body();
            
            $this->logInfo('Login response received', [
                'status' => $statusCode,
                'has_response_data' => !empty($responseData),
                'response_keys' => !empty($responseData) ? array_keys($responseData) : [],
                'response_structure' => !empty($responseData) ? $this->getResponseStructure($responseData) : 'empty',
            ]);

            // Handle non-JSON responses
            if (empty($responseData) && !empty($responseBody)) {
                $this->logError('Non-JSON response received', [
                    'status' => $statusCode,
                    'body_preview' => substr($responseBody, 0, 500),
                ]);
                throw new BirbankApiException(
                    'API returned non-JSON response. Status: ' . $statusCode . '. Response: ' . substr($responseBody, 0, 200),
                    $statusCode,
                    ['raw_body' => $responseBody]
                );
            }

            // Check if API returned an error in response
            // According to API docs: success = response.code = "0", error = any other code
            // But we also handle different response structures
            $responseCode = $responseData['response']['code'] 
                ?? $responseData['code'] 
                ?? $responseData['status'] 
                ?? null;
            
            $isSuccess = ($responseCode === '0' || $responseCode === 0 || $responseCode === 'success' || $responseCode === 200)
                && $response->successful();
            
            // Also check if HTTP status is not successful (non-2xx status)
            if (!$response->successful() || (!$isSuccess && $responseCode !== null)) {
                $this->logError('Login failed', [
                    'status' => $statusCode,
                    'response_code' => $responseCode,
                    'response' => $this->sanitizeResponse($responseData),
                    'full_response_structure' => $this->getResponseStructure($responseData),
                ]);
                
                // Extract error message from response (try multiple possible locations)
                $errorMessage = $responseData['response']['message'] 
                    ?? $responseData['response']['error'] 
                    ?? $responseData['message'] 
                    ?? $responseData['error'] 
                    ?? $responseData['errorMessage']
                    ?? $responseData['error_description']
                    ?? ($statusCode === 401 ? 'Invalid username or password' : 'Login failed');
                
                // Use error code from response if available, otherwise use HTTP status
                $errorCode = $responseCode ?? $statusCode;
                
                // Add more context to error message
                if ($statusCode === 401) {
                    $errorMessage = 'Invalid credentials. ' . $errorMessage;
                } elseif ($statusCode === 403) {
                    $errorMessage = 'Access forbidden. ' . $errorMessage;
                } elseif ($statusCode === 404) {
                    $errorMessage = 'API endpoint not found. ' . $errorMessage;
                } elseif ($statusCode >= 500) {
                    $errorMessage = 'Server error. ' . $errorMessage;
                }
                
                throw new BirbankApiException($errorMessage, (int)$errorCode, $responseData);
            }

            // Extract tokens and user data
            // Try multiple possible response structures
            $responseDataOnly = $responseData['responseData'] ?? $responseData['data'] ?? $responseData;
            $jwtToken = $responseDataOnly['jwttoken'] 
                ?? $responseDataOnly['jwt_token'] 
                ?? $responseDataOnly['access_token']
                ?? $responseData['jwttoken'] 
                ?? $responseData['jwt_token']
                ?? $responseData['access_token']
                ?? null;
                
            $refreshToken = $responseDataOnly['jwtrefreshtoken'] 
                ?? $responseDataOnly['jwt_refresh_token'] 
                ?? $responseDataOnly['refresh_token']
                ?? $responseData['jwtrefreshtoken'] 
                ?? $responseData['jwt_refresh_token']
                ?? $responseData['refresh_token']
                ?? null;
                
            $authType = $responseDataOnly['authType'] 
                ?? $responseDataOnly['auth_type']
                ?? $responseData['authType'] 
                ?? $responseData['auth_type']
                ?? null;

            if (!$jwtToken) {
                $this->logError('JWT token not found', [
                    'response_structure' => $this->getResponseStructure($responseData),
                    'response_keys' => array_keys($responseData),
                ]);
                $errorMsg = $responseData['response']['message'] 
                    ?? $responseData['message'] 
                    ?? 'JWT token not found in login response. Please check API response structure.';
                throw new BirbankApiException($errorMsg, 500, $responseData);
            }

            // Store credentials and tokens
            $credential->username = $username;
            $credential->password = $password;
            $credential->access_token = $jwtToken;
            $credential->refresh_token = $refreshToken;
            $credential->auth_type = $authType;
            $credential->last_login_at = Carbon::now();
            // Note: token_expires_at may be set if API provides expiry info
            $credential->save();

            $this->logInfo('Login successful', [
                'company_id' => $this->companyId,
                'env' => $this->env,
                'auth_type' => $authType,
            ]);

            // Return response data without tokens
            return $responseDataOnly;

        } catch (BirbankApiException $e) {
            throw $e;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->logError('Connection exception', [
                'message' => $e->getMessage(),
                'url' => $url,
            ]);
            throw new BirbankApiException(
                'Bağlantı xətası: ' . $e->getMessage() . '. API-yə çatmaq mümkün olmadı. SSL sertifikatı və ya şəbəkə bağlantısını yoxlayın.',
                0,
                [],
                $e
            );
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->logError('Request exception', [
                'message' => $e->getMessage(),
                'url' => $url,
            ]);
            throw new BirbankApiException(
                'Sorğu xətası: ' . $e->getMessage(),
                0,
                [],
                $e
            );
        } catch (\Exception $e) {
            $this->logError('Login exception', [
                'message' => $e->getMessage(),
                'exception_type' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new BirbankApiException('Login request failed: ' . $e->getMessage(), 0, [], $e);
        }
    }

    /**
     * Ensure we have a valid access token, login if needed.
     *
     * @throws BirbankApiException
     */
    public function ensureToken(): void
    {
        $credential = $this->getCredential();

        if (!$credential->hasValidToken()) {
            $this->logInfo('Token missing or expired, attempting login', [
                'company_id' => $this->companyId,
                'env' => $this->env,
            ]);
            $this->login();
        }
    }

    /**
     * Refresh access token using refresh token.
     * Note: Endpoint not provided yet, this is a scaffold.
     *
     * @throws BirbankApiException
     */
    public function refresh(): void
    {
        $credential = $this->getCredential();

        if (!$credential->refresh_token) {
            throw new BirbankApiException('Refresh token not available', 401);
        }

        // TODO: Implement when refresh endpoint is provided
        throw new BirbankApiException('Token refresh endpoint not yet implemented', 501);

        // Scaffold code (will be implemented later):
        /*
        $endpoint = config('birbank.endpoints.refresh');
        $url = rtrim($this->baseUrl, '/') . $endpoint;

        $response = Http::timeout(config('birbank.timeout', 30))
            ->withOptions(['verify' => config('birbank.verify_ssl', true)])
            ->asJson()
            ->acceptJson()
            ->post($url, [
                'refresh_token' => $credential->refresh_token,
            ]);

        if (!$response->successful()) {
            throw BirbankApiException::fromHttpResponse($response->status(), $response->json());
        }

        $responseData = $response->json();
        $credential->access_token = $responseData['jwttoken'] ?? null;
        $credential->refresh_token = $responseData['jwtrefreshtoken'] ?? $credential->refresh_token;
        $credential->save();
        */
    }

    /**
     * Make an authenticated API request.
     *
     * @param string $method HTTP method
     * @param string $path API path
     * @param array $options Request options (body, query, etc.)
     * @param bool $retryOn401 Retry once after refresh on 401
     * @return array Response data
     * @throws BirbankApiException
     */
    public function request(string $method, string $path, array $options = [], bool $retryOn401 = true): array
    {
        $this->ensureToken();

        $credential = $this->getCredential();
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');

        $http = Http::timeout(config('birbank.timeout', 30))
            ->withOptions([
                'verify' => config('birbank.verify_ssl', true),
                'connect_timeout' => config('birbank.connect_timeout', 10),
            ])
            ->asJson()
            ->acceptJson()
            ->withToken($credential->access_token);

        // Add query parameters if provided
        if (isset($options['query'])) {
            $http->withQueryParameters($options['query']);
        }

        // Add body if provided
        if (isset($options['body'])) {
            $http->json($options['body']);
        }

        try {
            $response = $http->{strtolower($method)}($url);

            $statusCode = $response->status();
            $responseData = $response->json() ?? [];

            // Handle 401 Unauthorized - try refresh once
            if ($statusCode === 401 && $retryOn401) {
                $this->logInfo('Received 401, attempting token refresh', [
                    'company_id' => $this->companyId,
                    'path' => $path,
                ]);

                try {
                    $this->refresh();
                    // Retry the request once
                    return $this->request($method, $path, $options, false);
                } catch (BirbankApiException $e) {
                    // If refresh fails, try login as fallback
                    if ($e->getStatusCode() === 501) {
                        // Refresh not implemented, try login
                        $this->login();
                        return $this->request($method, $path, $options, false);
                    }
                    throw $e;
                }
            }

            if (!$response->successful()) {
                $this->logError('API request failed', [
                    'method' => $method,
                    'path' => $path,
                    'status' => $statusCode,
                    'response' => $this->sanitizeResponse($responseData),
                ]);
                throw BirbankApiException::fromHttpResponse($statusCode, $responseData, 'API request failed');
            }

            return $responseData;

        } catch (BirbankApiException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->logError('API request exception', [
                'method' => $method,
                'path' => $path,
                'message' => $e->getMessage(),
            ]);
            throw new BirbankApiException('API request failed: ' . $e->getMessage(), 0, [], $e);
        }
    }

    /**
     * Get accounts list.
     * TODO: Implement when endpoint is provided.
     *
     * @return array
     * @throws BirbankApiException
     */
    public function getAccounts(): array
    {
        // TODO: Implement when endpoint is available
        // $endpoint = config('birbank.endpoints.accounts');
        // return $this->request('GET', $endpoint);

        $this->logInfo('getAccounts called (stub)', [
            'company_id' => $this->companyId,
            'env' => $this->env,
        ]);

        return [];
    }

    /**
     * Get account statement/transactions.
     * TODO: Implement when endpoint is provided.
     *
     * @param string $accountIdOrIban Account ID or IBAN
     * @param Carbon $from Start date
     * @param Carbon $to End date
     * @param array $filters Additional filters
     * @return array
     * @throws BirbankApiException
     */
    public function getAccountStatement(string $accountIdOrIban, Carbon $from, Carbon $to, array $filters = []): array
    {
        // TODO: Implement when endpoint is available
        // $endpoint = config('birbank.endpoints.account_statement');
        // return $this->request('GET', $endpoint, [
        //     'query' => array_merge([
        //         'account' => $accountIdOrIban,
        //         'from' => $from->toDateString(),
        //         'to' => $to->toDateString(),
        //     ], $filters),
        // ]);

        $this->logInfo('getAccountStatement called (stub)', [
            'company_id' => $this->companyId,
            'account' => $accountIdOrIban,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);

        return [];
    }

    /**
     * Sanitize response data for logging (remove sensitive info).
     */
    protected function sanitizeResponse(array $data): array
    {
        $sanitized = $data;
        $sensitiveKeys = ['jwttoken', 'jwtrefreshtoken', 'token', 'password', 'access_token', 'refresh_token', 'jwt_token', 'jwt_refresh_token'];

        foreach ($sensitiveKeys as $key) {
            if (isset($sanitized[$key])) {
                $sanitized[$key] = '***REDACTED***';
            }
        }

        // Recursively sanitize nested arrays
        foreach ($sanitized as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeResponse($value);
            }
        }

        return $sanitized;
    }

    /**
     * Get response structure for debugging (shows keys without values).
     */
    protected function getResponseStructure($data, $prefix = ''): array
    {
        $structure = [];
        
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $fullKey = $prefix ? $prefix . '.' . $key : $key;
                if (is_array($value)) {
                    $structure[$fullKey] = 'array(' . count($value) . ' items)';
                    $structure = array_merge($structure, $this->getResponseStructure($value, $fullKey));
                } else {
                    $type = gettype($value);
                    $preview = is_string($value) ? substr($value, 0, 50) : $value;
                    $structure[$fullKey] = $type . ($type === 'string' && strlen($value) > 50 ? ' (truncated)' : '');
                }
            }
        }
        
        return $structure;
    }

    /**
     * Log info message safely (without sensitive data).
     */
    protected function logInfo(string $message, array $context = []): void
    {
        Log::channel('daily')->info("[Birbank] {$message}", $context);
    }

    /**
     * Log error message safely (without sensitive data).
     */
    protected function logError(string $message, array $context = []): void
    {
        Log::channel('daily')->error("[Birbank] {$message}", $context);
    }
}

