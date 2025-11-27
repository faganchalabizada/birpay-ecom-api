<?php

namespace FaganChalabizada\BirPay\Exception;

use Exception;

class BirPayException extends Exception
{
    private string $errorCode;   // Error code as provided in the response
    private array $details;       // Additional details about the error (if any)

    // Constructor to initialize the error object
    public function __construct(string $message, string $errorCode = '', array $details = [])
    {
        parent::__construct($message); // Call parent constructor with the message
        $this->errorCode = $errorCode;
        $this->details = $details;
    }

    // Getters for the error properties
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    // Optional: Method to represent the error as a string
    public function __toString(): string
    {
        return sprintf("Error [%s]: %s", $this->errorCode, $this->getMessage());
    }
}
