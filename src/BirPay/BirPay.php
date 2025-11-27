<?php

namespace FaganChalabizada\BirPay;

use Exception;
use FaganChalabizada\BirPay\Enums\ConfirmationType;
use FaganChalabizada\BirPay\Enums\PaymentMethod;
use FaganChalabizada\BirPay\Exception\BirPayException;
use FaganChalabizada\BirPay\Response\APIResponse;
use FaganChalabizada\BirPay\Response\CancelPaymentResponse;
use FaganChalabizada\BirPay\Response\CreatePaymentResponse;
use FaganChalabizada\BirPay\Response\RefundPaymentResponse;
use FaganChalabizada\BirPay\Response\RetrievePaymentResponse;
use FaganChalabizada\BirPay\Response\RetrieveRefundResponse;
use FaganChalabizada\BirPay\Response\WebhookResponse;

class BirPay
{

    private string $merchantId;
    private string $terminalId;

    private string $demoUrl = 'https://precheckout.kapitalbank.az/api';
    private string $prodUrl = 'https://checkout.kapitalbank.az/api';

    private Auth $auth;

    private string $baseUrl;

    public function __construct($merchantId = 'E1040009', $terminalId = 'E1040009', $clientId = 'birpay-test', $clientSecret = 'mc8JHRvS9JyaElcj1ozm1Fpd5Gpaj73q')
    {

        $demo = ($merchantId == 'E1040009');

        $this->baseUrl = $demo ? $this->demoUrl : $this->prodUrl;
        $this->merchantId = $merchantId;
        $this->terminalId = $terminalId;

        $this->auth = new Auth($clientId, $clientSecret, $demo);
    }

    /**
     * Returns the Auth instance.
     *
     * @return Auth
     */
    public function auth(): Auth
    {
        return $this->auth;
    }


    /**
     * Generate a unique idempotency key for each request.
     *
     * @return string The generated idempotency key.
     * @throws Exception
     */
    private function generateIdempotencyKey(): string
    {
        return bin2hex(random_bytes(16)); // Generate a unique key (e.g., using random bytes)
    }


    /**
     * @return string
     * @throws BirPayException
     */
    public function getBearerToken(): string
    {
        return $this->auth()->getAccessToken();
    }


