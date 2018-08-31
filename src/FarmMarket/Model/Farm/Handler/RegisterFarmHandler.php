<?php namespace App\FarmMarket\Model\Farm\Handler;

use App\FarmMarket\Model\Farm\Command\RegisterFarm;
use App\FarmMarket\Model\Farm\Farm;
use App\FarmMarket\Model\Farm\Repository\FarmCollection;

class RegisterFarmHandler
{

    /**
     * @var FarmCollection
     */
    private $farmCollection;


    public function __construct(FarmCollection $farmCollection)
    {

        $this->farmCollection = $farmCollection;
    }

    public function __invoke(RegisterFarm $command)
    {
        //TODO: Check for duplicate farm names!

        $farm = Farm::registerFarm(
            $command->farmId(),
            $command->name(),
            $command->emailAddress()
        );

        $this->farmCollection->save($farm);
    }
}

