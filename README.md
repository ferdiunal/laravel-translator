<img src="./art/logo.png" alt="Laravel Translator" />

# Laravel Translator

English | [Türkçe](README.TR.md)

A type-safe and extensible translation package for Laravel applications with multiple provider support.

Supported providers:

- Google Translate
- Bing Translator
- DeepL
- MyMemory
- NLP Cloud
- OpenAI / OpenAI-compatible custom base URLs

## Requirements

- PHP `^8.2` — Laravel 13 combinations require PHP `^8.3` through Laravel's framework constraints.
- Laravel 10, 11, 12, or 13.

## Installation

```bash
composer require ferdiunal/laravel-translator
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Ferdiunal\LaravelTranslator\LaravelTranslatorServiceProvider"
```

## Basic usage

```php
use Ferdiunal\LaravelTranslator\LaravelTranslator;

$translated = LaravelTranslator::translate(
    translator: 'google',
    source: 'en',
    target: 'tr',
    text: 'Hello World',
);
```

Facade:

```php
use Ferdiunal\LaravelTranslator\Facades\LaravelTranslator;

$translated = LaravelTranslator::translate('openai', 'en', 'tr', 'Hello World');
```

Helper:

```php
$translated = translator('deepl', 'en', 'tr', 'Hello World');
```

Resolving a provider instance:

```php
$provider = LaravelTranslator::translator('mymemory');
$translated = $provider->run('en', 'tr', 'Hello :name');
```

`run()` preserves Laravel placeholders. Placeholders such as `:name` and `:count` are not translated, and characters such as `@` inside email addresses are not corrupted.

## Provider keys and aliases

Canonical provider keys:

| Provider | Canonical key | Legacy/compatible alias |
| --- | --- | --- |
| Google | `google` | - |
| Bing | `bing` | - |
| DeepL | `deepl` | - |
| MyMemory | `mymemory` | `myMemory` |
| NLP Cloud | `nlpcloud` | `nlpCloud` |
| OpenAI | `openai` | - |

Acronym and case differences are no longer resolved through runtime class-name guessing. Provider resolution uses an explicit registry, so names such as `OpenAI`, `DeepL`, `NLPCloud`, and `MyMemory` are not fragile on Linux or PSR-4 case-sensitive environments.

Get the list of active providers:

```php
$providers = LaravelTranslator::providers();
```

## Configuration

The published `config/translator.php` file contains these fields in summary:

```php
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
        // Example for disabling a built-in provider:
        // 'openai' => ['enabled' => false],
    ],

    'deepl' => [
        'api_key' => env('DEEPL_API_KEY'),
    ],

    'nlpcloud' => [
        'api_key' => env('NLPCLOUD_API_KEY'),
        'model' => env('NLPCLOUD_MODEL', 'nllb-200-3-3b'),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
    ],
];
```

The package does not ship a default secret or API key. API keys must be provided through `.env`.

```env
DEEPL_API_KEY=your-deepl-api-key
NLPCLOUD_API_KEY=your-nlpcloud-api-key
NLPCLOUD_MODEL=nllb-200-3-3b
OPENAI_API_KEY=your-openai-api-key
OPENAI_BASE_URL=https://api.openai.com/v1
OPENAI_MODEL=gpt-4o-mini
```

## Adding a custom provider

Custom provider classes must extend `Ferdiunal\LaravelTranslator\Translators\Translator`.

```php
<?php

declare(strict_types=1);

namespace App\Translators;

use Ferdiunal\LaravelTranslator\Translators\Translator;

final class AcmeTranslator extends Translator
{
    public function handle(string $source, string $target, string $text): string
    {
        return "[$source>$target] $text";
    }

    public function icon(): string
    {
        return 'https://example.com/icon.svg';
    }

    public function key(): string
    {
        return 'acme';
    }

    public function title(): string
    {
        return 'Acme';
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
}
```

`config/translator.php`:

```php
'providers' => [
    'acme' => [
        'driver' => App\Translators\AcmeTranslator::class,
        'enabled' => true,
        'title' => 'Acme',
        'icon' => 'https://example.com/icon.svg',
        'aliases' => ['legacyAcme'],
    ],
],
```

Usage:

```php
$translated = LaravelTranslator::translate('acme', 'en', 'tr', 'Hello');
```

## Disabling or overriding providers

Disable a built-in provider:

```php
'providers' => [
    'openai' => [
        'enabled' => false,
    ],
],
```

Override built-in provider metadata or driver:

```php
'providers' => [
    'openai' => [
        'driver' => App\Translators\CustomOpenAITranslator::class,
        'title' => 'Company OpenAI Proxy',
        'default_base_url' => 'https://ai.example.com/v1',
        'enabled' => true,
    ],
],
```

Invalid custom provider classes throw `InvalidTranslatorProviderException`; unknown or disabled providers throw `UnsupportedTranslatorException`.

## Using OpenAI-compatible endpoints

The OpenAI provider supports OpenAI-compatible endpoints through `OPENAI_BASE_URL`:

```env
OPENAI_API_KEY=your-api-key
OPENAI_BASE_URL=https://openrouter.ai/api/v1
OPENAI_MODEL=openai/gpt-4o-mini
```

## Tests and quality gates

```bash
composer validate --strict
composer format:check
composer analyse
composer test -- --ci
```

Single command:

```bash
composer ci
```

The CI matrix is designed to cover PHP 8.2/8.3/8.4/8.5 and Laravel 10/11/12/13 combinations.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
