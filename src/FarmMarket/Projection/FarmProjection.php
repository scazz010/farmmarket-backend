<?php

declare(strict_types=1);

namespace App\FarmMarket\Projection;

use App\Entity\Farm;
use App\FarmMarket\Event\FarmLocationWasUpdated;
use App\FarmMarket\Event\FarmPreviewImageWasUpdated;
use App\FarmMarket\Event\FarmWasRegistered;
use App\FarmMarket\Model\Farm\FarmWriteModel;
use App\Repository\FarmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FarmProjection implements EventSubscriberInterface
{
    /**
     * @var FarmRepository
     */
    private $farmRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(FarmRepository $farmRepository, EntityManagerInterface $em)
    {
        $this->farmRepository = $farmRepository;
        $this->em = $em;
    }

    public function onFarmWasRegistered(FarmWasRegistered $event)
    {
        $farm = new FarmWriteModel();
        $farm->setId($event->getFarmId()->toUuid());
        $farm->setName($event->getName());
        $farm->setEmail($event->getEmailAddress());
        $this->em->persist($farm);
        $this->em->flush();
    }

    public function onFarmLocationUpdated(FarmLocationWasUpdated $farmLocationWasUpdated)
    {
        /** @var FarmWriteModel $farm */
        $farm = $this->farmRepository->find($farmLocationWasUpdated->getFarmId()->toString());
        $farm->setLocation($farmLocationWasUpdated->getLocation());
        $this->em->flush();
    }

    public function onFarmPreviewImageUpdated(FarmPreviewImageWasUpdated $farmPreviewImageWasUpdated)
    {
        /** @var FarmWriteModel $farm */
        $farm = $this->farmRepository->find($farmPreviewImageWasUpdated->getFarmId()->toString());

        $farm->setPreviewImage($farmPreviewImageWasUpdated->getImage());
        $this->em->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            \App\FarmMarket\Event\FarmWasRegistered::NAME => 'onFarmWasRegistered',
            FarmLocationWasUpdated::NAME => [
                ['onFarmLocationUpdated', 0]
            ],
            FarmPreviewImageWasUpdated::NAME => 'onFarmPreviewImageUpdated'
        ];
    }
}