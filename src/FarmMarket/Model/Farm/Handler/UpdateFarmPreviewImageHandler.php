<?php namespace App\FarmMarket\Model\Farm\Handler;

use App\Entity\Image;
use App\FarmMarket\Event\FarmPreviewImageWasUpdated;
use App\FarmMarket\Model\Farm\Farm;
use App\FarmMarket\Model\Farm\Repository\FarmCollection;
use App\FarmMarket\Model\Farm\Command\UpdateFarmPreviewImage;
use App\FarmMarket\Model\Image\Command\UploadImage;
use App\Images\ImageManipulator;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateFarmPreviewImageHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ImageRepository
     */
    private $imageRepository;
    /**
     * @var FarmCollection
     */
    private $farmCollection;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        EntityManagerInterface $em, ImageRepository $imageRepository, FarmCollection $farmCollection, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->imageRepository = $imageRepository;
        $this->farmCollection = $farmCollection;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(UpdateFarmPreviewImage $command)
    {
        $image = $this->persistImage($command->imageId(), $command->image());

        $farm = $this->farmCollection->get($command->farmId());

        if (!$farm) {
            throw new \Exception("Farm not found!");
        }

        $farm->updatePreviewImage($image);
        $this->farmCollection->save($farm);

        $this->eventDispatcher->dispatch(FarmPreviewImageWasUpdated::NAME,
            new FarmPreviewImageWasUpdated($command->farmId(), $image));
    }

    /* Skip event sourcing for images since it's automatic and we don't really care about images */
    private function persistImage(Uuid $imageId, File $imageFile) : Image
    {
        $image = new Image($imageId);
        $image->setImageFile($imageFile);
        $this->em->persist($image);
        $this->em->flush();
        return $image;
    }
}