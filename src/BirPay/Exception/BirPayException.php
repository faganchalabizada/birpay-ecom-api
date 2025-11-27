<?php

namespace FaganChalabizada\BirPay\Exception;

use Exception;

class BirPayException extends Exception
{
    private string $errorCode;   // Error code as provided in the response
    private array $responseData;       //response data of api

    // Constructor to initialize the error object
    public function __construct(string $message, string $errorCode = '', array $responseData = [])
    {
        parent::__construct($message); // Call parent constructor with the message
        $this->errorCode = $errorCode;
        $this->responseData = $responseData;
    }

    // Getters for the error properties
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function responseData(): array
    {
        return $this->responseData;
    }

    // Optional: Method to represent the error as a string
    public function __toString(): string
    {
        return sprintf("Error [%s]: %s", $this->errorCode, $this->getMessage());
    }
}
