<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Support;

final class PlaceholderPreserver
{
    /**
     * @param  callable(string): string  $translate
     */
    public function translate(string $text, callable $translate): string
    {
        /** @var array<string, string> $tokens */
        $tokens = [];
        $index = 0;

        $prepared = preg_replace_callback('/:\w+/', static function (array $matches) use (&$tokens, &$index): string {
            $placeholder = (string) $matches[0];
            $token = sprintf("\u{E000}laravel_translator_placeholder_%d\u{E001}", $index++);
            $tokens[$token] = $placeholder;

            return $token;
        }, $text);

        if ($prepared === null || $tokens === []) {
            return $translate($text);
        }

        return strtr($translate($prepared), $tokens);
    }
}
