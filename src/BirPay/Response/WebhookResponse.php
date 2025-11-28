<?php

namespace FaganChalabizada\BirPay\Response;

use FaganChalabizada\BirPay\Enums\PaymentMethod;
use FaganChalabizada\BirPay\Enums\PaymentStatus;

class WebhookResponse extends APIResponse
{

    /**
     * Get the event ID payment_succeeded or payment_canceled
     *
     * @return string|null Event id
     */
    public function getEvent(): ?string
    {
        return $this->data['event'] ?? null;
    }

    /**
     * Get the transaction ID (payment ID).
     *
     * @return string|null The transaction ID, or null if not available.
     */
    public function getTransactionId(): ?string
    {
        return $this->data['payload']['id'] ?? null; // Transaction ID is the 'id' in the response
    }

    /**
     * Get the type: purchase
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->data['payload']['type'] ?? null;
    }


    /**
     * Get the payment method. birbank, m10, bank_card
     *
     * @return PaymentMethod|null The payment method, or null if not available.
     */
    public function getPaymentMethod(): ?PaymentMethod
    {
        return PaymentMethod::tryFrom($this->data['payload']['paymentMethod'] ?? '');
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


}
