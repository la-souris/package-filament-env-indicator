<?php

declare(strict_types=1);

namespace LaSouris\FilamentEnvIndicator\Tests;

use Illuminate\Support\HtmlString;
use LaSouris\FilamentEnvIndicator\EnvironmentIndicatorPlugin;
use PHPUnit\Framework\Attributes\Test;

class BadgeTest extends TestCase
{
    #[Test]
    public function itRendersNoBadgeInProduction(): void
    {
        $this->setEnvironmentName('production');

        $this->assertNull($this->invokePrivate(EnvironmentIndicatorPlugin::make(), 'badge'));
    }

    #[Test]
    public function itRendersNoBadgeForAnUnregisteredEnvironment(): void
    {
        $this->setEnvironmentName('some-unknown-env');

        $this->assertNull($this->invokePrivate(EnvironmentIndicatorPlugin::make(), 'badge'));
    }

    #[Test]
    public function itRendersABadgeWithTheCapitalizedEnvironmentName(): void
    {
        $this->setEnvironmentName('local');

        $badge = $this->invokePrivate(EnvironmentIndicatorPlugin::make(), 'badge');

        $this->assertInstanceOf(HtmlString::class, $badge);
        $this->assertStringContainsString('fi-env-indicator-badge', (string) $badge);
        $this->assertStringContainsString('Local', (string) $badge);
    }
}
