<?php

namespace FaganChalabizada\BirPay\Enums;

enum PaymentCancelReason: string
{
    case CANCELED_BY_MERCHANT = 'CANCELED_BY_MERCHANT';
    case CANCELED_BY_PAYMENT_NETWORK = 'CANCELED_BY_PAYMENT_NETWORK';
    case EXPIRED_ON_CONFIRMATION = 'EXPIRED_ON_CONFIRMATION';
    case INSUFFICIENT_FUNDS = 'INSUFFICIENT_FUNDS';
    case THREE_DS_VERIFICATION_FAILED = 'THREE_DS_VERIFICATION_FAILED';
    case EXPIRED_ON_CAPTURE = 'EXPIRED_ON_CAPTURE';
    case ISSUER_DECLINE = 'ISSUER_DECLINE';
    case GENERAL_DECLINE = 'GENERAL_DECLINE';

    // Method to get the description for each cancellation reason
    public function getDescription(): string
    {
        return match ($this) {
            self::CANCELED_BY_MERCHANT => "When merchant cancels payment.",
            self::CANCELED_BY_PAYMENT_NETWORK => "When payment network cancels payment.",
            self::EXPIRED_ON_CONFIRMATION => "When customer does not confirm payment in time.",
            self::INSUFFICIENT_FUNDS => "When customer does not have sufficient funds.",
            self::THREE_DS_VERIFICATION_FAILED => "When 3DS verification is failed.",
            self::EXPIRED_ON_CAPTURE => "Such as when the capture is not performed in time.",
            self::ISSUER_DECLINE => "For card payments, if the issuer declines to process the payment, the transaction will fail and the customer will be shown a failure page.",
            self::GENERAL_DECLINE => "Such declines are uncommon but can occur in extreme cases.",
        };
    }

    // Custom method to get the description by cancellation reason string
    public static function getDescriptionByReason(string $reason): string
    {
        return match ($reason) {
            'CANCELED_BY_MERCHANT' => "When merchant cancels payment.",
            'CANCELED_BY_PAYMENT_NETWORK' => "When payment network cancels payment.",
            'EXPIRED_ON_CONFIRMATION' => "When customer does not confirm payment in time.",
            'INSUFFICIENT_FUNDS' => "When customer does not have sufficient funds.",
            'THREE_DS_VERIFICATION_FAILED' => "When 3DS verification is failed.",
            'EXPIRED_ON_CAPTURE' => "Such as when the capture is not performed in time.",
            'ISSUER_DECLINE' => "For card payments, if the issuer declines to process the payment, the transaction will fail and the customer will be shown a failure page.",
            'GENERAL_DECLINE' => "Such declines are uncommon but can occur in extreme cases.",
            default => "Unknown cancellation reason",
        };
    }
}
