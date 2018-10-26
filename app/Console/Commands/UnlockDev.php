<?php

namespace App\Console\Commands;

use App\Dev;
use Illuminate\Console\Command;

class UnlockDev extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'dev-bouncer:unlock {name}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Unlock dev';

    public function handle(): void
    {
        $name = $this->argument('name');

        if ($dev = Dev::whereName($name)->first()) {
            $dev->unlock();
            $this->output->success("#$name has been unlocked");

            return;
        }

        $this->output->error("#$name does not exist");
    }
}
