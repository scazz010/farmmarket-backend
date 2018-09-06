<?php
namespace App\GraphQL\Resolver;

use App\Elastic\FarmFinder;
use App\Geo\Point;
use App\Repository\FarmRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class Farms implements ResolverInterface, AliasedInterface
{
    private $farmRepository;

    /**
     * @var FarmFinder
     */
    private $farmFinder;

    public function __construct(FarmFinder $farmFinder, FarmRepository $farmRepository)
    {
        $this->farmRepository = $farmRepository;
        $this->farmFinder = $farmFinder;
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases()
    {
        return ['resolve' => 'Farms'];
    }

    public function resolve(Argument $args)
    {
        if (isset($args['location'])) {
            $startingPoint = new Point($args['location']['latitude'], $args['location']['longitude']);
            return $this->farmFinder->findFarmsNearPoint($startingPoint);
        }


        return $this->farmRepository->findAll();
    }
}