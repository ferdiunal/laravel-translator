<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Tests\Fixtures;

use Ferdiunal\LaravelTranslator\Translators\Translator;

final class EchoTranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        return sprintf('[%s>%s] %s', $source, $target, $text);
    }

    public function icon(): string
    {
        return 'echo.svg';
    }

    public function title(): string
    {
        return 'Echo';
    }

    public function key(): string
    {
        return 'echo';
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
