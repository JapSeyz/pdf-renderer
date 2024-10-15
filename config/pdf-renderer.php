<?php

use JapSeyz\PDFRenderer\Overwrites\Typeset\UriResolver\BrowsershotService;

return [
    // Possible values are:
    // BrowsershotService::class, API2PDFService::class, SnappyService::class, TypesetService::class
    'driver' => BrowsershotService::class,

    // Wait for 500ms after the last network connection
    'wait_for_idle' => true,

    // Path to the Chromium executable
    'chromium_path' => env('CHROMIUM_PATH', '/usr/bin/chromium-browser'),

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
