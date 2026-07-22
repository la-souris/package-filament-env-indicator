<?php

declare(strict_types=1);

namespace LaSouris\FilamentEnvIndicator\Tests;

use Filament\Support\Colors\Color;
use LaSouris\FilamentEnvIndicator\EnvironmentIndicatorPlugin;
use PHPUnit\Framework\Attributes\Test;

class EnvironmentIndicatorPluginTest extends TestCase
{
    #[Test]
    public function makeReturnsAPluginInstance(): void
    {
        $this->assertInstanceOf(EnvironmentIndicatorPlugin::class, EnvironmentIndicatorPlugin::make());
    }

    #[Test]
    public function itHasAStableId(): void
    {
        $this->assertSame('environment-indicator', EnvironmentIndicatorPlugin::make()->getId());
    }

    #[Test]
    public function itRegistersTheDefaultEnvironments(): void
    {
        $environments = $this->readEnvironments(EnvironmentIndicatorPlugin::make());

        $this->assertSame(
            ['local', 'development', 'demo', 'acceptance'],
            array_keys($environments),
        );
    }

    #[Test]
    public function localAndDevelopmentShareTheSameGreenTheme(): void
    {
        $environments = $this->readEnvironments(EnvironmentIndicatorPlugin::make());

        $this->assertSame($environments['local'], $environments['development']);
        $this->assertSame(Color::Green, $environments['local']['palette']);
        $this->assertSame('800', $environments['local']['topbarShade']);
    }

    #[Test]
    public function environmentIsChainable(): void
    {
        $plugin = EnvironmentIndicatorPlugin::make();

        $this->assertSame($plugin, $plugin->environment('staging', Color::Blue));
    }

    #[Test]
    public function environmentRegistersANewThemeWithDefaults(): void
    {
        $plugin = EnvironmentIndicatorPlugin::make()
            ->environment('staging', Color::Blue);

        $environments = $this->readEnvironments($plugin);

        $this->assertArrayHasKey('staging', $environments);
        $this->assertSame([
            'palette'      => Color::Blue,
            'topbarShade'  => '500',
            'topbarAccent' => '50',
            'textColor'    => 'black',
        ], $environments['staging']);
    }

    #[Test]
    public function environmentCanOverrideADefaultTheme(): void
    {
        $plugin = EnvironmentIndicatorPlugin::make()
            ->environment('local', Color::Blue, topbarShade: '700', topbarAccent: '100', textColor: 'white');

        $environments = $this->readEnvironments($plugin);

        $this->assertSame([
            'palette'      => Color::Blue,
            'topbarShade'  => '700',
            'topbarAccent' => '100',
            'textColor'    => 'white',
        ], $environments['local']);
    }
}
