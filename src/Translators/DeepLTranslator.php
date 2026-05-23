<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Translators;

use DeepL\Translator as DeepLClient;
use Ferdiunal\LaravelTranslator\Exceptions\MissingCredentialException;
use Throwable;

class DeepLTranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        if (! class_exists(DeepLClient::class)) {
            throw new MissingCredentialException('The package deeplcom/deepl-php is not installed. Please run `composer require deeplcom/deepl-php`.');
        }

        $authKey = config('translator.deepl.api_key');

        if (! is_string($authKey) || $authKey === '') {
            throw MissingCredentialException::forProvider('DeepL', 'DEEPL_API_KEY');
        }

        try {
            $translator = new DeepLClient($authKey);
            $translation = $translator->translateText($text, $source, $target);

            return $translation->text;
        } catch (Throwable $throwable) {
            report($throwable);

            if ((bool) config('translator.fallback.throw', false)) {
                throw $throwable;
            }

            return $text;
        }
    }

    public function icon(): string
    {
        return 'https://cdn.worldvectorlogo.com/logos/deepl-1.svg';
    }

    public function key(): string
    {
        return 'deepl';
    }

    public function title(): string
    {
        return 'DeepL';
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
