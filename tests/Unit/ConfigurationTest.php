<?php

declare(strict_types=1);

it('does not ship a default secret-shaped OpenAI API key', function (): void {
    expect(config('translator.openai.api_key'))->toBeNull();
});
