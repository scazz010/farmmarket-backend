<?php

declare(strict_types=1);

namespace App\FarmMarket\ElasticSearch;

use App\FarmMarket\Event\FarmLocationWasUpdated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FarmLocationUpdater implements EventSubscriberInterface
{
    public function onFarmLocationUpdated(FarmLocationWasUpdated $farmLocationWasUpdated)
    {

    }

    public static function getSubscribedEvents()
    {
        return [
            FarmLocationWasUpdated::NAME => [
                ['onFarmLocationUpdated', 0]
            ]
        ];
    }
}