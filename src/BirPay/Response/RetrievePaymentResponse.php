<?php

namespace FaganChalabizada\BirPay\Response;

class RetrievePaymentResponse extends APIResponse
{
    /**
     * Get the payment ID (transaction ID).
     *
     * @return string|null The payment ID, or null if not available.
     */
    public function getTransactionId(): ?string
    {
        return $this->data['id'] ?? null;
    }

    /**
     * Get the payment status (e.g., "canceled", "pending", etc.).
     *
     * @return string|null The payment status, or null if not available.
     */
    public function getPaymentStatus(): ?string
    {
        return $this->data['status'] ?? null;
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
     * Get the merchant details.
     *
     * @return array|null The merchant details (ID, name, MCC), or null if not available.
     */
    public function getMerchantDetails(): ?array
    {
        return $this->data['merchant'] ?? null;
    }

    /**
     * Get the cancellation reason (e.g., "canceled_by_merchant").
     *
     * @return string|null The cancellation reason, or null if not available.
     */
    public function getCancelationReason(): ?string
    {
        return $this->data['cancelationReason'] ?? null;
    }

    /**
     * Get the party that canceled the payment (e.g., "merchant").
     *
     * @return string|null The cancellation party, or null if not available.
     */
    public function getCancelationParty(): ?string
    {
        return $this->data['cancelationParty'] ?? null;
    }

    /**
     * Get the confirmation type
     *
     * @return string|null The confirmation type, or null if not available. qr, mobile, redirect
     */
    public function getConfirmationType(): ?string
    {
        return $this->data['confirmation']['type'] ?? null;
    }


    /**
     * Get the confirmation URL (for mobile AND redirect confirmation).
     *
     * @return string|null The confirmation URL, or null if not available.
     */
    public function getConfirmationUrl(): ?string
    {
        return $this->data['confirmation']['confirmUrl'] ?? null;
    }


    /**
     * Get the confirmation URL (for QR confirmation).
     *
     * @return string|null The confirmation data, or null if not available.
     */
    public function getConfirmationData(): ?string
    {
        return $this->data['confirmation']['confirmData'] ?? null;
    }


    /**
     * Get the return URL for the confirmation (if available).
     *
     * @return string|null The return URL, or null if not available.
     */
    public function getReturnUrl(): ?string
    {
        return $this->data['confirmation']['returnUrl'] ?? null;
    }

    /**
     * Get the expiration date of the payment.
     *
     * @return string|null The expiration date, or null if not available.
     */
    public function getExpirationDate(): ?string
    {
        return $this->data['expiresAt'] ?? null;
    }
}
