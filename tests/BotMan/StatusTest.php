<?php

namespace Tests\BotMan;

use App\Dev;
use App\Http\Controllers\ConversationsController;
use DevsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see ConversationsController::status()
 */
class StatusTest extends TestCase
{
    use RefreshDatabase;
    use ReserveDev;

    /**
     * Create test data in database.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->seed(DevsSeeder::class);
    }

    public function testDatabaseHasRecords(): void
    {
        $this->assertEquals(3, Dev::count(), 'Something went wrong with seeding database.');
    }

    /**
     * @depends testDatabaseHasRecords
     */
    public function testInitialStatus(): void
    {
        $this->bot
            ->receives('status')
            ->assertReply("**dev1** – free\n\n**dev2** – free\n\n**dev3** – free");
    }

    /**
     * @depends testInitialStatus
     */
    public function testStatusIfReserved(): void
    {
        $this->reserveDev('john_doe', '111', 'dev1', 2, 'my super test');

        $this->bot
            ->receives('status')
            ->assertReply("**dev1** – john_doe for 2h \"my super test\"\n\n**dev2** – free\n\n**dev3** – free");

        $this->bot->receives('unlock dev1');

        $this->bot
            ->receives('status')
            ->assertReply("**dev2** – free\n\n**dev3** – free");
    }

    public function testUnlockAllDevs(): void
    {
        $this->reserveDev('john_doe', '111', 'dev1', 1, '');
        $this->reserveDev('john_doe', '111', 'dev2', 1, '');
        $this->reserveDev('john_doe', '111', 'dev3', 1, '');

        $this->bot->receives('unlock dev1');
        $this->bot->receives('unlock dev2');
        $this->bot->receives('unlock dev3');

        $this->bot->receives('status')->assertReply('No devs reserved');
    }
}
