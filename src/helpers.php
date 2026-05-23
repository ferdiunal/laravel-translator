<?php

declare(strict_types=1);

use Ferdiunal\LaravelTranslator\Facades\LaravelTranslator;

if (! function_exists('translator')) {
    /**
     * Translate text using the specified translator.
     *
     * @param  'google'|'bing'|'deepl'|'mymemory'|'myMemory'|'nlpcloud'|'nlpCloud'|'openai'|string  $translator
     */
    function translator(string $translator, string $source, string $target, string $text): string
    {
        return LaravelTranslator::translate($translator, $source, $target, $text);
    }
}
