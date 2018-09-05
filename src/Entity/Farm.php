<?php namespace App\Entity;

use App\Geo\Point;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FarmRepository")
 * @ORM\Table(name="farms")
 */
class Farm {
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="point", nullable=true)
     * @var Point
     */
    private $location;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getLocation() : ?Point {
        return $this->location;
    }

    /**
     * TODO: remove this from the read model somehow....
     * @param Point $point
     */
    public function setLocation(Point $point) {
        $this->location = $point;
    }
    public function setId(UuidInterface $id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }
}