<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm\Repository;


use App\FarmMarket\Model\Farm\Farm;
use App\FarmMarket\Model\Farm\FarmId;

interface FarmCollection
{
    public function save(Farm $farm): void;
    public function get(FarmId $farmId): ?Farm;
}