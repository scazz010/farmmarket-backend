<?php namespace App\FarmMarket\Model\Farmer\Handler;

use App\FarmMarket\Event\FarmLocationWasUpdated;
use App\FarmMarket\Event\FarmWasRegistered;
use App\FarmMarket\Model\Farm\Command\RegisterFarm;
use App\FarmMarket\Model\Farm\Farm;
use App\FarmMarket\Model\Farm\Repository\FarmCollection;

use App\FarmMarket\Model\Farmer\Command\RegisterFarmerCommand;
use App\FarmMarket\Model\Farmer\Farmer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RegisterFarmerCommandHandler
{
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
    }
}