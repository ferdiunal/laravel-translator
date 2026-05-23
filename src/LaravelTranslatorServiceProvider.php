<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator;

use Ferdiunal\LaravelTranslator\Manager\TranslatorManager;
use Ferdiunal\LaravelTranslator\Registry\ProviderRegistry;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelTranslatorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-translator')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(ProviderRegistry::class, static fn (): ProviderRegistry => new ProviderRegistry);

        $this->app->singleton(
            TranslatorManager::class,
            fn ($app): TranslatorManager => new TranslatorManager(
                registry: $app->make(ProviderRegistry::class),
                container: $app,
            ),
        );

        $this->app->alias(TranslatorManager::class, LaravelTranslator::class);
    }
}
