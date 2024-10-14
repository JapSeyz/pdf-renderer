<?php

namespace JapSeyz\PDFRenderer\Services;

use Illuminate\Support\Facades\App;
use JapSeyz\PDFRenderer\Contracts\PDFRenderer;
use function class_exists;
use function public_path;
use function storage_path;
use function sys_get_temp_dir;
use function view;

class TypesetService implements PDFRenderer
{
    protected string $html;
    protected bool $landscape = false;
    protected ?string $header = null;
    protected ?string $footer = null;
    protected array $margins = [5, 5, 15, 5];

    public function __construct()
    {
        if (! class_exists(\Typesetsh\UriResolver::class)) {
            throw new \Exception('Typesetsh package is not installed. Please install typesetsh/typesetsh');
        }
    }

    public function template(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function view(string $template, array $data = []): self
    {
        return $this->template(view($template, $data)->render());
    }

    public function margins(int $top = 5, int $right = 5, int $bottom = 15, int $left = 5): self
    {
        $this->margins = [$top, $right, $bottom, $left];

        return $this;
    }

    public function render(): string|null
    {
        $base = public_path();
        $cachePath = sys_get_temp_dir();

        $orientation = $this->landscape ? 'landscape' : 'portrait';
        $styles = <<<CSS
@page {
    size: A4 {$orientation};
    margin: {$this->margins[0]}mm {$this->margins[1]}mm {$this->margins[2]}mm {$this->margins[3]}mm;
}
.page {
    break-after: page;
    break-before: page;
}
CSS;

        $html = str_replace('</head>', "<style>{$styles}</style></head>", $this->html);
        $resolveUri = \Typesetsh\UriResolver::all($cachePath, $base);

        $pdf = \Typesetsh\createPdf($html, $resolveUri);

        return $pdf->asString();
    }

    public function landscape(bool $landscape): self
    {
        $this->landscape = true;

        return $this;
    }

    public function header(?string $header): self
    {
        throw new \Exception('Method not supported for Typeset.sh');
    }

    public function footer(?string $footer): self
    {
        throw new \Exception('Method not supported for Typeset.sh');
    }

    public function setOption(string $key, mixed $value): self
    {
        throw new \Exception('Method not supported for Typeset.sh');
    }
}
