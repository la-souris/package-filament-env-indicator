<?php

declare(strict_types=1);

namespace LaSouris\FillamentEnvIndicator;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EnvIndicatorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-env-indicator')
            ->hasViews('env-indicator');
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('filament-env-indicator', __DIR__ . '/../resources/dist/env-indicator.css'),
        ], 'lasouris/filament-env-indicator');
    }
}
