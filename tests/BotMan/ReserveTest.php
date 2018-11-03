<?php

namespace Tests\BotMan;

use App\Dev;
use Carbon\Carbon;
use DevsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReserveTest extends TestCase
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
    public function testReserveWithoutComment(): void
    {
        $now = now();
        Carbon::setTestNow($now->copy());

        $this->bot
            ->setUser(['username' => 'john_doe', 'id' => '111'])
            ->receives('reserve dev1 2h')
            ->assertReply('(key) #dev1 has been reserved');

        $this->assertDatabaseHas(Dev::TABLE, [
            'name' => 'dev1',
            'owner_skype_id' => '111',
            'owner_skype_username' => 'john_doe',
            'expired_at' => $now->copy()->addHours(2),
            'notified_at' => $now,
            'comment' => '',
        ]);

        Carbon::setTestNow();
    }

    /**
     * @depends testReserveWithoutComment
     */
    public function testReserveWithComment(): void
    {
        $this->reserveDev('john_doe', '111', 'dev1', 2, 'test');
    }

    /**
     * @depends testReserveWithComment
     */
    public function testReserveDevIfAlreadyReserved(): void
    {
        $this->reserveDev('john_doe', '111', 'dev1', 2, 'test_1');

        $this->bot
            ->setUser(['username' => 'john_doe', 'id' => '111'])
            ->receives('reserve dev1 2h')
            ->assertReply('#dev1 have already been reserved');

        $this->bot
            ->setUser(['username' => 'hacker_x', 'id' => '333'])
            ->receives('reserve dev1 2h')
            ->assertReply('#dev1 have already been reserved');
    }

    /**
     * @depends testReserveWithComment
     */
    public function testReserveMultipleDevs(): void
    {
        $this->reserveDev('john_doe', '111', 'dev1', 1, 'test_1');
        $this->reserveDev('john_doe', '111', 'dev2', 2, 'test_2');
        $this->reserveDev('hacker_x', '333', 'dev3', 3, 'test_3');

        $this->bot
            ->setUser(['username' => 'hacker_x', 'id' => '333'])
            ->receives('reserve dev1 2h')
            ->assertReply('#dev1 have already been reserved');
    }

    /**
     * @depends testReserveWithComment
     */
    public function testReserveDevIfNotExistD(): void
    {
        $this->bot
            ->receives('reserve dev777 1h')
            ->assertReply('#dev777 doesn\'t exist');
    }
}
