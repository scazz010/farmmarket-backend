<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm\Event;

use App\FarmMarket\Model\Farm\FarmId;
use Prooph\EventSourcing\AggregateChanged;
use Ramsey\Uuid\UuidInterface;

final class FarmPreviewImageWasUpdated extends AggregateChanged
{
    private $farmId;
    private $imageId;

    public static function toImage(FarmId $farmId, UuidInterface $imageId) : FarmPreviewImageWasUpdated
    {
        $event = self::occur($farmId->toString(), [
            'image_id' => $imageId->toString()
        ]);

        $event->farmId = $farmId->toString();
        $event->imageId = $imageId->toString();

        return $event;
    }
}