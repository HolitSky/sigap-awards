<?php

return [
    'sheets' => [
        'spreadsheet_id' => env('GOOGLE_SHEETS_ID'),
        'range'          => env('GOOGLE_SHEETS_RANGE', 'Sheet1!A:ZZ'),
        'api_key'        => env('GOOGLE_API_KEY'),
    ],
];


