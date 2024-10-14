<?php

namespace JapSeyz\PDFRenderer\Services;

use Api2Pdf\Api2Pdf;
use JapSeyz\PDFRenderer\Contracts\PDFRenderer;
use function class_exists;

class API2PDFService implements PDFRenderer
{
    public string $html;
    protected Api2Pdf $instance;
    protected array $options = [
        'displayHeaderFooter' => true,
    ];
    protected array $margins = [5, 5, 5, 5];

     public function __construct()
    {
        if (! class_exists(\Api2Pdf\Api2Pdf::class)) {
            throw new \Exception('Api2pdf package is not installed. Please install api2pdf/api2pdf');
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

    public function landscape(bool $landscape): self
    {
        $this->setOption('landscape', $landscape);

        return $this;
    }

    public function header(?string $header): self
    {
        if ($header) {
            $this->setOption('headerTemplate', $header);
        }

        return $this;
    }

    public function footer(?string $footer): self
    {
        if ($footer) {
            $this->setOption('footerTemplate', $footer);
        }

        return $this;
    }

    public function setOption(string $key, mixed $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }

    public function render(): string
    {
        $method = match (config('pdf-renderer.api2pdf.driver')) {
            'chrome'      => 'chromeHtmlToPdf',
            'wkhtmltopdf' => 'wkHtmlToPdf',
            default       => 'chromeHtmlToPdf',
        };
        $resp = (new Api2Pdf(config('pdf-renderer.api2pdf.key')))
            ->{$method}($this->html, options: $this->options);

        return $resp->getFileContents() ?? '';
    }
}
