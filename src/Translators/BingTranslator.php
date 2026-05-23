<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Translators;

use Ferdiunal\LaravelTranslator\Lib\BingTranslatorTool;

class BingTranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        return (new BingTranslatorTool)->translate(
            text: $text,
            target: $target,
            source: $source,
        );
    }

    public function icon(): string
    {
        return 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Microsoft_logo.svg/1024px-Microsoft_logo.svg.png';
    }

    public function key(): string
    {
        return 'bing';
    }

    public function title(): string
    {
        return 'Bing';
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
