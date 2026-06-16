# Filament Environment Indicator

A Filament v3 plugin that colors the admin panel's topbar and shows a badge in the global search bar so you can tell at a glance which environment you're looking at.

The badge also shows the current git branch when available.

## Behavior

- Hidden on `production` and for guests.
- Renders for any environment registered on the plugin.

## Installation

Install the package via Composer:

```bash
composer require la-souris/fillament-env-indicator
```

## Usage

Register the plugin on your panel:

```php
use LaSouris\FillamentEnvIndicator\EnvironmentIndicatorPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(EnvironmentIndicatorPlugin::make());
}
```

## Adding or overriding environments

Use the `environment()` method to register a new environment or replace the defaults. Pass any `Filament\Support\Colors\Color` palette and pick which shade to use for the topbar and badge.

```php
use LaSouris\FillamentEnvIndicator\EnvironmentIndicatorPlugin;
use Filament\Support\Colors\Color;

EnvironmentIndicatorPlugin::make()
    ->environment('staging', Color::Yellow, topbarShade: '600', badgeShade: '700')
    ->environment('local', Color::Blue);
```

## How it works

- `STYLES_AFTER` render hook injects a `<style>` block that recolors `.fi-topbar`.
- `GLOBAL_SEARCH_BEFORE` render hook renders a pill-shaped badge with the environment name and current git branch (via `git branch --show-current`).
- The shade indices (e.g. `'500'`, `'700'`) match keys on Filament's `Color` palettes.