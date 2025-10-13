<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    }
}


