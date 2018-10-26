<?php

namespace App\Console\Commands;

use App\Dev;
use Illuminate\Console\Command;

class CreateDev extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'dev-bouncer:create {name}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Add new dev';

    public function handle(): void
    {
        $name = $this->argument('name');

        if (Dev::whereName($name)->exists()) {
            $this->output->error("#$name already exists");

            return;
        }

        Dev::create(['name' => $name]);
        $this->output->success("#$name has been created");
    }
}
