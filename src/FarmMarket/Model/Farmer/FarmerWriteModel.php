<?php

namespace App\FarmMarket\Model\Farmer;

use App\Entity\User;

interface FarmerWriteModel
{
    public static function registerFarmer(
        string $id,
        string $givenName,
        string $familyName,
        string $email
    ): User;
}
