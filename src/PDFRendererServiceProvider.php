<?php

namespace JapSeyz\PDFRenderer;

use Spatie\LaravelPackageTools\Package;
use JapSeyz\PDFRenderer\Contracts\PDFRenderer;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PDFRendererServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('pdf-renderer')
            ->hasConfigFile();
    }

    public function boot()
    {
        parent::boot();

        $this->app->bind(
            PDFRenderer::class,
            config('pdf-renderer.driver')
        );
    }
}
