<?php

namespace Populator\Tests\Event;

use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Populator\Database\DatabaseInterface;
use Populator\Populator\AbstractPopulator;
use Populator\Populator\PopulatorInterface;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class EventTest extends TestCase
{
    protected function createInput(): InputInterface
    {
        if (PHP_VERSION_ID < 80300) {
            return require __DIR__ . '/NullInputSymfony6.php';
        }
        return require __DIR__ . '/NullInputSymfony7.php';
    }

    protected function createOutput(): OutputInterface
    {
        return new class implements OutputInterface {
            private array $messages = [];

            private OutputFormatterInterface $formatter;

            private int $verbosity = self::VERBOSITY_NORMAL;

            public function __construct()
            {
                $this->formatter = new OutputFormatter();
            }

            public function getFormatter(): OutputFormatterInterface
            {
                return $this->formatter;
            }

            public function getVerbosity(): int
            {
                return $this->verbosity;
            }

            public function isDecorated(): bool
            {
                return $this->formatter->isDecorated();
            }

            public function setDecorated($decorated): void
            {
                $this->formatter->setDecorated($decorated);
            }

            public function setFormatter(OutputFormatterInterface $formatter): void
            {
                $this->formatter = $formatter;
            }

            public function setVerbosity($level): void
            {
                $this->verbosity = $level;
            }

            public function write($messages, $newline = false, $options = 0): void
            {
                if (!is_array($messages)) {
                    $messages = [$messages];
                }
                foreach ($messages as $message) {
                    $this->messages[$options][] = $message . ($newline ? "\n" : '');
                }
            }

            public function writeln($messages, $options = 0): void
            {
                if (!is_array($messages)) {
                    $messages = [$messages];
                }
                foreach ($messages as $message) {
                    $this->messages[$options][] = $message . "\n";
                }
            }

            public function getMessages($verbosity = null): array
            {
                if ($verbosity === null) {
                    return $this->messages;
                }

                return $this->messages[$verbosity] ?? [];
            }

            public function isDebug(): bool
            {
                return self::VERBOSITY_DEBUG <= $this->verbosity;
            }

            public function isQuiet(): bool
            {
                return self::VERBOSITY_QUIET === $this->verbosity;
            }

            public function isVerbose(): bool
            {
                return self::VERBOSITY_VERBOSE <= $this->verbosity;
            }

            public function isVeryVerbose(): bool
            {
                return self::VERBOSITY_VERY_VERBOSE <= $this->verbosity;
            }
        };
    }

    protected function createPopulator(string $table, int $count = 10, ?string $databaseIdentifier = null): PopulatorInterface
    {
        return new class($table, $count, $databaseIdentifier) extends AbstractPopulator {
            protected function generateData(Generator $faker): array
            {
                return [];
            }

            public function getLanguages(): array
            {
                return $this->languages;
            }

            public function getDatabases(): array
            {
                return $this->databases;
            }

            public function checkDatabase(?string $database = null): DatabaseInterface
            {
                return $this->getDatabase($database);
            }
        };
    }
}
