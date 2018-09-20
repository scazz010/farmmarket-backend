<?php
namespace App\Tests;

use App\Entity\Farm;
use App\Entity\User;
use App\FarmMarket\Model\Farm\Command\RegisterFarm;
use App\Geo\Point;
use http\Exception\InvalidArgumentException;
use Prooph\ServiceBus\CommandBus;
use Ramsey\Uuid\Uuid;

class RegisterFarmTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;

    /** @var \Codeception\Module $symfony */
    private $symfony;

    public function __construct(
        ?string $name = null,
        array $data = [],
        string $dataName = ''
    ) {
        parent::__construct($name, $data, $dataName);
    }

    protected function _before()
    {
        $this->symfony = $this->getModule('Symfony');
    }

    protected function _after()
    {
    }

    // tests
    public function testICanRegisterAFarm()
    {
        $userId = $this->createUser();

        $farmId = Uuid::uuid4();
        $location = new Point(51.600661700009, -0.97846689679276);

        $registerFarmCommand = RegisterFarm::withData(
            $farmId,
            $userId,
            'Test Farm',
            'test@example.com',
            $location
        );

        /** @var CommandBus $commandBus */
        $commandBus = $this->symfony->grabService(
            'prooph_service_bus.farm_market_command_bus'
        );
        $commandBus->dispatch($registerFarmCommand);

        /*
         * Test event was persisted
         */

        /*
         * Test read model was updated
         */
        $this->tester->seeInRepository(Farm::class, [
            'id' => $farmId->toString(),
            'name' => 'Test Farm',
            'email' => 'test@example.com'
        ]);
    }

    public function testVariantsAreProtected()
    {
        $userId = $this->createUser();

        $this->specify("FarmId is a valid Uuid", function () use ($userId) {
            try {
                RegisterFarm::withData(
                    '',
                    $userId->toString(),
                    'Test Farm',
                    'test@rand.com',
                    null
                );
                $this->assertTrue(false);
            } catch (\Assert\InvalidArgumentException $e) {
                $this->assertTrue(true);
            }
        });

        $this->specify("Farm Name is required", function () use ($userId) {
            try {
                RegisterFarm::withData(
                    Uuid::uuid4(),
                    $userId->toString(),
                    '',
                    'test@rand.com',
                    null
                );
                $this->assertTrue(false);
            } catch (\Assert\InvalidArgumentException $e) {
                $this->assertTrue(true);
            }
        });

        $this->specify("Farm email is valid", function () use ($userId) {
            try {
                RegisterFarm::withData(
                    Uuid::uuid4(),
                    $userId->toString(),
                    'Test Farm',
                    'testrand.com',
                    null
                );
                $this->assertTrue(false);
            } catch (\Assert\InvalidArgumentException $e) {
                $this->assertTrue(true);
            }
        });

        $this->specify("Farmer ID is required", function () use ($userId) {
            try {
                RegisterFarm::withData(
                    Uuid::uuid4(),
                    '',
                    'Test Farm',
                    'test@rand.com',
                    null
                );
                $this->assertTrue(false);
            } catch (\Assert\InvalidArgumentException $e) {
                $this->assertTrue(true);
            }
        });
    }

    protected function createUser(
        $givenName = "sammy",
        $familyName = "carr",
        $email = null
    ) {
        $id = Uuid::uuid4();

        $this->tester->haveInRepository(User::class, [
            'id' => $id,
            'givenName' => $givenName,
            'familyName' => $familyName,
            'username' => $givenName . $familyName,
            'email' => $email ?: $givenName . '@' . $familyName . '.com'
        ]);

        return $id;
    }
}
