<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as FOSUBUser;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\FarmerRepository")
 */
class User extends FOSUBUser
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
    public function setId($id) {
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
     * @param mixed $givenName
     */
    public function setGivenName($givenName): void
    {
        $this->givenName = $givenName;
    }

    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @param mixed $familyName
     */
    public function setFamilyName($familyName): void
    {
        $this->familyName = $familyName;
    }

    public function getFarm(): ?Farm
    {
        return $this->farm;
    }
}