<?php

namespace App\Command;

use App\Geo\Point;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Prooph\ServiceBus\CommandBus;

use App\FarmMarket\Model\Farm\Command\RegisterFarm as RegisterFarmCommand;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterFarmConsole extends Command
{
    /**
     * @var CommandBus
     */
    private $proophCommandBus;
    /**
     * @var MessageBusInterface
     */
    private $symfonyMessageBus;

    public function __construct(CommandBus $commandBus, MessageBusInterface $symfonyMessageBus)
    {
        parent::__construct();
        $this->proophCommandBus = $commandBus;
        $this->symfonyMessageBus = $symfonyMessageBus;
    }

    protected function configure()
    {
        $this
            ->setName('farmMarket:register-farm')
            ->setDescription('Registers a new farm.')
            ->setHelp('This command allows you to register a farm...')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the farm')
            ->addArgument('email', InputArgument::REQUIRED, 'The email address of the farm')
            ->addArgument('latitude')
            ->addArgument('longitude')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $location = null;

        if ($input->getArgument('latitude') && $input->getArgument('longitude')) {
            $location = new Point($input->getArgument('latitude'), $input->getArgument('longitude'));
        }
        $registerFarmCommand = RegisterFarmCommand::withData(
            Uuid::uuid4(),
            $input->getArgument('name'),
            $input->getArgument('email'),
            $location
        );

        $this->proophCommandBus->dispatch($registerFarmCommand);
        //$this->symfonyMessageBus->dispatch($registerFarmCommand);
    }
}