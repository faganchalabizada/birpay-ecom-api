<?php

namespace FaganChalabizada\BirPay;

use FaganChalabizada\BirPay\Exception\BirPayException;

class Auth
{
    private string $demoUrl = 'https://precheckout.kapitalbank.az/api';
    private string $prodUrl = 'https://checkout.kapitalbank.az/api';

    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $accessToken;
    private string $refreshToken;
    private int $accessTokenExpiresIn = 0;
    private int $refreshTokenExpiresIn = 0;

    /**
     * Auth constructor.
     *
     * @param string $clientId The client ID for authentication.
     * @param string $clientSecret The client secret for authentication.
     * @param bool $demo
     */
    public function __construct(string $clientId = 'birpay-test', string $clientSecret = 'mc8JHRvS9JyaElcj1ozm1Fpd5Gpaj73q', $demo = true)
    {
        $this->baseUrl = $demo ? $this->demoUrl : $this->prodUrl;

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        // Start the session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if tokens are already stored in session
        if (isset($_SESSION['access_token']) && isset($_SESSION['refresh_token'])) {
            $this->accessToken = $_SESSION['access_token'];
            $this->refreshToken = $_SESSION['refresh_token'];
            $this->accessTokenExpiresIn = $_SESSION['access_token_expires_in'];
            $this->refreshTokenExpiresIn = $_SESSION['refresh_token_expires_in'];
        }
    }

    /**
     * Get the access token, refreshing it if necessary.
     *
     * @return string The current access token.
     * @throws BirPayException If the token is unavailable or cannot be refreshed.
     */
    public function getAccessToken(): string
    {
        if ($this->isAccessTokenExpired()) {

            if (!$this->isRefreshAccessTokenExpired()) {
                $this->refreshAccessToken();
            } else {
                $this->createAccessToken();
            }
        }

        return $this->accessToken;
    }

    /**
     * Check if the refresh access token has expired.
     *
     * @return bool True if the refresh access token is expired, false otherwise.
     */
    private function isRefreshAccessTokenExpired(): bool
    {
        // Simple check based on the expiry time of the access token
        return time() > $this->refreshTokenExpiresIn || !isset($this->refreshToken);
    }

    /**
     * Check if the access token has expired.
     *
     * @return bool True if the access token is expired, false otherwise.
     */
    private function isAccessTokenExpired(): bool
    {
        // Simple check based on the expiry time of the access token
        return time() > $this->accessTokenExpiresIn;
    }


    /**
     * Refresh the access token using the refresh token.
     *
     * @throws BirPayException If refreshing the token fails.
     */
    private function refreshAccessToken(): void
    {


        echo "refreshAccessToken";

        $data = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken
        ];

        // Send the refresh token request to get a new access token
        $response = $this->sendRequest($data);

        if (isset($response['access_token'])) {

            $this->accessToken = $response['access_token'];
            $this->refreshToken = $response['refresh_token'] ?? '';
            $this->accessTokenExpiresIn = time() + ($response['expires_in'] ?? 0); // Set access token expiry time
            $this->refreshTokenExpiresIn = time() + ($response['refresh_expires_in'] ?? 0); // Set refresh token expiry time

            // Store the tokens in the session
            $_SESSION['access_token'] = $this->accessToken;
            $_SESSION['refresh_token'] = $this->refreshToken;
            $_SESSION['access_token_expires_in'] = $this->accessTokenExpiresIn;
            $_SESSION['refresh_token_expires_in'] = $this->refreshTokenExpiresIn;
        } else {
            throw new BirPayException('Failed to refresh access token.');
        }
    }

    /**
     * Request a new access token using client credentials.
     *
     * @throws BirPayException If the token request fails.
     */
    public function createAccessToken(): void
    {

        $data = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => 'email'
        ];

        // Send the token request to get the access token
        $response = $this->sendRequest($data);

        if (isset($response['access_token'])) {

            $this->accessToken = $response['access_token'];
            $this->refreshToken = $response['refresh_token'] ?? '';
            $this->accessTokenExpiresIn = time() + ($response['expires_in'] ?? 0); // Set access token expiry time
            $this->refreshTokenExpiresIn = time() + ($response['refresh_expires_in'] ?? 0); // Set refresh token expiry time

            // Store the tokens in the session
            $_SESSION['access_token'] = $this->accessToken;
            $_SESSION['refresh_token'] = $this->refreshToken;
            $_SESSION['access_token_expires_in'] = $this->accessTokenExpiresIn;
            $_SESSION['refresh_token_expires_in'] = $this->refreshTokenExpiresIn;
        } else {
            throw new BirPayException('Failed to create access token.');
        }
    }

    /**
     * Sends a POST request to the given endpoint with the provided data.
     * This is used for token request and refresh token request.
     *
     * @param array $data The data to be sent in the request body, typically as an associative array.
     *
     * @return array The decoded JSON response from the API.
     * @throws BirPayException If the request fails or the response is invalid.
     */
    private function sendRequest(array $data): array
    {// Initialize cURL session
        $ch = curl_init();

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        // Prepare the data for URL-encoded POST request
        $postFields = http_build_query($data);

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->baseUrl . '/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields, // Send data in URL-encoded format
            CURLOPT_HTTPHEADER => $headers
        ]);

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            throw new BirPayException('cURL Error: ' . curl_error($ch));
        }

        // Get the HTTP response code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close the cURL session
        curl_close($ch);

        // Handle response based on status code
        if ($httpCode === 200) {
            return json_decode($response, true); // Decode JSON response
        } else {
            throw new BirPayException('Error: ' . $response);
        }
    }



    /**
     * Creates an HMAC SHA-256 signature for a given payload.
     *
     * This method takes the payload data (e.g., request body) and generates a secure HMAC SHA-256 hash.
     * The resulting hash is then encoded in base64 format for easy transmission in headers or other parts of HTTP requests.
     *
     * @param string $payload The data (payload) to be signed, usually the body of the request.
     *
     * @return string The base64-encoded HMAC SHA-256 signature for the given payload.
     *
     * @throws \Exception If the HMAC generation fails due to invalid algorithm or other errors.
     */
    public function createSignature(string $payload): string
    {
        // Generate HMAC SHA-256 hash
        $hash = hash_hmac('sha256', $payload, $this->clientSecret, true);
        // Return the hash as a base64 encoded string
        return base64_encode($hash);
    }

    /**
     * Validates the received signature against the generated signature for the given payload.
     *
     * This method compares the received `X-Signature` header (or other signature source) with the signature
     * generated from the payload using the shared secret key. It ensures the integrity and authenticity of the data.
     *
     * @param string $payload The data (payload) to be verified, usually the body of the request.
     * @param string $sentSignature The signature received in the request header (X-Signature).
     *
     * @return bool Returns `true` if the signature is valid, `false` otherwise.
     *
     * @throws \Exception If there is an error during signature generation or comparison.
     */
    public function isValid(string $payload, string $sentSignature): bool
    {
        // Generate the expected signature for the given payload
        $generatedSignature = $this->createSignature($payload);

        // Use `hash_equals` for constant time comparison to avoid timing attacks
        return hash_equals($generatedSignature, $sentSignature);
    }

}

