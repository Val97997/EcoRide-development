<?php
// src/Command/CheckProductQuantitiesCommand.php
namespace App\Command;

use App\Service\SeatQuantityChecker;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:check-seat-availability', 'Checks and deletes routes with no availability left')]
class SeatQuantityCheckCommand extends Command{


    private $seatQuantityChecker;

    public function __construct(SeatQuantityChecker $seatQuantityChecker){
        $this->seatQuantityChecker = $seatQuantityChecker;
        parent::__construct();
    }

    protected function configure():void{
        $this->setDescription('Checks and deletes routes with no availability left');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->seatQuantityChecker->checkAndDeleteCarsharesWithZeroQuantity();
        $output->writeln('Routes availability checked and updated');

        return Command::SUCCESS;
        
    }
}