<?php

namespace App\Console\Commands;

use App\Exceptions\DevNotFoundException;
use App\Facades\DevBouncer;
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

        try {
            DevBouncer::unlockByName($name);
            $this->output->success("Dev {$name} has been unlocked");
        } catch (DevNotFoundException $e) {
            $this->output->error("Dev {$name} does not exist");
        }
    }
}
