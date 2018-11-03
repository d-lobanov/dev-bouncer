<?php

namespace Tests\Feature\Console;

use App\Console\Commands\DeleteDev;
use App\Dev;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see DeleteDev
 */
class DeleteDevTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test(): void
    {
        $data = ['name' => 'dev1'];

        $this->assertDatabaseMissing(Dev::TABLE, $data);
        $this->artisan('dev-bouncer:delete', $data);
        $this->assertDatabaseMissing(Dev::TABLE, $data);

        factory(Dev::class)->create($data);
        $this->assertDatabaseHas(Dev::TABLE, $data);
        $this->artisan('dev-bouncer:delete', $data);
        $this->assertDatabaseMissing(Dev::TABLE, $data);
    }
}
