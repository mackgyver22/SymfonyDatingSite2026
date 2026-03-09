<?php

namespace App\Command;

use App\Entity\City;
use App\Entity\State;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:import-cities', description: 'Import US states and cities from CSV')]
class ImportCitiesCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set("memory_limit", "2G");

        $io = new SymfonyStyle($input, $output);
        $csvPath = $this->getProjectDir() . '/var/us_cities.csv';

        if (!file_exists($csvPath)) {
            $io->error("CSV file not found at: $csvPath");
            return Command::FAILURE;
        }

        $handle = fopen($csvPath, 'r');
        fgetcsv($handle); // skip header row

        $states = [];
        $batchSize = 500;
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            [$id, $stateCode, $stateName, $cityName] = $row;

            if (!isset($states[$stateCode])) {
                $state = $this->em->getRepository(State::class)->findOneBy(['code' => $stateCode]);

                if (!$state) {
                    $state = new State();
                    $state->setCode($stateCode);
                    $state->setState($stateName);
                    $this->em->persist($state);
                }

                $states[$stateCode] = $state;
            }

            $city = new City();
            $city->setCity($cityName);
            $city->setState($states[$stateCode]);
            $this->em->persist($city);

            $count++;

            if ($count % $batchSize === 0) {
                $this->em->flush();
                $this->em->clear();
                $states = []; // rebuild state cache after clear
                $io->writeln("Imported $count rows...");
            }
        }

        fclose($handle);
        $this->em->flush();

        $io->success("Import complete. Total cities imported: $count");

        return Command::SUCCESS;
    }

    private function getProjectDir(): string
    {
        return dirname(__DIR__, 2);
    }
}
