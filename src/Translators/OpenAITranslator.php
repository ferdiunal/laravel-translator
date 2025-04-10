<?php

namespace Ferdiunal\LaravelTranslator\Translators;

class OpenAITranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        if (! class_exists(\OpenAI\Factory::class)) {
            throw new \Exception('OpenAI package not found. Please install it by running "composer require openai/openai-php"');
        }

        $apiKey = config('translator.openai.api_key');

        if ($apiKey === null) {
            throw new \Exception('OpenAI API key not found. Please set the key in the environment variable OPENAI_API_KEY=xxxxxxx-...');
        }

        try {
            $client = \OpenAI::factory()->withApiKey($apiKey)->withBaseUri(config('translator.openai.base_url'))->make();

            $pattern = config('translator.pattern');
            $sourcePattern = $pattern['source'] ?? '{source}';
            $targetPattern = $pattern['target'] ?? '{target}';
            $textPattern = $pattern['text'] ?? '{text}';

            $response = $client->chat()->create([
                'model' => config('translator.openai.model'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => config('translator.openai.system_message'),
                    ],
                    [
                        'role' => 'user',
                        'content' => str_replace([$sourcePattern, $targetPattern, $textPattern], [mb_strtolower($source), mb_strtolower($target), $text], config('translator.openai.user_message')),
                    ],
                ],
            ]);

            return $response->choices[0]->message->content;
        } catch (\Throwable $e) {
            report($e);
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

    public function toArray(): array
    {
        return [
            'icon' => $this->icon(),
            'key' => $this->key(),
            'title' => $this->title(),
        ];
    }
}
