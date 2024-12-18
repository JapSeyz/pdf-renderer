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

    public array $allowedDirectories = [];

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
        $orientation = $this->landscape ? 'landscape' : 'portrait';
        $styles = <<<CSS
@page {
    size: A4 {$orientation};
    margin: {$this->margins[0]}mm {$this->margins[1]}mm {$this->margins[2]}mm {$this->margins[3]}mm;
}
CSS;
        $html = str_replace('</head>', "<style>{$styles}</style></head>", $this->html);

        // Allow fetching files from these directories
        if (! $this->allowedDirectories) {
            $this->allowedDirectories = [
                storage_path('app/public'),
                public_path(),
            ];
        }

        $cachePath = sys_get_temp_dir();

        // e.g. https://example.org/test.css
        $http = new \Typesetsh\UriResolver\Http($cachePath);

        // e.g. data:image/png;base64,iVBORw0KGgoAA...
        $data = new \Typesetsh\UriResolver\Data($cachePath);

        // e.g. file:/images/logo.png
        $file = new \JapSeyz\PDFRenderer\Overrides\Typesetsh\UriResolver\File($this->allowedDirectories);

        $resolveUri = new \Typesetsh\UriResolver(
            [
                'file'  => $file,
                'http'  => $http,
                'https' => $http,
                'data'  => $data,
            ],
            public_path()
        );

        $pdf = \Typesetsh\createPdf($html, $resolveUri);

        return $pdf->asString();
    }

    public function landscape(bool $landscape = true): self
    {
        $this->landscape = $landscape;

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
        match ($key) {
            'allowedDirectories' => $this->allowedDirectories = $value,
            default              => throw new \Exception('Unsupported option for Typeset.sh'),
        };

        return $this;
    }
}
