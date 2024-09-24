<?php

namespace Ferdiunal\LaravelTranslator\Commands;

use Illuminate\Console\Command;

class LaravelTranslatorCommand extends Command
{
    public $signature = 'laravel-translator';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
