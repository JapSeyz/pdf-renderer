<?php

use JapSeyz\PDFRenderer\Services\BrowsershotService;

return [
    // Possible values are:
    // BrowsershotService::class, API2PDFService::class, SnappyService::class
    'driver' => BrowsershotService::class,

    // Possible values are:
    // 'screen', 'print'
    'emulate_media' => 'print',

    'api2pdf' => [
        'key'    => env('API2PDF_KEY'),

        // Possible values are:
        // 'chrome', 'wkhtmltopdf'
        'driver' => 'chrome'
    ],
];
