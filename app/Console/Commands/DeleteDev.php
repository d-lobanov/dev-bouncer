<?php

namespace App\Console\Commands;

use App\Dev;
use Illuminate\Console\Command;

class DeleteDev extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'dev-bouncer:delete {name}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Remove dev';

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $name = $this->argument('name');

        if ($dev = Dev::whereName($name)->first()) {
            $dev->delete();
            $this->output->success("Dev {$name} has been deleted");

            return;
        }

        $this->output->error("Dev {$name} does not exist");
    }
}
