<?php

namespace Tests\Unit\Http\Middleware\Bot;

use App\Http\Middleware\Bot\RemoveBotNickname;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Tests\TestCase;

/**
 * @see RemoveBotNickname
 */
class RemoveBotNicknameTest extends TestCase
{
    /**
     * @var BotMan
     */
    private $botMan;

    /**
     * @var RemoveBotNickname
     */
    private $middleware;

    /**
     * @var string
     */
    private $initBotName;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->botMan = $this->prophesize(BotMan::class)->reveal();
        $this->middleware = new RemoveBotNickname();

        $this->initBotName = config('botman.config.bot_name');
        config()->set('botman.config.bot_name', 'my_super_bot');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        config()->set('botman.config.bot_name', $this->initBotName);

        parent::tearDown();
    }

    /**
     * @dataProvider usernameDataProvider
     */
    public function testReceived(string $input, string $expected): void
    {
        $message = new IncomingMessage($input, null, null);

        $this->middleware->received($message, function (IncomingMessage $message) use ($expected) {
            $this->assertEquals($expected, $message->getText());
        }, $this->botMan);
    }

    public function usernameDataProvider(): array
    {
        return [
            'empty' => ['', ''],
            'without_username' => ['how do you do?', 'how do you do?'],
            'username' => [' my_super_bot ', '  '],
            'username_and_message' => ['my_super_bot give me five', ' give me five'],
            '@username' => ['@my_super_bot ', ' '],
            '@username_and_message' => ['@my_super_bot test', ' test'],
            'username_and_@username' => ['@my_super_bot my_super_bot test', '  test'],
        ];
    }
}
