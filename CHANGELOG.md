# Changelog

All notable changes to `laravel-translator` will be documented in this file.

## Unreleased

- Added explicit provider registry and manager for deterministic provider resolution.
- Added canonical provider keys with backward-compatible aliases for `myMemory` and `nlpCloud`.
- Added custom provider registration, disable, override, and contract validation support.
- Removed default secret-shaped OpenAI API key from package config.
- Added typed exceptions for unsupported providers, invalid providers, and missing credentials.
- Added strict types across package source and tests.
- Hardened placeholder preservation for Laravel placeholders like `:name` and `:count`.
- Replaced external-service-dependent tests with deterministic provider registry and fake-provider tests.
- Added PHP 8.4/8.5 and Laravel 12 CI coverage.
- Updated README with current API, provider keys, custom provider examples, and quality gates.

## v1.0.0 - 2024-09-24

**Full Changelog**: https://github.com/ferdiunal/laravel-translator/commits/v1.0.0
