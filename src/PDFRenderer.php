<?php

namespace JapSeyz\PDFRenderer;

class PDFRenderer
{
    public function __call(string $name, array $arguments)
    {
        /* @var Contracts\PDFRenderer $instance */
        $instance = app(Contracts\PDFRenderer::class);

        return $instance->{$name}(...$arguments);
    }
}
