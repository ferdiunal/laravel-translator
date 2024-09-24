<?php

namespace Ferdiunal\LaravelTranslator;

use Ferdiunal\LaravelTranslator\Translators\Translator;

/**
 * @phpstan-type Translators array{
 *  google: \Ferdiunal\LaravelTranslator\Translators\GoogleTranslator,
 *  bing: \Ferdiunal\LaravelTranslator\Translators\BingTranslator,
 *  deepl: \Ferdiunal\LaravelTranslator\Translators\DeepLTranslator,
 *  myMemory: \Ferdiunal\LaravelTranslator\Translators\MyMemoryTranslator,
 *  nlpCloud: \Ferdiunal\LaravelTranslator\Translators\NLPCloudTranslator,
 * }
 */
class LaravelTranslator
{
    /**
     * Translate the given text.
     *
     * @template T of key-of<Translators>
     *
     * @param  T  $translator
     */
    public static function translate(string $translator, string $source, string $target, string $text): string
    {
        $translator = static::translator($translator);

        return $translator->handle(
            source: $source,
            target: $target,
            text: $text
        );
    }

    /**
     * Translate the given text.
     *
     * @template T of key-of<Translators>
     *
     * @param  T  $translator
     * @return Translators[T]
     */
    public static function translator(string $translator): Translator
    {
        /**
         * @var class-string<Translators[T]> $instance
         */
        $instance = str($translator)->ucfirst()->prepend('\Ferdiunal\LaravelTranslator\Translators\\')->append('Translator')->toString();
        if (! class_exists($instance)) {
            throw new \Exception(sprintf('The translator %s does not exist.', $instance));
        }

        /**
         * @var Translators[T] $instance
         */
        return new $instance;
    }
}
