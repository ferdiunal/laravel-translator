<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Translators;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class MyMemoryTranslator extends Translator
{
    private const API_URL = 'https://api.mymemory.translated.net/get';

    public function handle(string $source, string $target, string $text): string
    {
        if (mb_strlen($text) > 500) {
            return $text;
        }

        $response = $this->http()->get(self::API_URL, [
            'q' => $text,
            'langpair' => sprintf('%s|%s', $source, $target),
            'mt' => '1',
        ]);

        if (! $response->successful()) {
            return $text;
        }

        $data = $response->json();
        $status = (int) data_get($data, 'responseStatus', 200);

        if ($status !== 200) {
            return $text;
        }

        $translated = data_get($data, 'responseData.translatedText');

        return is_string($translated) && $translated !== '' ? $translated : $text;
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

    /** @return array{icon: string, key: string, title: string} */
    public function toArray(): array
    {
        return [
            'icon' => $this->icon(),
            'key' => $this->key(),
            'title' => $this->title(),
        ];
    }

    private function http(): PendingRequest
    {
        return Http::timeout((int) config('translator.http.timeout', 10))
            ->connectTimeout((int) config('translator.http.connect_timeout', 5))
            ->retry(
                (int) config('translator.http.retry_times', 1),
                (int) config('translator.http.retry_sleep_ms', 100),
            );
    }
}
