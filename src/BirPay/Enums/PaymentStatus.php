<?php

namespace FaganChalabizada\BirPay\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case SUCCEEDED = 'succeeded';
    case CANCELED = 'canceled';
    case WAITING_FOR_CAPTURE = 'waiting_for_capture';

    // Method to get a description for each payment status
    public function getDescription(): string
    {
        return match ($this) {
            self::PENDING => "The payment is initially created with a PENDING status. In this case, no transaction is created, and the next action is waiting for the customer or client.",
            self::SUCCEEDED => "The payment is considered successful when the merchant completes it.",
            self::CANCELED => "When no action is performed after the payment is created, it will be automatically cancelled after the expiry date provided by the merchant, or if the merchant intentionally wants to cancel the order.",
            self::WAITING_FOR_CAPTURE => "This condition is utilized when the merchant intends to perform after completion (only for cards).",
        };
    }

    // Custom method to get the description by status code
    public static function getDescriptionByStatus(string $status): string
    {
        return match ($status) {
            'pending' => "The payment is initially created with a PENDING status. In this case, no transaction is created, and the next action is waiting for the customer or client.",
            'succeeded' => "The payment is considered successful when the merchant completes it.",
            'canceled' => "When no action is performed after the payment is created, it will be automatically cancelled after the expiry date provided by the merchant, or if the merchant intentionally wants to cancel the order.",
            'waiting_for_capture' => "This condition is utilized when the merchant intends to perform after completion (only for cards).",
            default => "Unknown status",
        };
    }
}