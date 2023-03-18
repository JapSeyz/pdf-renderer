<?php

namespace JapSeyz\PDFRenderer\Services;

use Illuminate\Support\Facades\App;
use JapSeyz\PDFRenderer\Contracts\PDFRenderer;

class SnappyService implements PDFRenderer
{
    protected string $html;
    protected array $options = [];
    protected bool $landscape = false;
    protected ?string $header = null;
    protected ?string $footer = null;
    protected array $margins = [5, 5, 15, 5];

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
        /** @var \Barryvdh\Snappy\PdfWrapper $pdf */
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($this->html);

        foreach ($this->options as $key => $val) {
            $pdf->setOption($key, $val);
        }

        $pdf->setPaper('a4')
            ->setOrientation($this->landscape ? 'landscape' : 'portrait')
            ->setOption('margin-top', $this->margins[0])
            ->setOption('margin-right', $this->margins[1])
            ->setOption('margin-bottom', $this->margins[2])
            ->setOption('margin-left', $this->margins[3]);

        if ($this->header) {
            $pdf->setOption('header-html', $this->header);
        }

        if ($this->footer) {
            $pdf->setOption('footer-html', $this->footer);
        }

        return $pdf->output();
    }

    public function landscape(bool $landscape): self
    {
        $this->landscape = true;

        return $this;
    }

    public function header(?string $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function footer(?string $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    public function setOption(string $key, mixed $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }
}
