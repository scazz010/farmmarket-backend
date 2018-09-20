<?php namespace App\FarmMarket\Model\Farm\Handler;

use App\Entity\User;
use App\FarmMarket\Event\FarmLocationWasUpdated;
use App\FarmMarket\Event\FarmWasRegistered;
use App\FarmMarket\Model\Farm\Command\RegisterFarmToSellProduct;
use App\FarmMarket\Model\Farm\Exception\FarmNotFound;
use App\FarmMarket\Model\Farm\Farm;
use App\FarmMarket\Model\Farm\Repository\FarmCollection;

use App\Repository\FarmerRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RegisterFarmToSellProductHandler
{
    /**
     * @var FarmCollection
     */
    private $farmCollection;

    public function __construct(FarmCollection $farmCollection)
    {
        $this->farmCollection = $farmCollection;
    }

    public function __invoke(RegisterFarmToSellProduct $command)
    {
        $farm = $this->farmCollection->get($command->farmId());

        if (!$farm) {
            throw new FarmNotFound();
        }
    }
}
