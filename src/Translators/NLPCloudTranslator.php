<?php

namespace Ferdiunal\LaravelTranslator\Translators;

use Error;
use Exception;
use RuntimeException;

class NLPCloudTranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        if (class_exists(\NLPCloud\NLPCloud::class) === false) {
            throw new RuntimeException(
                'The package nlpcloud/nlpcloud-client is not installed. Please run `composer require nlpcloud/nlpcloud-client`',
            );
        }

        $authKey = config('translator.nlpCloud.api_key');
        $sourceLang = config("translator.nlpCloud.languages.{$source}");
        $targetLang = config("translator.nlpCloud.languages.{$target}");

        if ($authKey === null) {
            throw new RuntimeException(
                'The NLPCLoud API key is not set. Please set the key in the environment variable NLPCLOUD_API_KEY=xxxxxxx...',
            );
        }

        try {
            $translator = new \NLPCloud\NLPCloud(config('translator.nlpCloud.model'), $authKey, false);
            $translate = $translator->translation(
                $text,
                $sourceLang,
                $targetLang
            );

            return data_get($translate, 'translation_text');
        } catch (Exception|Error|RuntimeException $e) {
            ray($e);

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

    public function toArray(): array
    {
        return [
            'icon' => $this->icon(),
            'key' => $this->key(),
            'title' => $this->title(),
        ];
    }
}
