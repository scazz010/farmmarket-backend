<?php namespace App\FarmMarket\Model\Farmer\Handler;

use App\FarmMarket\Event\FarmerWasRegistered;
use App\FarmMarket\Model\Farmer\Command\RegisterFarmerCommand;
use App\FarmMarket\Model\Farmer\Farmer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RegisterFarmerCommandHandler
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param RegisterFarmerCommand $command
     */
    public function __invoke(RegisterFarmerCommand $command)
    {
        Farmer::register(
            $command->userId(),
            $command->familyName(),
            $command->givenName(),
            $command->email()
        );

        $this->eventDispatcher->dispatch(
            FarmerWasRegistered::NAME,
            new FarmerWasRegistered(
                $command->userId(),
                $command->givenName(),
                $command->familyName(),
                $command->email()->toString()
            )
        );
    }
}
