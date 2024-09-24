<?php

namespace Ferdiunal\LaravelTranslator\Tests;

use Ferdiunal\LaravelTranslator\LaravelTranslatorServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Ferdiunal\\LaravelTranslator\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTranslatorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app) {}
}
