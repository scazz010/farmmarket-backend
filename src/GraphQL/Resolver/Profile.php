<?php
namespace App\GraphQL\Resolver;

use App\Repository\FarmRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallContext;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Profile implements ResolverInterface, AliasedInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Returns methods aliases.
     *
     * For instance:
     * array('myMethod' => 'myAlias')
     *
     * @return array
     */
    public static function getAliases()
    {
        return [ 'resolve' => 'Profile' ];
    }

    public function resolve()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}