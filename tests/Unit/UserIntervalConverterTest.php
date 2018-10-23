<?php

namespace Tests\Unit;

use App\Services\UserIntervalParser;
use Carbon\Carbon;
use Tests\TestCase;

class UserIntervalConverterTest extends TestCase
{
    public function testEmptyInput()
    {
        $service = new UserIntervalParser();

        $this->assertNull($service->parse(''));
    }

    public function testInvalidInput()
    {
        $service = new UserIntervalParser();

        $this->assertNull($service->parse('test'));
        $this->assertNull($service->parse('2m 2s'));
    }

    public function testZeroValue()
    {
        $service = new UserIntervalParser();

        $this->assertNull($service->parse('0d'));
        $this->assertNull($service->parse('0h'));
        $this->assertNull($service->parse('0d 0h'));

        $this->assertNotNull($service->parse('0d 1h'));
    }

    public function testDaysLimit()
    {
        $service = new UserIntervalParser();

        $this->assertNull($service->parse('9d'));
        $this->assertNull($service->parse('10d 2h'));
        $this->assertNotNull($service->parse('8d'));
    }

    public function testHoursLimit()
    {
        $service = new UserIntervalParser();

        $this->assertNull($service->parse('24h'));
        $this->assertNull($service->parse('1d 24h'));
        $this->assertNotNull($service->parse('23h'));
    }

    public function testValidValue()
    {
        $service = new UserIntervalParser();

        $now = now();
        Carbon::setTestNow($now->copy());

        $expected = $now->copy()->addDays(2)->addHours(2)->timestamp;
        $this->assertEquals($expected, $service->parse('2d 2h'));
        $this->assertEquals($expected, $service->parse('2h 2d'));

        $expected = $now->copy()->addDays(8)->addHours(23)->timestamp;
        $this->assertEquals($expected, $service->parse('8d 23h'));

        $expected = $now->copy()->addDays(1)->timestamp;
        $this->assertEquals($expected, $service->parse('1d'));

        Carbon::setTestNow();
    }
}
