<?php

declare(strict_types=1);

namespace App\FarmMarket\Model;

interface ValueObject
{
    public function sameValueAs(ValueObject $object): bool;
    public function toString() : string;
}