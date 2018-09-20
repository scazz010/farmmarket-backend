<?php

namespace App\FarmMarket\Model\Farm;

use App\Entity\Image;
use App\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface FarmWriteModel
{
    public static function registerFarm(
        UuidInterface $uuid,
        string $name,
        string $email,
        User $farmer
    );

    public function setPreviewImage(Image $image);
}
