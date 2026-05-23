<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Lib;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Throwable;

class BingTranslatorTool
{
    private const TOKEN_CACHE_KEY = 'laravel-translator.bing.token';

    private string $apiAuth = 'https://edge.microsoft.com/translate/auth';

    private string $apiTranslate = 'https://api.cognitive.microsofttranslator.com/translate';

    private readonly Client $client;

    public function __construct(
        private readonly string $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299',
    ) {
        $this->client = new Client([
            'connect_timeout' => (int) config('translator.http.connect_timeout', 5),
            'headers' => [
                'User-Agent' => $this->userAgent,
            ],
            'timeout' => (int) config('translator.http.timeout', 10),
        ]);
    }

    public function translate(string $text, ?string $source = null, string $target = 'en'): string
    {
        try {
            $config = $this->getToken();
            $response = $this->client->post($this->apiTranslate, [
                'query' => [
                    'api-version' => '3.0',
                    'from' => $source,
                    'to' => $target,
                ],
                'json' => [['Text' => $text]],
                'headers' => [
                    'User-Agent' => $this->userAgent,
                    'Authorization' => $config['token'],
                ],
            ]);

            $translated = data_get(
                json_decode((string) $response->getBody(), true) ?: [],
                '0.translations.0.text',
            );

            return is_string($translated) && $translated !== '' ? $translated : $text;
        } catch (Throwable) {
            return $text;
        }
    }

    /**
     * @return array{token: string, tokenExpiresAt: int}
     *
     * @throws RequestException
     * @throws GuzzleException
     */
    private function getToken(): array
    {
        $cached = Cache::get(self::TOKEN_CACHE_KEY);

        if (is_array($cached)
            && is_string($cached['token'] ?? null)
            && is_int($cached['tokenExpiresAt'] ?? null)
            && $cached['tokenExpiresAt'] > ((int) floor(microtime(true) * 1000) + 60_000)
        ) {
            /** @var array{token: string, tokenExpiresAt: int} $cached */
            return $cached;
        }

        $response = $this->client->get($this->apiAuth);
        $authJWT = (string) $response->getBody();
        $payload = $this->decodeJwtPayload($authJWT);
        $tokenExpiresAt = (int) (($payload['exp'] ?? 0) * 1000);

        $token = [
            'token' => $authJWT,
            'tokenExpiresAt' => $tokenExpiresAt,
        ];

        $ttlSeconds = max(60, (int) floor(($tokenExpiresAt - ((int) floor(microtime(true) * 1000))) / 1000));
        Cache::put(self::TOKEN_CACHE_KEY, $token, $ttlSeconds);

        return $token;
    }

    /** @return array<string, int|string|float|bool|null> */
    private function decodeJwtPayload(string $jwt): array
    {
        $parts = explode('.', $jwt);

        if (! isset($parts[1])) {
            return [];
        }

        $payload = base64_decode(strtr($parts[1], '-_', '+/'), true);

        if (! is_string($payload)) {
            return [];
        }

        $decoded = json_decode($payload, true);

        return is_array($decoded) ? $decoded : [];
    }
}
