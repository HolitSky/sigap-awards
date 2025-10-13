<?php

return [
    'sheets' => [
        'spreadsheet_id_bpkh' => env('GOOGLE_SHEETS_ID_BPKH'),
        'spreadsheet_id_produsen' => env('GOOGLE_SHEETS_ID_PRODUSEN'),
        'range'          => env('GOOGLE_SHEETS_RANGE', 'Sheet1!A:ZZ'),
        'api_key'        => env('GOOGLE_API_KEY'),
    ],
];


