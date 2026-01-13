<?php

use JapSeyz\PDFRenderer\Overrides\Typeset\UriResolver\BrowsershotService;

return [
    // Possible values are:
    // BrowsershotService::class, API2PDFService::class, SnappyService::class, TypesetService::class, PrinceService::class
    'driver' => BrowsershotService::class,

    // Wait for 500ms after the last network connection
    'wait_for_idle' => true,

    // Path to the Chromium executable
    'chromium_path' => env('CHROMIUM_PATH', '/usr/bin/chromium-browser'),

    // Path to the Prince executable
    'prince_path' => env('PRINCE_PATH', '/usr/bin/prince'),

    // Possible values are:
    // 'screen', 'print'
    'emulate_media' => 'print',

    'user_agent' => null,

    'api2pdf' => [
        'key'    => env('API2PDF_KEY'),

        // Possible values are:
        // 'chrome', 'wkhtmltopdf'
        'driver' => 'chrome'
    ],
];
