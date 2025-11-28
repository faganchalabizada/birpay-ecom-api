<?php

namespace FaganChalabizada\BirPay\Response;

use FaganChalabizada\BirPay\Enums\PaymentStatus;

class RetrieveRefundResponse extends APIResponse
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
     * Get the original ID (refunded payment ID).
     *
     * @return string|null The original ID, or null if not available.
     */
    public function getOriginalId(): ?string
    {
        return $this->data['originalId'] ?? null; // Original ID is the 'id' of the refunded payment
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
     * @return int|null The payment amount or null if not available.
     */
    public function getAmount(): ?int
    {
        return $this->data['amount']['value'] ?? null;
    }

    /**
     * Get the payment currency.
     *
     * @return string|null The payment currency or null if not available.
     */
    public function getCurrency(): ?string
    {
        return $this->data['amount']['currency'] ?? null;
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
     * Get the expiration date of the payment.
     *
     * @return string|null The expiration date of the payment, or null if not available.
     */
    public function getExpirationDate(): ?string
    {
        return $this->data['expiresAt'] ?? null;
    }

}
