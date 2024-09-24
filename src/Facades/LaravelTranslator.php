<?php

namespace Ferdiunal\LaravelTranslator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ferdiunal\LaravelTranslator\LaravelTranslator
 *
 * @phpstan-type Translators array{
 *  google: \Ferdiunal\LaravelTranslator\Translators\GoogleTranslator,
 *  bing: \Ferdiunal\LaravelTranslator\Translators\BingTranslator,
 *  deepl: \Ferdiunal\LaravelTranslator\Translators\DeepLTranslator,
 *  myMemory: \Ferdiunal\LaravelTranslator\Translators\MyMemoryTranslator,
 *  nlpCloud: \Ferdiunal\LaravelTranslator\Translators\NLPCloudTranslator,
 * }
 *
 * @template T of key-of<Translators>
 *
 * @param  T  $translator
 *
 * @method static string translate(string $translator, string $source, string $target, string $text): string
 */
class LaravelTranslator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ferdiunal\LaravelTranslator\LaravelTranslator::class;
    }
}
