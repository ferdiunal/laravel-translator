<?php

declare(strict_types=1);

return [
    'fallback' => [
        'throw' => env('TRANSLATOR_THROW_ON_FAILURE', false),
    ],

    'http' => [
        'timeout' => (int) env('TRANSLATOR_HTTP_TIMEOUT', 10),
        'connect_timeout' => (int) env('TRANSLATOR_HTTP_CONNECT_TIMEOUT', 5),
        'retry_times' => (int) env('TRANSLATOR_HTTP_RETRY_TIMES', 1),
        'retry_sleep_ms' => (int) env('TRANSLATOR_HTTP_RETRY_SLEEP_MS', 100),
    ],

    'providers' => [
        // Host apps may register custom translators here:
        // 'custom' => [
        //     'driver' => App\Translators\CustomTranslator::class,
        //     'enabled' => true,
        //     'title' => 'Custom Provider',
        //     'icon' => null,
        // ],
        // Built-in providers can also be disabled by key:
        // 'openai' => ['enabled' => false],
    ],

    'deepl' => [
        'api_key' => env('DEEPL_API_KEY'),
    ],

    'nlpcloud' => [
        'api_key' => env('NLPCLOUD_API_KEY'),
        'model' => env('NLPCLOUD_MODEL', 'nllb-200-3-3b'),
        'languages' => [
            'az' => 'azj_Latn',
            'de' => 'deu_Latn',
            'en' => 'eng_Latn',
            'es' => 'spa_Latn',
            'it' => 'ita_Latn',
            'pt' => 'por_Latn',
            'tr' => 'tur_Latn',
            'ru' => 'rus_Cyrl',
        ],
    ],

    // Backward-compatible key for apps that already published the previous config.
    'nlpCloud' => [
        'api_key' => env('NLPCLOUD_API_KEY'),
        'model' => env('NLPCLOUD_MODEL', 'nllb-200-3-3b'),
        'languages' => [
            'az' => 'azj_Latn',
            'de' => 'deu_Latn',
            'en' => 'eng_Latn',
            'es' => 'spa_Latn',
            'it' => 'ita_Latn',
            'pt' => 'por_Latn',
            'tr' => 'tur_Latn',
            'ru' => 'rus_Cyrl',
        ],
    ],

    'pattern' => [
        'source' => '{source}',
        'target' => '{target}',
        'text' => '{text}',
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'system_message' => 'You are an experienced translator, professionally translate the incoming content into the desired language and just give the output.',
        // Important: patterns in user_message must match the patterns defined above ({source}, {target}, {text})
        'user_message' => 'Translate the following text from {source} to {target}: {text}',
    ],
];
