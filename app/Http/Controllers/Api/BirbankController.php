<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\Birbank\BirbankApiException;
use App\Services\Birbank\BirbankClient;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BirbankController extends Controller
{
    /**
     * Login to Birbank API for a company.
     * POST /api/birbank/{company}/login
     *
     * @param Request $request
     * @param int|string $company Company ID
     * @return JsonResponse
     */
    public function login(Request $request, $company): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|required_without:use_stored',
            'password' => 'sometimes|string|required_without:use_stored',
            'use_stored' => 'sometimes|boolean',
            'env' => 'sometimes|in:test,prod',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $companyId = is_numeric($company) ? (int) $company : $company;
            $companyModel = Company::findOrFail($companyId);

            $env = $request->input('env', config('birbank.default_env', 'test'));
            $client = new BirbankClient($companyId, $env);

            $username = $request->input('username');
            $password = $request->input('password');
            $useStored = $request->boolean('use_stored', true);

            // If credentials provided, use them; otherwise use stored
            if ($username && $password) {
                $responseData = $client->login($username, $password);
            } elseif ($useStored) {
                $responseData = $client->login();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Username and password are required, or set use_stored=true',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'company_id' => $companyId,
                'env' => $env,
            ], 200);

        } catch (BirbankApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
            ], $e->getStatusCode() ?: 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get accounts for a company.
     * GET /api/birbank/{company}/accounts
     *
     * @param Request $request
     * @param int|string $company Company ID
     * @return JsonResponse
     */
    public function getAccounts(Request $request, $company): JsonResponse
    {
        try {
            $companyId = is_numeric($company) ? (int) $company : $company;
            $companyModel = Company::findOrFail($companyId);

            $env = $request->input('env', config('birbank.default_env', 'test'));
            $client = new BirbankClient($companyId, $env);

            $accounts = $client->getAccounts();

            return response()->json([
                'success' => true,
                'data' => $accounts,
                'company_id' => $companyId,
                'env' => $env,
            ], 200);

        } catch (BirbankApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
            ], $e->getStatusCode() ?: 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get account transactions/statement.
     * GET /api/birbank/{company}/transactions
     *
     * @param Request $request
     * @param int|string $company Company ID
     * @return JsonResponse
     */
    public function getTransactions(Request $request, $company): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'account' => 'required|string',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'env' => 'sometimes|in:test,prod',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $companyId = is_numeric($company) ? (int) $company : $company;
            $companyModel = Company::findOrFail($companyId);

            $env = $request->input('env', config('birbank.default_env', 'test'));
            $client = new BirbankClient($companyId, $env);

            $account = $request->input('account');
            $from = Carbon::parse($request->input('from'));
            $to = Carbon::parse($request->input('to'));

            // Additional filters (optional)
            $filters = $request->only(['limit', 'offset', 'direction', 'min_amount', 'max_amount']);

            $transactions = $client->getAccountStatement($account, $from, $to, $filters);

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'company_id' => $companyId,
                'env' => $env,
                'account' => $account,
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ], 200);

        } catch (BirbankApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
            ], $e->getStatusCode() ?: 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}

