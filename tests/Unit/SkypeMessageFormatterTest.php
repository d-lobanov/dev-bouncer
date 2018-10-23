<?php

namespace Tests\Unit;

use App\Services\SkypeMessageFormatter as Formatter;
use PHPUnit\Framework\TestCase;

class SkypeMessageFormatterTest extends TestCase
{
    public function testBold()
    {
        $formatter = new Formatter();

        $this->assertEquals('**test**', $formatter->bold('test'));
    }

    public function testTable()
    {
        $input = [
            ['dev111', 'john'],
            ['dev1', 'john_doe'],
        ];

        $formatter = new Formatter();

        $this->assertEquals("dev111 john    \n\ndev1   john_doe", $formatter->table($input));
    }

    public function testTableEmptyInput()
    {
        $formatter = new Formatter();

        $this->assertEquals('', $formatter->table([]));
    }
}
