<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Translators;

use Ferdiunal\LaravelTranslator\Exceptions\MissingCredentialException;
use OpenAI\Factory;
use RuntimeException;
use Throwable;

class OpenAITranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        if (! class_exists(Factory::class)) {
            throw new RuntimeException('OpenAI package not found. Please install it by running "composer require openai-php/client".');
        }

        $apiKey = config('translator.openai.api_key');

        if (! is_string($apiKey) || $apiKey === '') {
            throw MissingCredentialException::forProvider('OpenAI', 'OPENAI_API_KEY');
        }

        try {
            $baseUrl = $this->baseUrl();
            $client = \OpenAI::factory()->withApiKey($apiKey)->withBaseUri($baseUrl)->make();
            $response = $client->chat()->create([
                'model' => $this->model(),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->systemMessage(),
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->userMessage($source, $target, $text),
                    ],
                ],
            ]);

            $translated = $response->choices[0]->message->content ?? null;

            return is_string($translated) && $translated !== '' ? $translated : $text;
        } catch (Throwable $throwable) {
            report($throwable);

            if ((bool) config('translator.fallback.throw', false)) {
                throw $throwable;
            }
        }

        return $text;
    }

    public function icon(): string
    {
        return 'https://upload.wikimedia.org/wikipedia/commons/4/4d/OpenAI_Logo.svg';
    }

    public function key(): string
    {
        return 'openai';
    }

    public function title(): string
    {
        return 'OpenAI';
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

    private function baseUrl(): string
    {
        $baseUrl = config('translator.openai.base_url', 'https://api.openai.com/v1');

        return is_string($baseUrl) && filter_var($baseUrl, FILTER_VALIDATE_URL) ? $baseUrl : 'https://api.openai.com/v1';
    }

    private function model(): string
    {
        $model = config('translator.openai.model', 'gpt-4o-mini');

        return is_string($model) && $model !== '' ? $model : 'gpt-4o-mini';
    }

    private function systemMessage(): string
    {
        $message = config('translator.openai.system_message');

        return is_string($message) && $message !== ''
            ? $message
            : 'You are an experienced translator. Return only the translated output.';
    }

    private function userMessage(string $source, string $target, string $text): string
    {
        $pattern = config('translator.pattern', []);
        $pattern = is_array($pattern) ? $pattern : [];

        $sourcePattern = is_string($pattern['source'] ?? null) ? $pattern['source'] : '{source}';
        $targetPattern = is_string($pattern['target'] ?? null) ? $pattern['target'] : '{target}';
        $textPattern = is_string($pattern['text'] ?? null) ? $pattern['text'] : '{text}';

        $message = config('translator.openai.user_message', 'Translate the following text from {source} to {target}: {text}');
        $message = is_string($message) ? $message : 'Translate the following text from {source} to {target}: {text}';

        return str_replace(
            [$sourcePattern, $targetPattern, $textPattern],
            [mb_strtolower($source), mb_strtolower($target), $text],
            $message,
        );
    }
}
