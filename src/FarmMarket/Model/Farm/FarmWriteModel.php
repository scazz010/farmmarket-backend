<?php

namespace App\FarmMarket\Model\Farm;

use App\Entity\Image;
use App\Geo\Point;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

Interface FarmWriteModel
{
    public static function registerFarm(
        UuidInterface $uuid,
        string $name
    );

    public function setPreviewImage(Image $image);

}