<?php

return [
    'deepl' => [
        'api_key' => env('DEEPL_API_KEY'),
    ],

    'nlpCloud' => [
        'api_key' => env('NLPCLOUD_API_KEY'),
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
];
