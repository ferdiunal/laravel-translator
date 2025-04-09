<?php

namespace Ferdiunal\LaravelTranslator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ferdiunal\LaravelTranslator\LaravelTranslator
 *
 * @param  'google'|'bing'|'deepl'|'myMemory'|'nlpCloud'|'openai'  $translator
 * @param  'az'|'de'|'en'|'es'|'it'|'pt'|'tr'|'ru'  $source
 * @param  'az'|'de'|'en'|'es'|'it'|'pt'|'tr'|'ru'  $target
 * @param  string  $text
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
