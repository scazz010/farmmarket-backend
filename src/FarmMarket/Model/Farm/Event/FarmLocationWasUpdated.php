<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm\Event;

use App\FarmMarket\Model\EmailAddress;
use App\FarmMarket\Model\Farm\FarmId;
use App\Geo\Point;
use Prooph\EventSourcing\AggregateChanged;

final class FarmLocationWasUpdated extends AggregateChanged
{
    /**
     * @var Point
     */
    private $location;

    /**
     * @var FarmId
     */
    private $farmId;

    public static function withData(FarmId $farmId, Point $location): FarmLocationWasUpdated
    {
        /** @var self $event */
        $event = self::occur($farmId->toString(), [
            'latitude' => $location->getLat(),
            'longitude' => $location->getLong(),
        ]);

        $event->farmId = $farmId;
        $event->location = $location;

        return $event;
    }

    public function farmId(): FarmId
    {
        if (null === $this->farmId) {
            $this->farmId = FarmId::fromString($this->aggregateId());
        }
        return $this->farmId;
    }

    public function location(): Point
    {
        if (null === $this->location) {
            $this->location = new Point($this->payload['latitude'], $this->payload['longitude']);
        }
        return $this->location;
    }
}