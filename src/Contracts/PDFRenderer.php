<?php

namespace JapSeyz\PDFRenderer\Contracts;

interface PDFRenderer
{
    public function template(string $html): self;

    public function view(string $template, array $data = []): self;

     public function setOption(string $key, mixed $value): self;

    public function landscape(bool $landscape): self;

    public function header(?string $header): self;

    public function footer(?string $footer): self;

    public function margins(int $top = 5, int $right = 5, int $bottom = 15, int $left = 5): self;

    public function render(): string|null;
}
