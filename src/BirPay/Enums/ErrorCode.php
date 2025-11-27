<?php

namespace FaganChalabizada\BirPay\Enums;

enum ErrorCode: string
{
    case INVALID_OPERATION = 'invalid_operation';
    case INVALID_MERCHANT = 'invalid_merchant';
    case INVALID_POLICY = 'invalid_policy';
    case BAD_REQUEST = 'bad_request';

    case UNAUTHORIZED_PAYMENT = 'unauthorized_payment';
    case ACCESS_DENIED = 'access_denied';

    case TOKEN_EXPIRED = 'token_expired';

    case UNEXPECTED_PAYMENT_ERROR = 'unexpected_payment_error';
    case INTERNAL_SERVER_ERROR = 'internal_server_error';

    case BAD_GATEWAY = 'bad_gateway';

    case GATEWAY_TIMEOUT = 'gateway_timeout';

    public function getDescription(): string
    {
        return match ($this) {
            self::INVALID_OPERATION => "Occurs when action is not supported on provided item.",
            self::INVALID_MERCHANT => "Occurs when merchant id is provided, but does not linked to merchant.",
            self::INVALID_POLICY => "Occurs when merchant is not eligible to perform action on given resource.",
            self::BAD_REQUEST => "Not all required parameters are provided.",

            self::UNAUTHORIZED_PAYMENT => "Occurs when fetching payment does not belong to merchant.",
            self::ACCESS_DENIED => "The bearer token is not valid.",

            self::TOKEN_EXPIRED => "The bearer token is expired.",

            self::UNEXPECTED_PAYMENT_ERROR => "Error occurs due to internal reasons.",
            self::INTERNAL_SERVER_ERROR => "Error occurs due to internal reasons.",

            self::BAD_GATEWAY => "Error occurs due to external reasons.",

            self::GATEWAY_TIMEOUT => "Error occurs due to internal timeouts.",
        };
    }

    // Custom method to handle lookup by API-specific code
    public static function getDescriptionByCode(string $code): string
    {
        return match ($code) {
            'invalid_operation' => "Occurs when action is not supported on provided item.",
            'invalid_merchant' => "Occurs when merchant id is provided, but does not linked to merchant.",
            'invalid_policy' => "Occurs when merchant is not eligible to perform action on given resource.",
            'bad_request' => "Not all required parameters are provided.",

            'unauthorized_payment' => "Occurs when fetching payment does not belong to merchant.",
            'access_denied' => "The bearer token is not valid.",

            'token_expired' => "The bearer token is expired.",

            'unexpected_payment_error' => "Error occurs due to internal reasons.",
            'internal_server_error' => "Error occurs due to internal reasons.",

            'bad_gateway' => "Error occurs due to external reasons.",

            'gateway_timeout' => "Error occurs due to internal timeouts.",
            default => "Unknown error",
        };
    }
}
