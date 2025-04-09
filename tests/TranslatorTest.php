<?php

use Ferdiunal\LaravelTranslator\LaravelTranslator;

it('can translate text', function () {
    $text = 'Hello World';

    $translator = new LaravelTranslator;

    expect($translator->translate('google', 'en', 'tr', $text))->toMatch('/^Selam Dünya/');

    expect($translator->translate('bing', 'en', 'tr', $text))->toMatch('/^Merhaba Dünya/');

    expect($translator->translate('myMemory', 'en', 'tr', $text))->toMatch('/^Merhaba Dünya/');

    expect($translator->translate('deepl', 'en', 'tr', $text))->toMatch('/^Merhaba Dünya/');

    expect($translator->translate('nlpCloud', 'en', 'tr', $text))->toMatch('/^Merhaba Dünya/');

    expect($translator->translate('openai', 'en', 'tr', $text))->toMatch('/^Merhaba Dünya/');
});
