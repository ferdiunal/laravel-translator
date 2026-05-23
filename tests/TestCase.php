<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Tests;

use Ferdiunal\LaravelTranslator\LaravelTranslatorServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName): string => 'Ferdiunal\\LaravelTranslator\\Database\\Factories\\'.class_basename($modelName).'Factory',
        );
    }

    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelTranslatorServiceProvider::class,
        ];
    }

    /** @param Application $app */
    public function getEnvironmentSetUp($app): void {}
}
