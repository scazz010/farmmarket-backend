<?php namespace App\GraphQL\Mutation;

use App\Entity\User;
use App\FarmMarket\Model\Farm\Command\RegisterFarm;
use App\Geo\Point;
use App\Repository\FarmRepository;
use Geocoder\Plugin\PluginProvider;
use Geocoder\Query\GeocodeQuery;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Prooph\ServiceBus\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use App\Repository\ImageRepository;


class RegisterFarmMutation implements MutationInterface, AliasedInterface
{
    /**
     * @var PluginProvider
     */
    private $geoCoder;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var FarmRepository
     */
    private $farmRepository;

    public function __construct(
        CommandBus $commandBus,
        PluginProvider $geoCoder,
        TokenStorageInterface $tokenStorage,
        FarmRepository $farmRepository
    )
    {
        $this->geoCoder = $geoCoder;
        $this->tokenStorage = $tokenStorage;
        $this->commandBus = $commandBus;
        $this->farmRepository = $farmRepository;
    }

    public function registerFarm($name, $postCode)
    {
        $results = $this->geoCoder->geocodeQuery(
            GeocodeQuery::create($postCode)
        );

        if ($results->isEmpty()) {
            throw new \Exception("Invalid postcode");
        }

        $coordinates = $results->first()->getCoordinates();
        $location = new Point($coordinates->getLatitude(), $coordinates->getLongitude());

        /** @var User $currentUser */
        $currentUser = $this->tokenStorage->getToken()->getUser();
        $emailAddress = $currentUser->getEmail();
        $farmerId = $currentUser->getId();
        $farmId = Uuid::uuid4();



        $command = RegisterFarm::withData($farmId->toString(), $farmerId, $name, $emailAddress, $location);
        $this->commandBus->dispatch($command);


        return $this->farmRepository->find($farmId);
    }

    public static function getAliases()
    {
        return [
            'registerFarm' => 'register_farm'
        ];
    }

}