<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\DTO;

use Ferdiunal\LaravelTranslator\Translators\Translator;

/**
 * Immutable metadata for a translator provider.
 *
 * @phpstan-type ProviderArray array{
 *     key: string,
 *     title: string,
 *     icon: string,
 *     driver: class-string<Translator>,
 *     enabled: bool,
 *     aliases: list<string>,
 *     config_key: string|null,
 *     default_base_url: string|null,
 *     max_text_length: int|null
 * }
 */
final readonly class ProviderDefinition
{
    /**
     * @param  class-string<Translator>  $driver
     * @param  list<string>  $aliases
     */
    public function __construct(
        public string $key,
        public string $title,
        public string $icon,
        public string $driver,
        public bool $enabled = true,
        public array $aliases = [],
        public ?string $configKey = null,
        public ?string $defaultBaseUrl = null,
        public ?int $maxTextLength = null,
    ) {}

    /** @return ProviderArray */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'title' => $this->title,
            'icon' => $this->icon,
            'driver' => $this->driver,
            'enabled' => $this->enabled,
            'aliases' => $this->aliases,
            'config_key' => $this->configKey,
            'default_base_url' => $this->defaultBaseUrl,
            'max_text_length' => $this->maxTextLength,
        ];
    }
}
