<?php

namespace FaganChalabizada\BirPay\Response;

use FaganChalabizada\BirPay\Enums\PaymentStatus;

class CreatePaymentResponse extends APIResponse
{
    /**
     * Get the transaction ID (payment ID).
     *
     * @return string|null The transaction ID, or null if not available.
     */
    public function getTransactionId(): ?string
    {
        return $this->data['id'] ?? null; // Transaction ID is the 'id' in the response
    }

    /**
     * Get the payment URL for QR code confirmation.
     *
     * @return string|null The payment URL, or null if not available.
     */
    public function getPaymentURL(): ?string
    {
        if (isset($this->data['confirmation']['confirmUrl'])) {
            return $this->data['confirmation']['confirmUrl'];
        }

        // Assuming the 'confirmData' inside 'confirmation' contains the payment URL
        return $this->data['confirmation']['confirmData'] ?? null;
    }

    /**
     * Get the payment status.
     *
     * @return PaymentStatus|null The payment status, or null if not available.
     */
    public function getPaymentStatus(): ?PaymentStatus
    {
        return PaymentStatus::tryFrom($this->data['payload']['status'] ?? '');
    }

    /**
     * Get the amount of the payment.
     *
     * @return array|null The payment amount (value and currency), or null if not available.
     */
    public function getAmount(): ?array
    {
        return $this->data['amount'] ?? null;
    }

    /**
     * Get the description of the payment.
     *
     * @return string|null The payment description, or null if not available.
     */
    public function getDescription(): ?string
    {
        return $this->data['description'] ?? null;
    }

    /**
     * Get the merchant details.
     *
     * @return array|null The merchant details (ID, name, MCC), or null if not available.
     */
    public function getMerchantDetails(): ?array
    {
        return $this->data['merchant'] ?? null;
    }

    /**
     * Get the expiration date of the payment.
     *
     * @return string|null The expiration date of the payment, or null if not available.
     */
    public function getExpirationDate(): ?string
    {
        return $this->data['expiresAt'] ?? null;
    }
}
