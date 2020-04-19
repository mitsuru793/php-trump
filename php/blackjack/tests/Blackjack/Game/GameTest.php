<?php
declare(strict_types=1);

namespace BlackJack\Blackjack\Game;

use BlackJack\BlackJack\Command as BlackJackCommand;
use Helper\TestBase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class GameTest extends TestBase
{
    private BlackJackCommand $command;
    private CommandTester $tester;

    public function testRun()
    {
        $this->tester->setInputs([
            'stand',
            'stand',
        ]);
        $this->tester->execute([]);
        $actual = $this->tester->getDisplay();

        $this->assertRegExp('/Player [a-zA-Z]+ turn/', $actual);
        $this->assertRegExp('/(?:Player [a-zA-Z]+|Dealer) won/', $actual);
        $this->assertNotRegExp('/Error|Exception/', $actual);
    }

    public function setUp(): void
    {
        parent::setUp();
        $application = new Application();
        $application->add(new BlackJackCommand('blackjack'));

        $command = $application->find('blackjack');
        assert($command instanceof BlackJackCommand);
        $this->command = $command;

        $this->tester = new CommandTester($this->command);
    }
}