<?php

declare(strict_types=1);

namespace LaSouris\FillamentEnvIndicator;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;

class EnvironmentIndicatorPlugin implements Plugin
{
    /**
     * @var array<string, array{
     *     palette: array<int|string, string>,
     *     topbarShade: string,
     *     topbarAccent: string,
     *     textColor: string
     * }>
     */
    private array $environments = [];

    public function __construct()
    {
        $green = ['palette' => Color::Green, 'topbarShade' => '800', 'topbarAccent' => '50', 'textColor' => 'black'];

        $this->environments = [
            'local'       => $green,
            'development' => $green,
            'demo'        => [
                'palette' => Color::Amber,
                'topbarShade' => '500',
                'topbarAccent' => '50',
                'textColor' => 'black'
            ],
            'acceptance'  => [
                'palette' => Color::Red,
                'topbarShade' => '500',
                'topbarAccent' => '50',
                'textColor' => 'black'
            ],
        ];
    }

    public static function make(): self
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'environment-indicator';
    }

    /**
     * @param  array<int|string, string>  $palette
     */
    public function environment(
        string $name,
        array $palette,
        string $topbarShade = '500',
        string $topbarAccent = '50',
        string $textColor = 'black'
    ): static {
        $this->environments[$name] = [
            'palette'     => $palette,
            'topbarShade' => $topbarShade,
            'topbarAccent'  => $topbarAccent,
            'textColor' => $textColor,
        ];

        return $this;
    }

    public function register(Panel $panel): void
    {
        $theme = $this->theme();

        if ($theme === null) {
            return;
        }

        FilamentAsset::registerCssVariables([
            'bg-env-header'          => $theme['palette'][$theme['topbarShade']],
            'fg-env-header'          => $theme['palette'][$theme['topbarAccent']],
            'search-text-env-header' => $theme['textColor'],
        ], 'la-souris/filament-env-indicator');

        $panel->renderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): ?HtmlString => $this->badge(),
        );
    }

    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * @return null|array{
     *     palette: array<int|string, string>,
     *     topbarShade: string,
     *     topbarAccent: string,
     *     textColor: string
     * }>
     */
    private function theme(): ?array
    {
        if (app()->isProduction()) {
            return null;
        }

        return $this->environments[app()->environment()] ?? null;
    }

    private function badge(): ?HtmlString
    {
        $theme = $this->theme();

        if ($theme === null) {
            return null;
        }

        $environment = e(ucfirst(app()->environment()));
        $branch      = e(trim((string) @exec('git branch --show-current')));

        return new HtmlString(<<<HTML
            <span class="fi-env-indicator-badge" title="{$branch}">
                {$environment}
            </span>
            HTML);
    }
}
