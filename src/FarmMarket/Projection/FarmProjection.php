<?php

declare(strict_types=1);

namespace App\FarmMarket\Projection;

use App\FarmMarket\Model\Farm\Event\FarmLocationWasUpdated;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

use App\FarmMarket\Model\Farm\Event\FarmWasRegistered;

/**
 * Class FarmProjection
 */
final class FarmProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when([
                FarmWasRegistered::class => function ($state, FarmWasRegistered $event) {
                    $readModel = $this->readModel();

                    $readModel->stack('insert', [
                        'id' => $event->farmId()->toString(),
                        'name' => $event->name(),
                        'email' => $event->emailAddress()->toString(),
                    ]);
                },
                FarmLocationWasUpdated::class => function ($state, FarmLocationWasUpdated $event) {
                    $readModel = $this->readModel();

                    $readModel->stack('updateLocation', $event->farmId()->toString(), $event->location());
                }
            ]);

        return $projector;
    }
}