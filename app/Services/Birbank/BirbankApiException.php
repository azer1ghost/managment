<?php

namespace App\Services\Birbank;

use Exception;

class BirbankApiException extends Exception
{
    protected $statusCode;
    protected $responseData;

    public function __construct(string $message = '', int $statusCode = 0, array $responseData = [], Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
        $this->responseData = $responseData;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public static function fromHttpResponse(int $statusCode, array $responseData = [], string $defaultMessage = 'Birbank API request failed'): self
    {
        $message = $responseData['message'] ?? $responseData['error'] ?? $defaultMessage;
        return new self($message, $statusCode, $responseData);
    }
}

