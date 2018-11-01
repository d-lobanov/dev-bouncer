<?php

namespace Tests\BotMan;

use App\Dev;
use DevsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\ConsoleController::unlock
 */
class UnlockTest extends TestCase
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

    public function testDatabaseHasRecords()
    {
        $this->assertEquals(3, Dev::count(), 'Something went wrong with seeding database.');
    }

    /**
     * @depends testDatabaseHasRecords
     */
    public function testUnlock(): void
    {
        $this->reserveDev('john_doe', '111', 'dev1', 2, 'test');

        $this->bot
            ->setUser(['username' => 'john_doe', 'id' => '111'])
            ->receives('unlock dev1')
            ->assertReply('(dropthemic) #dev1 has been unlocked');

        $this->assertDatabaseHas(Dev::TABLE, [
            'name' => 'dev1',
            'owner_skype_id' => null,
            'owner_skype_username' => null,
            'expired_at' => null,
            'notified_at' => null,
            'comment' => null,
        ]);
    }

    /**
     * @depends testDatabaseHasRecords
     */
    public function testUnlockIfDevIsFree()
    {
        $this->bot
            ->setUser(['username' => 'john_doe', 'id' => '111'])
            ->receives('unlock dev1')
            ->assertReply('#dev1 have already been unlocked');
    }

    /**
     * @depends testDatabaseHasRecords
     */
    public function testUnlockIfDevNotExists()
    {
        $this->bot
            ->setUser(['username' => 'john_doe', 'id' => '111'])
            ->receives('unlock dev4')
            ->assertReply('#dev4 doesn\'t exist');
    }

    /**
     * @depends testDatabaseHasRecords
     */
    public function testUnlockIfDevBelongsToAnotherUser()
    {
        $this->reserveDev('john_doe', '111', 'dev1', 2, 'test');

        $this->bot
            ->setUser(['username' => 'hacker_x', 'id' => '333'])
            ->receives('unlock dev1')
            ->assertReply('#dev1 doesn\'t belong to current user');
    }
}
