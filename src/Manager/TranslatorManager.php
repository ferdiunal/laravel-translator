<?php

declare(strict_types=1);

namespace Ferdiunal\LaravelTranslator\Manager;

use Closure;
use Ferdiunal\LaravelTranslator\DTO\ProviderDefinition;
use Ferdiunal\LaravelTranslator\Exceptions\InvalidTranslatorProviderException;
use Ferdiunal\LaravelTranslator\Exceptions\UnsupportedTranslatorException;
use Ferdiunal\LaravelTranslator\Registry\ProviderRegistry;
use Ferdiunal\LaravelTranslator\Translators\Translator;
use Illuminate\Contracts\Container\Container;

final class TranslatorManager
{
    /** @var array<string, Closure(Container): Translator> */
    private array $extensions = [];

    public function __construct(
        private readonly ProviderRegistry $registry,
        private readonly Container $container,
    ) {}

    public function translate(string $translator, string $source, string $target, string $text): string
    {
        return $this->translator($translator)->handle($source, $target, $text);
    }

    public function translator(string $translator): Translator
    {
        $definition = $this->registry->definition($translator);

        if (! $definition instanceof ProviderDefinition) {
            throw UnsupportedTranslatorException::forKey($translator);
        }

        if (isset($this->extensions[$definition->key])) {
            return ($this->extensions[$definition->key])($this->container);
        }

        if (! class_exists($definition->driver)) {
            throw InvalidTranslatorProviderException::missingClass($definition->key, $definition->driver);
        }

        if (! is_subclass_of($definition->driver, Translator::class)) {
            throw InvalidTranslatorProviderException::invalidContract($definition->key, $definition->driver);
        }

        $instance = $this->container->make($definition->driver);

        if (! $instance instanceof Translator) {
            throw InvalidTranslatorProviderException::invalidContract($definition->key, $definition->driver);
        }

        return $instance;
    }

    /**
     * @return array<string, array{
     *     key: string,
     *     title: string,
     *     icon: string,
     *     driver: class-string<Translator>,
     *     enabled: bool,
     *     aliases: list<string>,
     *     config_key: string|null,
     *     default_base_url: string|null,
     *     max_text_length: int|null
     * }>
     */
    public function providers(): array
    {
        return array_map(
            static fn (ProviderDefinition $definition): array => $definition->toArray(),
            $this->registry->definitions(),
        );
    }

    /**
     * @param  Closure(Container): Translator  $factory
     */
    public function extend(string $key, Closure $factory): void
    {
        $this->extensions[strtolower($key)] = $factory;
    }
}
