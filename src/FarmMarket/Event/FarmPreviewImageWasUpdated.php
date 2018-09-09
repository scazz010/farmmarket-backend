<?php

declare(strict_types=1);

namespace App\FarmMarket\Event;

use App\Entity\Image;
use App\FarmMarket\Model\Farm\FarmId;
use Symfony\Component\EventDispatcher\Event;

class FarmPreviewImageWasUpdated extends Event
{
    const NAME = 'farm.preview_image.updated';

    /**
     * @var FarmId
     */
    private $farmId;
    /**
     * @var Image
     */
    private $image;


    public function __construct(FarmID $farmId, Image $image)
    {
        $this->farmId = $farmId;
        $this->image = $image;
    }

    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @return FarmId
     */
    public function getFarmId(): FarmId
    {
        return $this->farmId;
    }
}