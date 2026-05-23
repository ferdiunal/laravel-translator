<?php

declare(strict_types=1);

use Ferdiunal\LaravelTranslator\Translators\Translator;

it('preserves placeholders without corrupting at signs or repeated placeholders', function (): void {
    $translator = new class extends Translator
    {
        public function handle(string $source, string $target, string $text): string
        {
            return str_replace('Hello', 'Merhaba', $text);
        }

        public function icon(): string
        {
            return 'icon.svg';
        }

        public function title(): string
        {
            return 'Fake';
        }

        public function key(): string
        {
            return 'fake';
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
    };

    expect($translator->run('en', 'tr', 'Hello :name, mail foo@bar.com, again :name and :count.'))
        ->toBe('Merhaba :name, mail foo@bar.com, again :name and :count.');
});
