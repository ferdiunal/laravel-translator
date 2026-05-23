<?php

declare(strict_types=1);

use Ferdiunal\LaravelTranslator\Exceptions\InvalidTranslatorProviderException;
use Ferdiunal\LaravelTranslator\Exceptions\UnsupportedTranslatorException;
use Ferdiunal\LaravelTranslator\Manager\TranslatorManager;
use Ferdiunal\LaravelTranslator\Tests\Fixtures\EchoTranslator;

it('resolves and runs a custom provider configured by the host app', function (): void {
    config()->set('translator.providers.echo', [
        'driver' => EchoTranslator::class,
        'enabled' => true,
        'title' => 'Echo',
        'icon' => 'echo.svg',
    ]);

    expect(app(TranslatorManager::class)->translate('echo', 'en', 'tr', 'Hello'))->toBe('[en>tr] Hello');
});

it('does not expose or resolve disabled custom providers', function (): void {
    config()->set('translator.providers.echo', [
        'driver' => EchoTranslator::class,
        'enabled' => false,
        'title' => 'Echo',
    ]);

    expect(app(TranslatorManager::class)->providers())->not->toHaveKey('echo');

    app(TranslatorManager::class)->translator('echo');
})->throws(UnsupportedTranslatorException::class);

it('rejects custom providers that do not extend the translator contract', function (): void {
    config()->set('translator.providers.invalid', [
        'driver' => stdClass::class,
        'enabled' => true,
    ]);

    app(TranslatorManager::class)->translator('invalid');
})->throws(InvalidTranslatorProviderException::class, 'Translator provider [invalid] must extend');
