<?php


namespace App\Security;

use App\Entity\User;
use App\FarmMarket\Model\Farmer\Command\RegisterFarmerCommand;
use Auth0\JWTAuthBundle\Security\Auth0Service;
use Auth0\JWTAuthBundle\Security\Core\JWTUserProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\Intl\Exception\NotImplementedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class A0UserProvider implements JWTUserProviderInterface
{
    protected $auth0Service;

    /**
     * @var UserManagerInterface
     */

    private $userManager;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(Auth0Service $auth0Service, UserManagerInterface $userManager, CommandBus $commandBus) {
        $this->auth0Service = $auth0Service;
        $this->userManager = $userManager;
        $this->commandBus = $commandBus;
    }

    public function loadUserByJWT($jwt) {
         $data = $this->auth0Service->getUserProfileByA0UID($jwt->token,$jwt->sub);

         /** @var User $user */
         $user = $this->userManager->findUserBy(['id' => $jwt->sub]);
         if (!$user) {
             $registerFarmerCommand = RegisterFarmerCommand::withData(
                 $jwt->sub,
                 $data['given_name'],
                 $data['family_name'],
                 $data['email']
             );

             $this->commandBus->dispatch($registerFarmerCommand);

             //$user->setUsername($data['nickname']);
         }

        return $user;
    }

    public function loadUserByUsername($username)
    {
        throw new NotImplementedException('method not implemented');
    }

    public function getAnonymousUser() {
        return new A0AnonymousUser();
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'App\Entity\User';
    }
}