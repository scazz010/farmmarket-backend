<?php


namespace App\Security;

use App\Entity\User;
use Auth0\JWTAuthBundle\Security\Auth0Service;
use Auth0\JWTAuthBundle\Security\Core\JWTUserProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
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

    public function __construct(Auth0Service $auth0Service, UserManagerInterface $userManager, EntityManagerInterface $em) {
        $this->auth0Service = $auth0Service;
        $this->userManager = $userManager;
        $this->em = $em;
    }

    public function loadUserByJWT($jwt) {
        // you can fetch the user profile from the auth0 api
        // or from your database

         $data = $this->auth0Service->getUserProfileByA0UID($jwt->token,$jwt->sub);

         /** @var User $user */
         $user = $this->userManager->findUserBy(['id' => $jwt->sub]);
         if (!$user) {
             $user = $this->userManager->createUser();
             $user->setId($jwt->sub);
             $user->setEnabled(true);
             $user->setEmail($data['email']);
             $user->setUsername($data['nickname']);
             $user->setGivenName($data['given_name']);
             $user->setFamilyName($data['family_name']);
             $this->em->persist($user);
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