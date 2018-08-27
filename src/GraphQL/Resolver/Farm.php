<?php
namespace App\GraphQL\Resolver;

use App\Repository\FarmRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class Farm implements ResolverInterface, AliasedInterface
{
    private $farmRepository;

    public function __construct(FarmRepository $farmRepository)
    {
        $this->farmRepository = $farmRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases()
    {
        return ['resolve' => 'Farm'];
    }

    public function resolve(Argument $args)
    {
        if (isset($args['id'])) {
            return $this->farmRepository->find($args['id']);
        }
        return $this->farmRepository->findAll();
    }
}