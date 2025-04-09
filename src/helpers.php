<?php

use Ferdiunal\LaravelTranslator\Facades\LaravelTranslator;

if (! function_exists('translator')) {

    /**
     * Translate text using the specified translator.
     *
     * @see \Ferdiunal\LaravelTranslator\LaravelTranslator
     *
     * @param  'google'|'bing'|'deepl'|'myMemory'|'nlpCloud'|'openai'  $translator
     * @param  'az'|'de'|'en'|'es'|'it'|'pt'|'tr'|'ru'  $source
     * @param  'az'|'de'|'en'|'es'|'it'|'pt'|'tr'|'ru'  $target
     */
    function translator(string $translator, string $source, string $target, string $text): string
    {
        return LaravelTranslator::translate($translator, $source, $target, $text);
    }

}
