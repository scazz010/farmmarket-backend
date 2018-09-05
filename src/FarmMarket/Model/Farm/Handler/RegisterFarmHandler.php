<?php namespace App\FarmMarket\Model\Farm\Handler;

use App\FarmMarket\Event\FarmLocationWasUpdated;
use App\FarmMarket\Event\FarmWasRegistered;
use App\FarmMarket\Model\Farm\Command\RegisterFarm;
use App\FarmMarket\Model\Farm\Farm;
use App\FarmMarket\Model\Farm\Repository\FarmCollection;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RegisterFarmHandler
{

    /**
     * @var FarmCollection
     */
    private $farmCollection;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    public function __construct(EventDispatcherInterface $eventDispatcher, FarmCollection $farmCollection)
    {
        $this->farmCollection = $farmCollection;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(RegisterFarm $command)
    {
        //TODO: Check for duplicate farm names!

        $farm = Farm::registerFarm(
            $command->farmId(),
            $command->name(),
            $command->emailAddress(),
            $command->location()
        );

        $this->farmCollection->save($farm);

        $this->eventDispatcher->dispatch(
            FarmWasRegistered::NAME,
            new FarmWasRegistered(
                $command->farmId(),
                $command->name(),
                $command->emailAddress()->toString()
            )
        );

        $this->eventDispatcher->dispatch(
            FarmLocationWasUpdated::NAME,
            new FarmLocationWasUpdated($command->farmId(), $command->location())
        );
    }
}

