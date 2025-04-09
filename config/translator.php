<?php

return [
    'deepl' => [
        'api_key' => env('DEEPL_API_KEY'),
    ],

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
        'api_key' => env('OPENAI_API_KEY', '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'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'system_message' => 'You are an experienced translator, professionally translate the incoming content into the desired language and just give the output.',
        // Important: patterns in user_message must match the patterns defined above ({source}, {target}, {text})
        'user_message' => 'Translate the following text from {source} to {target}: {text}',
    ],
];
