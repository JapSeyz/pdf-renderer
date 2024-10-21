<?php

namespace JapSeyz\PDFRenderer\Services;

use JapSeyz\PDFRenderer\Contracts\PDFRenderer;
use Prince\Prince;
use function class_exists;
use function config;
use function ob_get_clean;
use function public_path;
use function view;

class PrinceService implements PDFRenderer
{
    protected string $html;
    protected bool $landscape = false;
    protected ?string $header = null;
    protected ?string $footer = null;
    protected array $margins = [5, 5, 15, 5];

    public array $options = [];

    public function __construct()
    {
        if (! class_exists(\Prince\Prince::class)) {
            throw new \Exception('Prince package is not installed. Please install yeslogic/prince-php-wrapper');
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

        $prince = new Prince(config('pdf-renderer.prince_path'));
        $prince->setJavaScript(true);
        $prince->setFileRoot(public_path());
        $prince->setPDFCreator($this->options['creator'] ?? 'PDFRenderer');
        $prince->setPDFAuthor($this->options['author'] ?? 'PDFRenderer');

        ob_start();
        $prince->convertStringToPassthru($html);

        return ob_get_clean();
    }

    public function landscape(bool $landscape): self
    {
        $this->landscape = true;

        return $this;
    }

    public function header(?string $header): self
    {
        throw new \Exception('Method not supported for Prince');
    }

    public function footer(?string $footer): self
    {
        throw new \Exception('Method not supported for Prince');
    }

    public function setOption(string $key, mixed $value): self
    {
        match ($key) {
            'author'  => $this->options['author'] = $value,
            'creator' => $this->options['creator'] = $value,
            default   => throw new \Exception('Unsupported option for Prince'),
        };

        return $this;
    }
}
