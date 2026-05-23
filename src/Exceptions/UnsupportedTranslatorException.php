<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Exceptions;

use InvalidArgumentException;

final class UnsupportedTranslatorException extends InvalidArgumentException
{
    public static function forKey(string $key): self
    {
        return new self(sprintf('Translator [%s] is not supported.', $key));
    }
}
