<?php

declare(strict_types=1);

namespace LaSouris\FillamentEnvIndicator;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;


class EnvironmentIndicatorPlugin implements Plugin
{
    /**
     * @var array<string, array{palette: array<int|string, string>, topbarShade: string, badgeShade: string}>
     */
    private array $environments = [];

    public function __construct()
    {
        $green = ['palette' => Color::Green, 'topbarShade' => '800', 'badgeShade' => '500'];

        $this->environments = [
            'local'       => $green,
            'development' => $green,
            'demo'        => ['palette' => Color::Orange, 'topbarShade' => '500', 'badgeShade' => '700'],
            'acceptance'  => ['palette' => Color::Red, 'topbarShade' => '500', 'badgeShade' => '700'],
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
    public function environment(string $name, array $palette, string $topbarShade = '500', string $badgeShade = '700'): static
    {
        $this->environments[$name] = [
            'palette'     => $palette,
            'topbarShade' => $topbarShade,
            'badgeShade'  => $badgeShade,
        ];

        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): ?HtmlString => $this->topbarStyle(),
        );

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
     * @return array{palette: array<int|string, string>, topbarShade: string, badgeShade: string}|null
     */
    private function theme(): ?array
    {
        if (auth()->guest() || app()->environment('production')) {
            return null;
        }

        return $this->environments[app()->environment()] ?? null;
    }

    private function topbarStyle(): ?HtmlString
    {
        $theme = $this->theme();

        if ($theme === null) {
            return null;
        }

        $background = $theme['palette'][$theme['topbarShade']];
        $foreground = $theme['palette']['50'];

        return new HtmlString(<<<HTML
            <style>
                .fi-topbar {
                    background-color: {$background} !important;
                }

                .fi-topbar .fi-icon-btn,
                .fi-topbar .fi-topbar-item,
                .fi-topbar .fi-dropdown-trigger,
                .fi-topbar .fi-topbar-item-label,
                .fi-topbar a {
                    color: {$foreground} !important;
                }
            </style>
            HTML);
    }

    private function badge(): ?HtmlString
    {
        $theme = $this->theme();

        if ($theme === null) {
            return null;
        }

        $background = $theme['palette'][$theme['badgeShade']];
        $foreground = $theme['palette']['50'];

        $environment = e(ucfirst(app()->environment()));

        $branch     = trim((string) @exec('git branch --show-current'));
        $branchHtml = $branch !== ''
            ? ' <code style="background:transparent;color:inherit;font-size:11px;">(' . e($branch) . ')</code>'
            : '';

        return new HtmlString(<<<HTML
            <span
                class="fi-env-indicator-badge"
                style="display:inline-flex;align-items:center;gap:0.25rem;padding:0.125rem 0.5rem;border-radius:9999px;font-size:11px;font-weight:600;line-height:1.25rem;background-color:{$background};color:{$foreground};"
            >
                {$environment}{$branchHtml}
            </span>
            HTML);
    }
}
