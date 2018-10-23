<?php

namespace Tests\Unit;

use App\Dev;
use App\Services\SkypeMessageFormatter as Formatter;
use Tests\TestCase;

class SkypeMessageFormatterTest extends TestCase
{
    public function testBold()
    {
        $formatter = new Formatter();

        $this->assertEquals('**test**', $formatter->bold('test'));
    }

    public function testFreeDevStatus()
    {
        $formatter = new Formatter();
        $dev = factory(Dev::class)->make();

        $this->assertEquals("**{$dev->name}** – free", $formatter->devStatus($dev));
    }

    public function testOccupiedDevStatus()
    {
        $formatter = new Formatter();

        $dev = factory(Dev::class)->make([
            'name' => 'dev1',
            'owner_skype_username' => 'test_user',
            'expired_at' => now()->addHour(),
            'comment' => 'Test',
        ]);

        $this->assertEquals('**dev1** – test_user for 1h "Test"', $formatter->devStatus($dev));
    }

}
