<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Exceptions;

use Ferdiunal\LaravelTranslator\Translators\Translator;
use InvalidArgumentException;

final class InvalidTranslatorProviderException extends InvalidArgumentException
{
    public static function missingClass(string $key, string $driver): self
    {
        return new self(sprintf('Translator provider [%s] driver [%s] does not exist.', $key, $driver));
    }

    public static function invalidContract(string $key, string $driver): self
    {
        return new self(sprintf('Translator provider [%s] must extend %s; [%s] given.', $key, Translator::class, $driver));
    }
}
