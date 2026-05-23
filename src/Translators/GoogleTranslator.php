<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Translators;

use RuntimeException;
use Stichoza\GoogleTranslate\GoogleTranslate;

class GoogleTranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        if (! class_exists(GoogleTranslate::class)) {
            throw new RuntimeException('Google Translate package not found. Please install it by running "composer require stichoza/google-translate-php"');
        }

        $translator = new GoogleTranslate(
            source: $source,
            target: $target,
        );

        $translated = $translator->translate($text);

        return is_string($translated) ? $translated : $text;
    }

    public function icon(): string
    {
        return 'https://upload.wikimedia.org/wikipedia/commons/d/db/Google_Translate_Icon.png';
    }

    public function key(): string
    {
        return 'google';
    }

    public function title(): string
    {
        return 'Google';
    }

    /** @return array{icon: string, key: string, title: string} */
    public function toArray(): array
    {
        return [
            'icon' => $this->icon(),
            'key' => $this->key(),
            'title' => $this->title(),
        ];
    }
}
