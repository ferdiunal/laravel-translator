<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Facades;

use Ferdiunal\LaravelTranslator\Manager\TranslatorManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see TranslatorManager
 *
 * @method static string translate(string $translator, string $source, string $target, string $text)
 * @method static \Ferdiunal\LaravelTranslator\Translators\Translator translator(string $translator)
 * @method static array providers()
 */
class LaravelTranslator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TranslatorManager::class;
    }
}
