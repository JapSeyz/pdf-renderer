<?php

use JapSeyz\PDFRenderer\Services\BrowsershotService;

return [
    // Possible values are:
    // BrowsershotService::class, API2PDFService::class
    'driver' => BrowsershotService::class,

    'api2pdf' => [
        'key'    => env('API2PDF_KEY'),

        // Possible values are:
        // 'chrome', 'wkhtmltopdf'
        'driver' => 'chrome'
    ],
];
