<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * @var UuidInterface
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
     * @var string $unitOfSale
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $unitOfSale;

    /**
     * @var int $defaultSaleQuantity
     * @ORM\Column(type="integer")
     */
    protected $defaultSaleQuantity;

    public static function makeSalable(
        UuidInterface $id,
        string $name,
        string $unitOfSale = null,
        int $defaultSaleQuantity = 1
    ): Product {
        $product = new self();
        $product->id = $id;
        $product->name = $name;
        $product->unitOfSale = $unitOfSale;
        $product->defaultSaleQuantity = $defaultSaleQuantity;

        return $product;
    }
}