    /**
     * Sends a request to the given endpoint with the provided data and handles the response.
     *
     * @param string $endpoint The API endpoint to send the request to.
     * @param array $data The data to be sent in the request body (for POST/PUT), or as query params (for GET).
     * @param string $responseClass The class to handle the response and map it to.
     * @param string $method The HTTP method (GET, POST, PUT).
     * @return APIResponse|mixed The response object or data after processing.
     * @throws BirPayException If the request fails or the response indicates an error.
     */
    public function sendRequest(string $endpoint, array $data, string $responseClass, string $method = 'POST'): mixed
    {
        // Initialize cURL session
        $ch = curl_init();

        // Prepare the headers
        $headers = [
            'Content-Type: application/json', // Set the content type to application/json
            'Authorization: Bearer ' . $this->getBearerToken(), // Set the authorization header
            'X-Idempotency-Key: ' . $this->generateIdempotencyKey() // Add idempotency key for preventing duplicate requests
        ];

        // Set the request URL
        $url = $this->baseUrl . $endpoint;

        // Handle GET request (appending query parameters to the URL)
        if (strtoupper($method) === 'GET' && !empty($data)) {
            // Build the query string
            $url .= '?' . http_build_query($data);
        }

        // Convert data array to JSON string for POST/PUT requests
        $jsonData = json_encode($data);

        // Set cURL options based on the HTTP method
        curl_setopt($ch, CURLOPT_URL, $url); // Set the full URL (baseUrl + endpoint)

        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true); // Specify the request method as POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Attach the JSON encoded data to the request
        } elseif (strtoupper($method) === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Specify the request method as PUT
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Attach the JSON encoded data to the request
        } elseif (strtoupper($method) === 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, true); // Specify the request method as GET
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set the custom headers for the request

        // Execute the cURL request and capture the response
        $response = curl_exec($ch);


        // Check for cURL errors
        if (curl_errno($ch)) {
            // If there is a cURL error, throw an exception or return an error response
            throw new BirPayException('cURL Error: ' . curl_error($ch));
        }

        // Get the HTTP response code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close the cURL session
        curl_close($ch);

        // Handle and return the response
        return $this->handleResponse($response, intval($httpCode), $responseClass);
    }


    // Handle response and return the appropriate response class

    /**
     * @param $response
     * @param int $httpCode
     * @param $responseClass
     * @return mixed
     * @throws BirPayException
     */
    private function handleResponse($response, int $httpCode, $responseClass): APIResponse
    {
        $responseBody = json_decode($response, true);
        if (!is_array($responseBody)) {
            throw new BirPayException("Wrong response: " . $responseBody);
        }
        return new $responseClass($responseBody, $httpCode);
    }


    /**
     * @param $orderId
     * @param $description
     * @param PaymentMethod $paymentMethod
     * @param ConfirmationType $confirmationType This type defines what kind of confirmation will user pass.
     * @param string $returnUrl This field is used for where acquirer will return the customer.
     * @param $amount
     * @param string $currency
     * @return CreatePaymentResponse
     * @throws BirPayException
     */
    public function createPayment($orderId, $description, PaymentMethod $paymentMethod, ConfirmationType $confirmationType, string $returnUrl, $amount, string $currency = "AZN"): CreatePaymentResponse
    {

        $data = [
            'amount' => [
                "value" => $amount,
                "currency" => $currency
            ],
            'capture' => true,
            'description' => $description,
            "paymentMethodData" => [
                "type" => $paymentMethod->value
            ],
            "confirmation" => [
                "type" => $confirmationType->value,
                "returnUrl" => $returnUrl
            ],
            "posDetail" => [
                "merchantId" => $this->merchantId,
                "terminalId" => $this->terminalId
            ],

            'metadata' => [
                'orderNo' => $orderId
            ],

        ];

        if ($paymentMethod == PaymentMethod::BANK_CARD) {//bug fix
            unset($data['paymentMethodData']);
        }

        return $this->sendRequest('/v1/payments', $data, CreatePaymentResponse::class);
    }


    /**
     * @param $paymentId
     * @param $amount
     * @param ConfirmationType|null $confirmationType This type defines what kind of confirmation will user pass.
     * @return RefundPaymentResponse
     * @throws BirPayException
     */
    public function refundPayment($paymentId, $amount = null, ?ConfirmationType $confirmationType = null): RefundPaymentResponse
    {
        $data = [
            "id" => $paymentId
        ];

        if ($amount != null) {
            $data['amount'] = $amount;
        }

        if ($confirmationType != null) {
            $data['confirmation'] = [
                'type' => $confirmationType->value
            ];
        }

        return $this->sendRequest('/v1/refunds', $data, RefundPaymentResponse::class);
    }

    /**
     * @param string $paymentId Payment ID
     * @return RetrievePaymentResponse
     * @throws BirPayException
     */
    public function retrievePayment(string $paymentId): RetrievePaymentResponse
    {
        return $this->sendRequest('/v1/payments/' . $paymentId, [], RetrievePaymentResponse::class, 'GET');
    }

    /**
     * @param string $refundId Refund ID
     * @return RetrieveRefundResponse
     * @throws BirPayException
     */
    public function retrieveRefund(string $refundId): RetrieveRefundResponse
    {
        return $this->sendRequest('/v1/refunds/' . $refundId, [], RetrieveRefundResponse::class, 'GET');
    }


    /**
     * Cancel a transaction.
     *
     * @param string $paymentId The payment ID to cancel.
     * @return CancelPaymentResponse The response from the cancel operation.
     * @throws BirPayException If the request fails.
     */
    public function cancelOperation(string $paymentId): CancelPaymentResponse
    {
        return $this->sendRequest('/v1/payments/' . $paymentId . '/cancel', [], RetrievePaymentResponse::class, 'PUT');
    }

    /**
     * @throws BirPayException
     */
    public function catchWebhook(): WebhookResponse
    {

        // Read raw POST body
        $payload = file_get_contents('php://input');

        // Signature sent from provider
        $sentSignature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';

        if (!$this->auth()->isValid($payload, $sentSignature)) {
            throw new BirPayException("Invalid signature", "invalid_signature");
        }

        // Decode JSON
        $data = json_decode($payload, true);

        if (!$data) {
            throw new BirPayException("Invalid json", "invalid_webhook_json");
        }

        return new WebhookResponse($data, 200);
    }

}
