<?php

use Ferdiunal\LaravelTranslator\Facades\LaravelTranslator;

if (! function_exists('translator')) {

    /**
     * Translate text using the specified translator.
     *
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
     */
    function translator(string $translator, string $source, string $target, string $text): string
    {
        return LaravelTranslator::translate($translator, $source, $target, $text);
    }

}
