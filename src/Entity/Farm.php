<?php namespace App\Entity;

use App\FarmMarket\Model\Farm\FarmWriteModel;
use App\Geo\Point;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FarmRepository")
 * @ORM\Table(name="farms")
 */
class Farm implements FarmWriteModel {
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Accessor(getter="getIdAsString")
     * @Groups({"elastica"})
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
     * @Groups({"elastica"})
     */
    protected $location;

    /**
     * One Farm has One Preview Image.
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="preview_image_id", referencedColumnName="id")
     */
    protected $previewImage;

    /**
     * One Farm has One Preview Image.
     * @var User
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="farmer_id", referencedColumnName="id")
     */
    protected $farmer;


    /**
     * Total Number of Sales
     * @ORM\Column(type="integer", name="total_sales")
     */
    protected $totalSales;

    /**
     * Total Number of unique customers
     * @ORM\Column(type="integer", name="total_customers")
     */
    protected $totalCustomers;

    /**
     * Total Revenue
     * @var Money
     * @ORM\Embedded(class="\Money\Money")
     * @Exclude
     */
    protected $totalRevenue;

    /**
     * @ORM\OneToMany(targetEntity="Sale", mappedBy="farm")
     */
    protected $sales;


    public function getId() {
        return $this->id;
    }

    public function getIdAsString() {
        return $this->id->toString();
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

    /**
     * @return Money
     */
    public function getTotalRevenue(): Money
    {
        return $this->totalRevenue;
    }

    public function getTotalCustomers() : Int
    {
        return $this->totalCustomers;
    }

    public function getTotalSales(): Int
    {
        return $this->totalSales;
    }

    public function getSales()
    {
        $sale = new Sale();
        $sale->setId(Uuid::uuid4());
        return [$sale];
    }


    public static function registerFarm(
        UuidInterface $id,
        string $name,
        string $email,
        User $farmer
    ): FarmWriteModel
    {

        $farm = new self();
        $farm->id = $id;
        $farm->name = $name;
        $farm->email = $email;
        $farm->farmer = $farmer;

        $farm->totalCustomers = 0;
        $farm->totalRevenue = Money::GBP(0);
        $farm->totalSales = 0;

        return $farm;
    }
    public function setLocation(Point $point) {
        $this->location = $point;
    }

    public function setPreviewImage(Image $image) {
        $this->previewImage = $image;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }
}