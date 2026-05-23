<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Translators;

use Ferdiunal\LaravelTranslator\Exceptions\MissingCredentialException;
use NLPCloud\NLPCloud;
use RuntimeException;
use Throwable;

class NLPCloudTranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        if (! class_exists(NLPCloud::class)) {
            throw new RuntimeException('The package nlpcloud/nlpcloud-client is not installed. Please run `composer require nlpcloud/nlpcloud-client`.');
        }

        $authKey = $this->configString('api_key') ?? $this->legacyConfigString('api_key');
        $sourceLang = $this->languageFor($source);
        $targetLang = $this->languageFor($target);

        if ($authKey === null) {
            throw MissingCredentialException::forProvider('NLPCloud', 'NLPCLOUD_API_KEY');
        }

        if ($sourceLang === null || $targetLang === null) {
            return $text;
        }

        try {
            $model = $this->configString('model') ?? $this->legacyConfigString('model') ?? 'nllb-200-3-3b';
            $translator = new NLPCloud($model, $authKey, false);
            $translation = $translator->translation($text, $sourceLang, $targetLang);
            $translated = data_get($translation, 'translation_text');

            return is_string($translated) && $translated !== '' ? $translated : $text;
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
        return 'https://nlpcloud.com/assets/images/logo.svg';
    }

    public function key(): string
    {
        return 'nlpcloud';
    }

    public function title(): string
    {
        return 'NLP Cloud';
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

    private function languageFor(string $language): ?string
    {
        $value = config("translator.nlpcloud.languages.{$language}") ?? config("translator.nlpCloud.languages.{$language}");

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function configString(string $key): ?string
    {
        $value = config("translator.nlpcloud.{$key}");

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function legacyConfigString(string $key): ?string
    {
        $value = config("translator.nlpCloud.{$key}");

        return is_string($value) && $value !== '' ? $value : null;
    }
}
