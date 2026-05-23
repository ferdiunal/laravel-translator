<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Translators;

use Ferdiunal\LaravelTranslator\Support\PlaceholderPreserver;

abstract class Translator
{
    /** Get the icon URL of the translator. */
    abstract public function icon(): string;

    /** Get the display title of the translator. */
    abstract public function title(): string;

    /** Get the canonical key of the translator. */
    abstract public function key(): string;

    /**
     * Get the translator metadata as an array.
     *
     * @return array{icon: string, key: string, title: string}
     */
    abstract public function toArray(): array;

    /** Translate the text. */
    abstract public function handle(string $source, string $target, string $text): string;

    /**
     * Translate while preserving Laravel-style placeholders such as :name.
     */
    public function run(string $source, string $target, string $text): string
    {
        return (new PlaceholderPreserver)->translate(
            $text,
            fn (string $value): string => $this->handle($source, $target, $value),
        );
    }
}
