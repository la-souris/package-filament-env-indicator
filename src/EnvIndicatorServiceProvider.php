<?php

declare(strict_types=1);

namespace LaSouris\FilamentEnvIndicator;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EnvIndicatorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-env-indicator');
    }

    public function packageBooted(): void
    {
        if (app()->isProduction()) {
            return;
        }

        FilamentAsset::register([
            Css::make('filament-env-indicator', __DIR__ . '/../resources/dist/env-indicator.css'),
        ], 'la-souris/filament-env-indicator');
    }
}
