<?php

declare(strict_types=1);

namespace App\FarmMarket\Event;

use App\FarmMarket\Model\Farm\FarmId;
use App\Geo\Point;
use Symfony\Component\EventDispatcher\Event;

class FarmLocationWasUpdated extends Event
{
    const NAME = 'farm.location.updated';

    /**
     * @var FarmId
     */
    private $farmId;
    /**
     * @var Point
     */
    private $location;

    public function __construct(FarmId $farmId, Point $location)
    {
        $this->farmId = $farmId;
        $this->location = $location;
    }

    /**
     * @return Point
     */
    public function getLocation(): Point
    {
        return $this->location;
    }

    /**
     * @return FarmId
     */
    public function getFarmId(): FarmId
    {
        return $this->farmId;
    }
}