<?php

namespace Tests\Feature\Console;

use App\Console\Commands\UnlockDev;
use App\Dev;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see UnlockDev
 */
class UnlockDevTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testBasicTest(): void
    {
        $data = ['name' => 'dev1'];

        $this->assertDatabaseMissing(Dev::TABLE, $data);
        $this->artisan('dev-bouncer:unlock', $data);

        factory(Dev::class)->create($data);
        $this->artisan('dev-bouncer:unlock', $data);
        $this->assertFalse(Dev::whereName('dev1')->first()->isReserved());
    }
}
