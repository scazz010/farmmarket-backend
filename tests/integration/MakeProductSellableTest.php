<?php
namespace App\Tests;

use App\FarmMarket\Model\Farm\Command\RegisterFarm;
use App\FarmMarket\Model\Farm\Command\RegisterFarmToSellProduct;
use App\FarmMarket\Model\Farm\Exception\FarmNotFound;
use App\FarmMarket\Model\Farm\FarmId;
use App\FarmMarket\Model\Farmer\Command\RegisterFarmerCommand;
use Ramsey\Uuid\Uuid;

class MakeProductSellableTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;

    /** @var FarmId $farmId */
    protected $farmId;

    protected $productId;

    protected $commandBus;

    protected $userId;

    protected function _before()
    {
        $this->farmId = FarmId::generate();
        $this->productId = UUid::uuid4(); // todo - wrong
        $this->userId = 'google|23923882u3';

        $this->commandBus = $this->getModule('Symfony')->grabService(
            'prooph_service_bus.farm_market_command_bus'
        );

        $this->createFarmer($this->userId);
        $this->createFarm($this->farmId, $this->userId);
    }

    protected function _after()
    {
    }

    protected function createFarmer(string $userId)
    {
        $command = RegisterFarmerCommand::withData(
            $userId,
            'Random',
            'Name',
            'random@name.com'
        );
        $this->commandBus->dispatch($command);
    }

    protected function createFarm(FarmId $farmId, string $userId)
    {
        $command = RegisterFarm::withData(
            $farmId->toString(),
            $userId,
            'Random Name',
            'random@email.com',
            null
        );
        $this->commandBus->dispatch($command);
    }

    // tests
    public function testMakeProductSellable()
    {
        $registerToSellProductCommand = RegisterFarmToSellProduct::withData(
            $this->farmId,
            $this->productId
        );

        $this->commandBus->dispatch($registerToSellProductCommand);
    }

    public function testInvariants()
    {
        $nonExistentId = FarmId::generate();

        try {
            $registerToSellProductCommand = RegisterFarmToSellProduct::withData(
                $nonExistentId,
                $this->productId
            );
            $this->commandBus->dispatch($registerToSellProductCommand);

            $this->fail("Failed to throw FarmNotFound exception");
        } catch (FarmNotFound $e) {
            $this->addToAssertionCount(1);
        }
    }
}
