<?php

namespace App\Services\Wave;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WaveCheckoutService
{
    protected string $baseUrl;

    protected string $apiKey;

    protected string $successUrl;

    protected string $errorUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.wave.base_url', 'https://api.wave.com/v1'), '/');
        $this->apiKey = config('services.wave.api_key', '');
        $this->successUrl = config('services.wave.success_url', '');
        $this->errorUrl = config('services.wave.error_url', '');
    }

    /**
     * Create a Wave checkout session.
     *
     * @param  integer  $amount  Amount to charge (e.g. "1000")
     * @param  string|null  $clientReference  Client reference
     * @return Response
     */
    public function createCheckoutSession(
        string $amount,
        string $clientReference = null,
    ): Response {
        $payload = array_merge([
            'amount' => $amount,
            'currency' => config('services.wave.currency', 'XOF'),
            'client_reference' => $clientReference,
            'success_url' => $this->successUrl,
            'error_url' => $this->errorUrl,
        ]);

        $timestamp = time();
        $signature = hash_hmac("sha256", $timestamp . json_encode($payload), config('services.wave.signing_secret'));
        $wave_signature = "t={$timestamp},v1={$signature}";

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Wave-Signature' => $wave_signature,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/checkout/sessions', $payload);
    }

    /**
     * Search checkout sessions by client reference.
     *
     * @param  string  $clientReference  Client reference (e.g. "123456")
     * @return Response
     */
    public function searchCheckoutSessions(string $clientReference): Response
    {
        $timestamp = time();
        $signature = hash_hmac("sha256", $timestamp . json_encode($payload), config('services.wave.signing_secret'));
        $wave_signature = "t={$timestamp},v1={$signature}";

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Wave-Signature' => $wave_signature,
        ])->get($this->baseUrl . '/checkout/sessions/search', [
            'client_reference' => $clientReference,
        ]);
    }

    /**
     * Check if the service is configured (API key present).
     */
    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }
}
