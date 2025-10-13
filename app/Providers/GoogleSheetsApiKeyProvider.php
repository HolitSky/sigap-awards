<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Revolution\Google\Client\GoogleApiClient;
use GuzzleHttp\Client as GuzzleClient;

class GoogleSheetsApiKeyProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // no-op
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Map our ENV-based API key into the package's expected config key.
        // The underlying Google client wrapper reads 'google.developer_key'.
        $apiKey = env('GOOGLE_API_KEY');

        if (!empty($apiKey)) {
            config(['google.developer_key' => $apiKey]);
        }

        // Keep a copy in our local google.sheets namespace for convenience
        // in case the app code wants to read it.
        if (!empty($apiKey) && !config('google.sheets.api_key')) {
            config(['google.sheets.api_key' => $apiKey]);
        }

        // Optionally map spreadsheet id and range if present.
        if ($spreadsheetId = env('GOOGLE_SHEETS_ID')) {
            config(['google.sheets.spreadsheet_id' => $spreadsheetId]);
        }
        if ($range = env('GOOGLE_SHEETS_RANGE')) {
            config(['google.sheets.range' => $range]);
        }

        // Configure CA bundle for SSL verification to avoid cURL error 60 on Windows/Laragon
        $defaultLaragonCa = 'C:\\laragon\\etc\\ssl\\cacert.pem';
        $envCa = env('GOOGLE_CA_BUNDLE') ?: env('SSL_CERT_FILE') ?: env('CURL_CA_BUNDLE');
        $caBundle = $envCa ?: (file_exists($defaultLaragonCa) ? $defaultLaragonCa : null);

        if ($caBundle && file_exists($caBundle)) {
            // Ensure environment hints are present for Guzzle/cURL
            @putenv('SSL_CERT_FILE='.$caBundle);
            @putenv('CURL_CA_BUNDLE='.$caBundle);
        }

        // Optional override to disable SSL verify locally (NOT for production)
        $disableVerify = filter_var(env('GOOGLE_DISABLE_SSL_VERIFY', false), FILTER_VALIDATE_BOOLEAN);

        // After the GoogleApiClient is resolved, inject a Guzzle client with proper verify settings
        $this->app->afterResolving(GoogleApiClient::class, function (GoogleApiClient $google) use ($caBundle, $disableVerify) {
            $guzzleOptions = [
                'base_uri' => \Google\Client::API_BASE_PATH,
                'http_errors' => false,
            ];

            if ($disableVerify) {
                $guzzleOptions['verify'] = false;
            } elseif ($caBundle && file_exists($caBundle)) {
                $guzzleOptions['verify'] = $caBundle;
            }

            $google->getClient()->setHttpClient(new GuzzleClient($guzzleOptions));
        });
    }
}


