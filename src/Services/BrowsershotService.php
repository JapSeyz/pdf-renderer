<?php

namespace JapSeyz\PDFRenderer\Services;

use Spatie\Browsershot\Browsershot;
use JapSeyz\PDFRenderer\Contracts\PDFRenderer;
use function config;

class BrowsershotService implements PDFRenderer
{
    protected string $html;
    protected array $options = [
        'allow-running-insecure-content',
        'autoplay-policy'     => 'user-gesture-required',
        'disable-component-update',
        'disable-domain-reliability',
        'disable-features'    => 'AudioServiceOutOfProcess,IsolateOrigins,site-per-process',
        'disable-print-preview',
        'disable-setuid-sandbox',
        'disable-site-isolation-trials',
        'disable-speech-api',
        'disable-web-security',
        'disable-dev-shm-usage',
        'disk-cache-size'     => 33554432,
        'enable-features'     => 'SharedArrayBuffer',
        'font-render-hinting' => 'none',
        'force-color-profile' => 'srgb',
        'hide-scrollbars',
        'ignore-gpu-blocklist',
        'mute-audio',
        'disable-gpu',
        'no-default-browser-check',
        'no-pings',
        'no-sandbox',
        'no-zygote',
        'use-gl'              => 'swiftshader',
        'window-size'         => '1920,1080',
        'disable-2d-canvas-clip-aa',
        'disable-accelerated-2d-canvas',
        'disable-breakpad',
        'disable-canvas-aa',
        'disable-desktop-notifications',
        'disable-extensions',
        'disable-gl-drawing-for-tests',
        'disable-permissions-api',
        'disable-sync',
        'no-first-run',
    ];
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
        $instance = Browsershot::html($this->html)
            ->addChromiumArguments($this->options)
            ->noSandbox()
            ->timeout(300)
            ->emulateMedia(config('pdf-renderer.emulate_media', 'print'))
            ->showBackground()
            ->ignoreHttpsErrors()
            ->disableJavascript()
            ->dismissDialogs()
            ->format('A4');

        if (config('pdf-renderer.wait_for_idle', true)) {
            $instance->waitUntilNetworkIdle();
        }

        if (($path = config('pdf-renderer.chromium_path'))) {
            $instance->setChromePath($path);
        }

        if ($this->landscape) {
            $instance->landscape();
        }

        if ($this->header || $this->footer) {
            $instance->showBrowserHeaderAndFooter();
        }

        $instance->headerHtml($this->header ?? '');
        $instance->footerHtml($this->footer ?? '');

        if ($this->margins) {
            $instance->margins(...$this->margins);
        }

        return $instance->pdf();
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
        if ($value) {
            $this->options[$key] = $value;
        } else {
            $this->options[] = $key;
        }

        return $this;
    }
}
