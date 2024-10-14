<?php

namespace JapSeyz\PDFRenderer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \JapSeyz\PDFRenderer\Contracts\PDFRenderer template(string $html)
 * @method static \JapSeyz\PDFRenderer\Contracts\PDFRenderer view(string $template, array $data = [])
 * @method static \JapSeyz\PDFRenderer\Contracts\PDFRenderer landscape(bool $landscape = true)
 * @method static \JapSeyz\PDFRenderer\Contracts\PDFRenderer header(?string $header)
 * @method static \JapSeyz\PDFRenderer\Contracts\PDFRenderer footer(?string $footer)
 * @method static \JapSeyz\PDFRenderer\Contracts\PDFRenderer setOption(string $key, mixed $value)
 *
 */
class PDFRenderer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JapSeyz\PDFRenderer\PDFRenderer::class;
    }
}