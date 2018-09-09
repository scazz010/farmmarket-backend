<?php namespace App\FarmMarket\Model\Image\Handler;

use App\Entity\Image;
use App\FarmMarket\Model\Image\Command\UploadImage;
use App\Images\ImageManipulator;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImageHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(UploadImage $command)
    {
        $image = new Image($command->imageId());
        $image->setImageFile($command->image());
        $this->em->persist($image);
        $this->em->flush();
    }
}