<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Exceptions;

use RuntimeException;

final class MissingCredentialException extends RuntimeException
{
    public static function forProvider(string $provider, string $environmentKey): self
    {
        return new self(sprintf('The %s API key is not set. Please set %s in your environment.', $provider, $environmentKey));
    }
}
