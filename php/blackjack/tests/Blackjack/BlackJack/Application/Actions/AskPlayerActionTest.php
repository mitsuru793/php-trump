<?php
declare(strict_types=1);

namespace BlackJack\Application\Actions;

use BlackJack\BlackJack\Application\Actions\AskPlayerAction;
use BlackJack\BlackJack\Domain\Playable\Player;
use Helper\TestBase;
use Symfony\Component\Console\Exception\MissingInputException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

class AskPlayerActionTest extends TestBase
{
    private AskPlayerAction $action;

    private Player $player;

    private StreamableInputInterface $input;

    private OutputInterface $output;

    private SymfonyStyle $io;

    private static function createStream(array $inputs)
    {
        $stream = fopen('php://memory', 'r+', false);

        foreach ($inputs as $input) {
            fwrite($stream, $input . PHP_EOL);
        }

        rewind($stream);

        return $stream;
    }

    public function setUp(): void
    {
        parent::setUp();

        $out = fopen('php://memory', 'r+');;
        $this->input = new ArrayInput([]);
        $this->output = new StreamOutput($out);
        $this->io = new SymfonyStyle($this->input, $this->output);

        $this->player = new Player('mike', []);
        $this->action = new AskPlayerAction($this->io, $this->player, false);
    }

    /**
     * @dataProvider choiceProvider
     */
    public function testCanChoice(string $choice)
    {
        $this->setInputs([
            $choice,
        ]);

        $action = ($this->action)();

        $expected = <<<EOF
        Player {$this->player->name()}'s action:
          [0] hit
          [1] stand
         > $choice
        EOF;
        $this->assertEquals($expected, $this->readOutput());
        $this->assertEquals($choice, $action->getValue());
    }

    public function choiceProvider()
    {
        return [
            ['hit'],
            ['stand'],
        ];
    }

    public function testThrowsWhenInvalidChoice()
    {
        $this->setInputs([
            'invalid',
        ]);

        $this->expectException(MissingInputException::class);
        ($this->action)();
    }

    protected function setInputs(array $inputs)
    {
        $stream = self::createStream($inputs);
        $this->input->setStream($stream);
    }

    private function readOutput(): string
    {
        rewind($this->output->getStream());
        $out = fread($this->output->getStream(), 2048);

        /**
         * Remove terminal codes.
         * @link \Symfony\Component\Console\Helper\QuestionHelper::autocomplete()
         */
        $out = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $out);
        return trim($out);
    }
}
