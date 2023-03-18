<?php

namespace JapSeyz\PDFRenderer\Facades;

use Illuminate\Support\Facades\Facade;

class PDFRenderer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JapSeyz\PDFRenderer\PDFRenderer::class;
    }
}