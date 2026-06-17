# Filament Environment Indicator

`la-souris/filament-env-indicator` is a Filament v5 plugin that makes non-production panels visually obvious by:

- recoloring the Filament topbar,
- recoloring the global search field to match,
- and adding a small environment badge before global search.

This helps avoid mistakes when switching between `local`, staging-like, and demo/admin environments.

## Requirements

- PHP `^8.4`
- Filament `^5.6`

## Installation

```bash
composer require la-souris/filament-env-indicator
```

## Basic usage

Register the plugin on your panel provider:

```php
use Filament\Panel;
use LaSouris\FillamentEnvIndicator\EnvironmentIndicatorPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(EnvironmentIndicatorPlugin::make());
}
```

## Default behavior

- Nothing is shown in `production` (styles and badge are disabled).
- Defaults are provided for:
  - `local` and `development` (green),
  - `demo` (amber),
  - `acceptance` (red).
- Environments not registered on the plugin are ignored.
- The badge text is the current Laravel environment name (`app()->environment()`).
- The current git branch is included as the badge tooltip (`title`) when available.

## Customizing environments

Use `environment()` to add new environment themes or override defaults:

```php
use Filament\Support\Colors\Color;
use LaSouris\FillamentEnvIndicator\EnvironmentIndicatorPlugin;

EnvironmentIndicatorPlugin::make()
    ->environment('staging', Color::Yellow, topbarShade: '600', topbarAccent: '100', textColor: 'black')
    ->environment('local', Color::Blue, topbarShade: '700', topbarAccent: '100', textColor: 'white');
```

Method signature:

```php
environment(
    string $name,
    array $palette,
    string $topbarShade = '500',
    string $topbarAccent = '50',
    string $textColor = 'black',
)
```

- `$palette` should be a Filament color palette (for example `Color::Blue`).
- Shade values like `'50'`, `'500'`, `'800'` must exist on the palette.
