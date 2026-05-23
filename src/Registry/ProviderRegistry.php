<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Registry;

use Ferdiunal\LaravelTranslator\DTO\ProviderDefinition;
use Ferdiunal\LaravelTranslator\Translators\BingTranslator;
use Ferdiunal\LaravelTranslator\Translators\DeepLTranslator;
use Ferdiunal\LaravelTranslator\Translators\GoogleTranslator;
use Ferdiunal\LaravelTranslator\Translators\MyMemoryTranslator;
use Ferdiunal\LaravelTranslator\Translators\NLPCloudTranslator;
use Ferdiunal\LaravelTranslator\Translators\OpenAITranslator;
use Ferdiunal\LaravelTranslator\Translators\Translator;

final class ProviderRegistry
{
    /**
     * @return array<string, ProviderDefinition>
     */
    public function definitions(): array
    {
        return array_filter(
            $this->allDefinitions(),
            static fn (ProviderDefinition $definition): bool => $definition->enabled,
        );
    }

    public function definition(string $key): ?ProviderDefinition
    {
        $normalized = $this->normalizeKey($key);

        return $this->definitions()[$normalized] ?? null;
    }

    /**
     * @return array<string, ProviderDefinition>
     */
    private function allDefinitions(): array
    {
        $definitions = $this->builtInDefinitions();

        foreach ($this->configuredProviders() as $key => $configuration) {
            if (! is_array($configuration)) {
                continue;
            }

            $normalized = $this->normalizeKey((string) $key);
            $base = $definitions[$normalized] ?? null;
            $definitions[$normalized] = $this->definitionFromConfiguration($normalized, $configuration, $base);
        }

        return $definitions;
    }

    /**
     * @return array<string, ProviderDefinition>
     */
    private function builtInDefinitions(): array
    {
        return [
            'google' => new ProviderDefinition(
                key: 'google',
                title: 'Google',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/d/db/Google_Translate_Icon.png',
                driver: GoogleTranslator::class,
                configKey: 'google',
            ),
            'bing' => new ProviderDefinition(
                key: 'bing',
                title: 'Bing',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Microsoft_logo.svg/1024px-Microsoft_logo.svg.png',
                driver: BingTranslator::class,
                configKey: 'bing',
                defaultBaseUrl: 'https://api.cognitive.microsofttranslator.com/translate',
            ),
            'deepl' => new ProviderDefinition(
                key: 'deepl',
                title: 'DeepL',
                icon: 'https://cdn.worldvectorlogo.com/logos/deepl-1.svg',
                driver: DeepLTranslator::class,
                configKey: 'deepl',
            ),
            'mymemory' => new ProviderDefinition(
                key: 'mymemory',
                title: 'MyMemory',
                icon: 'https://mymemory.translated.net/public/img/mym_logo_horizontal.svg',
                driver: MyMemoryTranslator::class,
                aliases: ['myMemory'],
                configKey: 'mymemory',
                defaultBaseUrl: 'https://api.mymemory.translated.net/get',
                maxTextLength: 500,
            ),
            'nlpcloud' => new ProviderDefinition(
                key: 'nlpcloud',
                title: 'NLP Cloud',
                icon: 'https://nlpcloud.com/assets/images/logo.svg',
                driver: NLPCloudTranslator::class,
                aliases: ['nlpCloud'],
                configKey: 'nlpcloud',
            ),
            'openai' => new ProviderDefinition(
                key: 'openai',
                title: 'OpenAI',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/4/4d/OpenAI_Logo.svg',
                driver: OpenAITranslator::class,
                configKey: 'openai',
                defaultBaseUrl: 'https://api.openai.com/v1',
            ),
        ];
    }

    /**
     * @param  array<string, mixed>  $configuration
     */
    private function definitionFromConfiguration(string $key, array $configuration, ?ProviderDefinition $base): ProviderDefinition
    {
        $baseDriver = $base instanceof ProviderDefinition ? $base->driver : null;
        $baseAliases = $base instanceof ProviderDefinition ? $base->aliases : [];
        $baseTitle = $base instanceof ProviderDefinition ? $base->title : ucfirst($key);
        $baseIcon = $base instanceof ProviderDefinition ? $base->icon : '';
        $baseEnabled = ! ($base instanceof ProviderDefinition) || $base->enabled;
        $baseConfigKey = $base instanceof ProviderDefinition ? $base->configKey : $key;
        $baseDefaultBaseUrl = $base instanceof ProviderDefinition ? $base->defaultBaseUrl : null;
        $baseMaxTextLength = $base instanceof ProviderDefinition ? $base->maxTextLength : null;

        $driver = $configuration['driver'] ?? $baseDriver;

        /** @var class-string<Translator> $driver */
        $driver = is_string($driver) && $driver !== '' ? $driver : Translator::class;

        $aliases = $configuration['aliases'] ?? $baseAliases;
        $aliases = is_array($aliases) ? array_values(array_filter($aliases, 'is_string')) : [];

        return new ProviderDefinition(
            key: $key,
            title: $this->stringConfig($configuration, 'title', $baseTitle),
            icon: $this->stringConfig($configuration, 'icon', $baseIcon),
            driver: $driver,
            enabled: (bool) ($configuration['enabled'] ?? $baseEnabled),
            aliases: $aliases,
            configKey: $this->nullableStringConfig($configuration, 'config_key', $baseConfigKey),
            defaultBaseUrl: $this->nullableStringConfig($configuration, 'default_base_url', $baseDefaultBaseUrl),
            maxTextLength: $this->nullableIntConfig($configuration, 'max_text_length', $baseMaxTextLength),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function configuredProviders(): array
    {
        $providers = config('translator.providers', []);

        return is_array($providers) ? $providers : [];
    }

    private function normalizeKey(string $key): string
    {
        foreach ($this->builtInDefinitions() as $canonical => $definition) {
            if ($key === $canonical || in_array($key, $definition->aliases, true)) {
                return $canonical;
            }
        }

        return strtolower($key);
    }

    /** @param array<string, mixed> $configuration */
    private function stringConfig(array $configuration, string $key, string $default): string
    {
        $value = $configuration[$key] ?? $default;

        return is_string($value) ? $value : $default;
    }

    /** @param array<string, mixed> $configuration */
    private function nullableStringConfig(array $configuration, string $key, ?string $default): ?string
    {
        $value = $configuration[$key] ?? $default;

        return is_string($value) && $value !== '' ? $value : $default;
    }

    /** @param array<string, mixed> $configuration */
    private function nullableIntConfig(array $configuration, string $key, ?int $default): ?int
    {
        $value = $configuration[$key] ?? $default;

        return is_int($value) ? $value : $default;
    }
}
