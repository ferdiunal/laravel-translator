<?php

declare(strict_types=1);

use Ferdiunal\LaravelTranslator\Exceptions\UnsupportedTranslatorException;
use Ferdiunal\LaravelTranslator\Manager\TranslatorManager;
use Ferdiunal\LaravelTranslator\Translators\DeepLTranslator;
use Ferdiunal\LaravelTranslator\Translators\MyMemoryTranslator;
use Ferdiunal\LaravelTranslator\Translators\NLPCloudTranslator;
use Ferdiunal\LaravelTranslator\Translators\OpenAITranslator;

it('resolves acronym and legacy aliases through the explicit registry', function (string $key, string $expectedClass): void {
    expect(app(TranslatorManager::class)->translator($key))->toBeInstanceOf($expectedClass);
})->with([
    'deepl canonical acronym' => ['deepl', DeepLTranslator::class],
    'openai canonical acronym' => ['openai', OpenAITranslator::class],
    'nlpcloud canonical acronym' => ['nlpcloud', NLPCloudTranslator::class],
    'nlpCloud legacy alias' => ['nlpCloud', NLPCloudTranslator::class],
    'mymemory canonical lower-case' => ['mymemory', MyMemoryTranslator::class],
    'myMemory legacy alias' => ['myMemory', MyMemoryTranslator::class],
]);

it('fails with a typed exception for unsupported translators', function (): void {
    app(TranslatorManager::class)->translator('unknown-provider');
})->throws(UnsupportedTranslatorException::class, 'Translator [unknown-provider] is not supported.');

it('lists only enabled providers with stable metadata', function (): void {
    $providers = app(TranslatorManager::class)->providers();

    expect(array_keys($providers))->toContain('google', 'bing', 'deepl', 'mymemory', 'nlpcloud', 'openai')
        ->and($providers['openai'])->toMatchArray([
            'key' => 'openai',
            'title' => 'OpenAI',
        ]);
});
