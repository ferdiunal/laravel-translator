<?php

namespace Ferdiunal\LaravelTranslator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ferdiunal\LaravelTranslator\LaravelTranslator
 *
 * @param 'google'|'bing'|'deepl'|'myMemory'|'nlpCloud' $translator
 * @method static string translate(string $translator, string $source, string $target, string $text): string
 */
class LaravelTranslator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ferdiunal\LaravelTranslator\LaravelTranslator::class;
    }
}
