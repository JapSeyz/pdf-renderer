<?php

namespace JapSeyz\PDFRenderer\Overwrites\Typesetsh\UriResolver;

class File extends \Typesetsh\UriResolver\File
{
    public function resolve(string $uri, string $baseUri): array
    {
        // Treat "/images/logo.png" as relative
        // eg. <img src="/images/logo.png"> should take the file from
        // public_path('/images/logo.png') not the OS root /images/logo.png
        $uri = ltrim($uri, '/');

        return parent::resolve($uri, $baseUri);
    }
}