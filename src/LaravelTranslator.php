<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator;

use Ferdiunal\LaravelTranslator\Manager\TranslatorManager;
use Ferdiunal\LaravelTranslator\Translators\Translator;

class LaravelTranslator
{
    /** Translate the given text using a registered translator key. */
    public static function translate(string $translator, string $source, string $target, string $text): string
    {
        return self::manager()->translate($translator, $source, $target, $text);
    }

    /** Resolve a registered translator instance. */
    public static function translator(string $translator): Translator
    {
        return self::manager()->translator($translator);
    }

    /**
     * Get enabled translator metadata keyed by canonical provider key.
     *
     * @return array<string, array{
     *     key: string,
     *     title: string,
     *     icon: string,
     *     driver: class-string<Translator>,
     *     enabled: bool,
     *     aliases: list<string>,
     *     config_key: string|null,
     *     default_base_url: string|null,
     *     max_text_length: int|null
     * }>
     */
    public static function providers(): array
    {
        return self::manager()->providers();
    }

    private static function manager(): TranslatorManager
    {
        return app(TranslatorManager::class);
    }
}
