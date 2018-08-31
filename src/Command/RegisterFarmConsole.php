<?php

namespace App\Command;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Prooph\ServiceBus\CommandBus;

use App\FarmMarket\Model\Farm\Command\RegisterFarm as RegisterFarmCommand;

class RegisterFarmConsole extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
        $this
            ->setName('farmMarket:register-farm')
            ->setDescription('Registers a new farm.')
            ->setHelp('This command allows you to register a farm...')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the farm')
            ->addArgument('email', InputArgument::REQUIRED, 'The email address of the farm')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $registerFarmCommand = RegisterFarmCommand::withData(
            Uuid::uuid4(),
            $input->getArgument('name'),
            $input->getArgument('email')
        );

        $this->commandBus->dispatch($registerFarmCommand);
    }
}