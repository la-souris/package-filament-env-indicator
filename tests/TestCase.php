<?php

declare(strict_types=1);

namespace LaSouris\FilamentEnvIndicator\Tests;

use LaSouris\FilamentEnvIndicator\EnvironmentIndicatorPlugin;
use Orchestra\Testbench\TestCase as Orchestra;
use ReflectionMethod;

abstract class TestCase extends Orchestra
{
    /**
     * Force the application environment for the current test.
     */
    protected function setEnvironmentName(string $environment): void
    {
        $this->app['env'] = $environment;
    }

    /**
     * Invoke a private method on the plugin (theme() / badge()).
     */
    protected function invokePrivate(EnvironmentIndicatorPlugin $plugin, string $method): mixed
    {
        return (new ReflectionMethod($plugin, $method))->invoke($plugin);
    }

    /**
     * Read the private $environments property from the plugin.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function readEnvironments(EnvironmentIndicatorPlugin $plugin): array
    {
        return (new \ReflectionProperty($plugin, 'environments'))->getValue($plugin);
    }
}
