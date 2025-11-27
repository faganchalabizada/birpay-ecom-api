<?php

namespace FaganChalabizada\BirPay\Response;

use FaganChalabizada\BirPay\Exception\BirPayException;

class APIResponse
{
    protected array $data;
    protected int $httpCode;

    /**
     * @param array $responseData The decoded JSON response from the API.
     * @param int $httpCode The HTTP status code of the response.
     * @throws BirPayException If the response indicates an error.
     */
    public function __construct(array $responseData, int $httpCode)
    {
        $this->data = $responseData;
        $this->httpCode = $httpCode;

        // Handle error response
        if ($httpCode != 200) {
            // Extract error details
            $errorCode = $this->data['code'] ?? 'UNKNOWN_ERROR';
            $errorMessage = $this->data['message'] ?? 'An unknown error occurred';

            if (isset($this->data['error'])) {
                $errorCode = $this->data['error'];
                $errorMessage = $this->data['error_description'] ?? 'An unknown error occurred';
            }

            // Include any validation errors, if available
            $errorDetails = $this->data['errors'] ?? [];


            // Create a detailed error message
            $detailedMessage = $this->buildErrorMessage($errorMessage, $errorDetails);

            $detailedMessage = $detailedMessage == '' ? $errorCode : $detailedMessage;

            // Throw a custom exception with the error information
            throw new BirPayException($detailedMessage, $errorCode);
        }

    }

    /**
     * Build a detailed error message.
     *
     * @param string $message The main error message.
     * @param array $errors The validation errors (if any).
     *
     * @return string The formatted error message.
     */
    private function buildErrorMessage(string $message, array $errors): string
    {
        if (empty($errors)) {
            return $message;
        }

        // If validation errors are present, append them to the message
        $validationErrors = array_map(function ($error) {
            return $error['property'] . ': ' . $error['message'];
        }, $errors);

        return $message . ' - Validation Errors: ' . implode(', ', $validationErrors);
    }

    /**
     * Get the error message from the response.
     *
     * @return string|null The error message, or null if no error occurred.
     */
    public function getErrorMessage(): ?string
    {
        return $this->data['message'] ?? null;
    }

    /**
     * Get the raw response data.
     *
     * @return array The raw response data.
     */
    public function getRawData(): array
    {
        return $this->data;
    }

    /**
     * Get the HTTP status code of the response.
     *
     * @return int The HTTP status code.
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

}

