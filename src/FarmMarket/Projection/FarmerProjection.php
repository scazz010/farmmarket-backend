<?php

declare(strict_types=1);

namespace App\FarmMarket\Projection;

use App\Entity\Farm;
use App\FarmMarket\Event\FarmerWasRegistered;
use App\FarmMarket\Model\Farmer\FarmerWriteModel;
use App\Repository\FarmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Money\Money;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FarmerProjection implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FarmerWriteModel
     */
    private $farmerWriter;

    public function __construct(
        EntityManagerInterface $em,
        FarmerWriteModel $farmerWriter
    ) {
        $this->em = $em;
        $this->farmerWriter = $farmerWriter;
    }

    public function onFarmerWasRegistered(FarmerWasRegistered $event)
    {
        $farmer = $this->farmerWriter::registerFarmer(
            $event->getFarmerId(),
            $event->getGivenName(),
            $event->getFamilyName(),
            $event->getEmailAddress()
        );
        $this->em->persist($farmer);
        $this->em->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            FarmerWasRegistered::NAME => 'onFarmerWasRegistered'
        ];
    }
}
