<?php namespace App\FarmMarket\Model\Farm\Handler;

use App\Entity\User;
use App\FarmMarket\Event\FarmLocationWasUpdated;
use App\FarmMarket\Event\FarmWasRegistered;
use App\FarmMarket\Model\Farm\Command\RegisterFarm;
use App\FarmMarket\Model\Farm\Farm;
use App\FarmMarket\Model\Farm\Repository\FarmCollection;

use App\Repository\FarmerRepository;
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
    /**
     * @var FarmerRepository
     */
    private $farmerRepository;


    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FarmCollection $farmCollection,
        FarmerRepository $farmerRepository
    )
    {
        $this->farmCollection = $farmCollection;
        $this->eventDispatcher = $eventDispatcher;
        $this->farmerRepository = $farmerRepository;
    }

    public function __invoke(RegisterFarm $command)
    {
        //TODO: Check for duplicate farm names!

        $farm = Farm::registerFarm(
            $command->farmId(),
            $command->farmerId(),
            $command->name(),
            $command->emailAddress(),
            $command->location()
        );

        $this->farmCollection->save($farm);

        /** @var User $farmer */
        $farmer = $this->farmerRepository->find($command->farmerId());

        $this->eventDispatcher->dispatch(
            FarmWasRegistered::NAME,
            new FarmWasRegistered(
                $command->farmId(),
                $farmer,
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

