<?php

use Ferdiunal\LaravelTranslator\Facades\LaravelTranslator;

if (! function_exists('translator')) {

    /**
     * Translate text using the specified translator.
     *
     * @see \Ferdiunal\LaravelTranslator\LaravelTranslator
     *
     * @param  'google'|'bing'|'deepl'|'myMemory'|'nlpCloud'  $translator
     */
    function translator(string $translator, string $source, string $target, string $text): string
    {
        return LaravelTranslator::translate($translator, $source, $target, $text);
    }

}
