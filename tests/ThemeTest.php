<?php

declare(strict_types=1);

namespace LaSouris\FilamentEnvIndicator\Tests;

use Filament\Support\Colors\Color;
use LaSouris\FilamentEnvIndicator\EnvironmentIndicatorPlugin;
use PHPUnit\Framework\Attributes\Test;

class ThemeTest extends TestCase
{
    #[Test]
    public function itReturnsNoThemeInProduction(): void
    {
        $this->setEnvironmentName('production');

        $this->assertNull($this->invokePrivate(EnvironmentIndicatorPlugin::make(), 'theme'));
    }

    #[Test]
    public function itReturnsNoThemeForAnUnregisteredEnvironment(): void
    {
        $this->setEnvironmentName('some-unknown-env');

        $this->assertNull($this->invokePrivate(EnvironmentIndicatorPlugin::make(), 'theme'));
    }

    #[Test]
    public function itReturnsTheThemeForARegisteredEnvironment(): void
    {
        $this->setEnvironmentName('local');

        $theme = $this->invokePrivate(EnvironmentIndicatorPlugin::make(), 'theme');

        $this->assertIsArray($theme);
        $this->assertSame(Color::Green, $theme['palette']);
        $this->assertSame('800', $theme['topbarShade']);
    }

    #[Test]
    public function itReturnsACustomThemeRegisteredAtRuntime(): void
    {
        $this->setEnvironmentName('staging');

        $theme = $this->invokePrivate(
            EnvironmentIndicatorPlugin::make()->environment('staging', Color::Blue, topbarShade: '600'),
            'theme',
        );

        $this->assertSame(Color::Blue, $theme['palette']);
        $this->assertSame('600', $theme['topbarShade']);
    }
}
