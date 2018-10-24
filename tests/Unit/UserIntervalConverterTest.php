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

    public function testParseWithInvalidInput()
    {
        $service = new UserIntervalParser();

        $this->assertNull($service->parse('test'));
        $this->assertNull($service->parse('2m 2s'));
    }

    public function testParseWithZeroValue()
    {
        $service = new UserIntervalParser();

        $this->assertNull($service->parse('0d'));
        $this->assertNull($service->parse('0h'));
        $this->assertNull($service->parse('0d 0h'));

        $this->assertNotNull($service->parse('0d 1h'));
    }

    public function testParseWithDaysLimit()
    {
        $service = new UserIntervalParser();

        $this->assertNull($service->parse('3d'));
        $this->assertNull($service->parse('3d 2h'));
        $this->assertNotNull($service->parse('2d'));
    }

    public function testParseWithHoursLimit()
    {
        $service = new UserIntervalParser();

        $this->assertNotNull($service->parse('23h'));
        $this->assertNotNull($service->parse('30h'));

        $this->assertNull($service->parse('50h'));
    }

    public function testParseWithValidValue()
    {
        $service = new UserIntervalParser();

        $now = now();
        Carbon::setTestNow($now->copy());

        $expected = $now->copy()->addDays(1)->addHours(2)->timestamp;
        $this->assertEquals($expected, $service->parse('1d 2h'));
        $this->assertEquals($expected, $service->parse('2h 1d'));

        $expected = $now->copy()->addDays(0)->addHours(23)->timestamp;
        $this->assertEquals($expected, $service->parse('0d 23h'));

        $expected = $now->copy()->addDays(1)->timestamp;
        $this->assertEquals($expected, $service->parse('1d'));

        $expected = $now->copy()->addHours(1)->timestamp;
        $this->assertEquals($expected, $service->parse('1h'));

        $time = $now->copy()->addDay()->setTime(0, 0, 0)->timestamp;
        $this->assertEquals($time, $service->parse('till tomorrow'));

        Carbon::setTestNow();
    }
}
