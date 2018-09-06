<?php namespace App\Entity;

use App\Geo\Point;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FarmRepository")
 * @ORM\Table(name="farms")
 */
class Farm {
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Accessor(getter="getIdAsString")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @Accessor(getter="getLocationAsString")
     * @ORM\Column(type="point", nullable=true)
     * @var Point
     */
    protected $location;

    public function getId() {
        return $this->id;
    }

    public function getIdAsString() {
        return "wooo";
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    /**
     * @return Point|null
     */
    public function getLocation() : ?Point {
        return $this->location;
    }

    public function getLocationAsString() : ?String {
        if ($this->getLocation()) {
            return $this->getLocation()->__toString();
        }
        return null;
    }
}