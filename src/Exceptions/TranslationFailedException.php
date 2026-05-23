<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Exceptions;

use RuntimeException;
use Throwable;

final class TranslationFailedException extends RuntimeException
{
    public static function forProvider(string $provider, Throwable $previous): self
    {
        return new self(sprintf('Translator provider [%s] failed.', $provider), previous: $previous);
    }
}
