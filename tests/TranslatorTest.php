<?php

declare(strict_types=1);

use Ferdiunal\LaravelTranslator\Facades\LaravelTranslator as LaravelTranslatorFacade;
use Ferdiunal\LaravelTranslator\LaravelTranslator;
use Ferdiunal\LaravelTranslator\Tests\Fixtures\EchoTranslator;

it('translates text through the static wrapper, facade, and helper without external APIs', function (): void {
    config()->set('translator.providers.echo', [
        'driver' => EchoTranslator::class,
        'enabled' => true,
        'title' => 'Echo',
        'icon' => 'echo.svg',
    ]);

    expect(LaravelTranslator::translate('echo', 'en', 'tr', 'Hello'))->toBe('[en>tr] Hello')
        ->and(LaravelTranslatorFacade::translate('echo', 'tr', 'en', 'Merhaba'))->toBe('[tr>en] Merhaba')
        ->and(translator('echo', 'en', 'de', 'Hi'))->toBe('[en>de] Hi');
});
