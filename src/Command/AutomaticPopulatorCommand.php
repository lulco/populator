<?php

namespace Populator\Command;

use Faker\Factory;
use Populator\Database\DatabaseInterface;
use Populator\Helper\TableAnalyzer;
use Populator\Populator\AutomaticPopulator;
use Populator\Populator\PopulatorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AutomaticPopulatorCommand extends SimplePopulatorCommand
{
    private $databaseName;

    private $countBase;

    public function __construct(DatabaseInterface $database, string $language = Factory::DEFAULT_LOCALE, int $countBase = 5)
    {
        parent::__construct($database, $language);
        $this->databaseName = $database->getName();
        $this->countBase = $countBase;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->populators = $this->createPopulators($this->databases[$this->databaseName]);
        return parent::execute($input, $output);
    }

    /**
     * @param DatabaseInterface $database
     * @return PopulatorInterface[]
     */
    private function createPopulators(DatabaseInterface $database): array
    {
        $structures = $database->getStructure();
        $tableAnalyzer = new TableAnalyzer();
        $tableDepths = $tableAnalyzer->getDepths($structures);

        $populators = [];
        foreach ($tableDepths as $table => $depth) {
            $populators[] = new AutomaticPopulator($table, min(1000, pow($this->countBase, $depth + 1))); // TODO think about it
        }
        return $populators;
    }
}
