<?php

namespace Tests\Unit;

use App\Services\UserIntervalParser;
use Carbon\Carbon;
use Tests\TestCase;

class UserIntervalConverterTest extends TestCase
{
    /**
     * @var UserIntervalParser
     */
    private $service;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->service = new UserIntervalParser();
    }

    /**
     * @dataProvider invalidInputDataProvider
     * @expectedException \App\Exceptions\IntervalValidationException
     * @param string $invalidData
     */
    public function testParseInvalidFormat(string $invalidData)
    {
        $this->service->parse($invalidData);
    }

    public function invalidInputDataProvider(): array
    {
        return [
            'empty' => [''],
            'string' => ['test'],
            'months' => ['12m'],
            'minutes' => ['12i'],
            'numbers' => ['1 2 3'],
            'number' => ['123'],
        ];
    }

    /**
     * @expectedException \App\Exceptions\IntervalValidationException
     */
    public function testParseMinLimit()
    {
        $this->service->parse('0h');
    }

    /**
     * @expectedException \App\Exceptions\IntervalValidationException
     */
    public function testParseHoursMaxLimit()
    {
        $this->service->parse('49h');
    }

    /**
     * @expectedException \App\Exceptions\IntervalValidationException
     */
    public function testParseDaysMaxLimit()
    {
        $this->service->parse('3d');
    }

    public function testParseValidInput()
    {
        $now = now();
        Carbon::setTestNow($now->copy());

        $expected = $now->copy()->addDays(1)->addHours(2)->timestamp;
        $this->assertEquals($expected, $this->service->parse('1d 2h'));
        $this->assertEquals($expected, $this->service->parse('2h 1d'));
        $this->assertEquals($expected, $this->service->parse('1h 1d 1h'));

        $expected = $now->copy()->addDays(0)->addHours(23)->timestamp;
        $this->assertEquals($expected, $this->service->parse('0d 23h'));

        $expected = $now->copy()->addDays(1)->timestamp;
        $this->assertEquals($expected, $this->service->parse('1d'));

        $expected = $now->copy()->addHours(1)->timestamp;
        $this->assertEquals($expected, $this->service->parse('1h'));

        $time = $now->copy()->setTimezone('Europe/Minsk')->endOfDay()->timestamp;
        $this->assertEquals($time, $this->service->parse('till tomorrow'));

        Carbon::setTestNow();
    }
}
