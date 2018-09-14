<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleRepository")
 * @ORM\Table(name="sales")
 */
class Sale {
    /**
     * @var UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="Farm", inversedBy="sales")
     * @ORM\JoinColumn(name="farm_id", referencedColumnName="id")
     */
    private $farm;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }


}