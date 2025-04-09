<?php

namespace Ferdiunal\LaravelTranslator\Translators;

use Illuminate\Support\Facades\Http;

class MyMemoryTranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        $apiUrl = 'https://api.mymemory.translated.net/get';

        if (strlen($text) > 500) {
            return $text;
        }

        $query = [
            'q' => $text,
            'langpair' => sprintf('%s|%s', $source, $target),
            'mt' => '1',
        ];

        $response = Http::get($apiUrl, $query);

        $data = $response->json();

        $status = (int) data_get($data, 'responseStatus', 200);

        if ($status !== 200) {
            return $text;
        }

        return data_get($data, 'responseData.translatedText', $text);
    }

    public function icon(): string
    {
        return 'https://mymemory.translated.net/public/img/mym_logo_horizontal.svg';
    }

    public function key(): string
    {
        return 'mymemory';
    }

    public function title(): string
    {
        return 'MyMemory';
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
