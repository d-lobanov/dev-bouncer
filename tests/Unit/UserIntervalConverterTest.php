<?php

namespace Tests\Unit;

use App\Services\UserIntervalConverter;
use Carbon\Carbon;
use Tests\TestCase;

class UserIntervalConverterTest extends TestCase
{
    public function testEmptyInput()
    {
        $service = new UserIntervalConverter();

        $this->assertNull($service->convert(''));
    }

    public function testInvalidInput()
    {
        $service = new UserIntervalConverter();

        $this->assertNull($service->convert('test'));
        $this->assertNull($service->convert('2m 2s'));
    }

    public function testZeroValue()
    {
        $service = new UserIntervalConverter();

        $this->assertNull($service->convert('0d'));
        $this->assertNull($service->convert('0h'));
        $this->assertNull($service->convert('0d 0h'));

        $this->assertNotNull($service->convert('0d 1h'));
    }

    public function testDaysLimit()
    {
        $service = new UserIntervalConverter();

        $this->assertNull($service->convert('9d'));
        $this->assertNull($service->convert('10d 2h'));
        $this->assertNotNull($service->convert('8d'));
    }

    public function testHoursLimit()
    {
        $service = new UserIntervalConverter();

        $this->assertNull($service->convert('24h'));
        $this->assertNull($service->convert('1d 24h'));
        $this->assertNotNull($service->convert('23h'));
    }

    public function testValidValue()
    {
        $service = new UserIntervalConverter();

        $now = now();
        Carbon::setTestNow($now->copy());

        $expected = $now->copy()->addDays(2)->addHours(2)->timestamp;
        $this->assertEquals($expected, $service->convert('2d 2h'));
        $this->assertEquals($expected, $service->convert('2h 2d'));

        $expected = $now->copy()->addDays(8)->addHours(23)->timestamp;
        $this->assertEquals($expected, $service->convert('8d 23h'));

        $expected = $now->copy()->addDays(1)->timestamp;
        $this->assertEquals($expected, $service->convert('1d'));

        Carbon::setTestNow();
    }
}
