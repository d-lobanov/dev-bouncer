<?php

namespace Tests\Feature\Console;

use App\Console\Commands\CreateDev;
use App\Dev;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see CreateDev
 */
class CreateDevTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testBasicTest(): void
    {
        $this->assertDatabaseMissing(Dev::TABLE, ['name' => 'dev1']);
        $this->artisan('dev-bouncer:create', ['name' => 'dev1']);
        $this->assertDatabaseHas(Dev::TABLE, ['name' => 'dev1']);

        $this->artisan('dev-bouncer:create', ['name' => 'dev1']);
        $this->assertDatabaseHas(Dev::TABLE, ['name' => 'dev1']);
    }
}
