<?php

namespace App\Entity;

use App\FarmMarket\Model\Farmer\FarmerWriteModel;
use FOS\UserBundle\Model\User as FOSUBUser;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\FarmerRepository")
 */
class User extends FOSUBUser implements FarmerWriteModel
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * @ORM\Id
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $id;

    /**
     * @ORM\Column(name="given_name", type="string", length=255, nullable=true)
     */
    private $givenName;

    /**
     * @ORM\Column(name="family_name", type="string", length=255, nullable=true)
     */
    private $familyName;

    /**
     * One Farmer has One Farm.
     * @ORM\OneToOne(targetEntity="Farm", mappedBy="farmer")
     */
    private $farm;

    /**
     * @return mixed
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    public function getFarm(): ?Farm
    {
        return $this->farm;
    }

    public static function registerFarmer(
        string $id,
        string $givenName,
        string $familyName,
        string $email
    ): User {
        /** @var User $farmer */
        $farmer = new self();
        $farmer->id = $id;
        $farmer->familyName = $familyName;
        $farmer->givenName = $givenName;
        $farmer->email = $email;
        $farmer->username = $email;

        return $farmer;
    }
}
