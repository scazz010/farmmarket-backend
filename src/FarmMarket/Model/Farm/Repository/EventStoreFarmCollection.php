<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm\Repository;

use Prooph\EventSourcing\Aggregate\AggregateRepository;

use App\FarmMarket\Model\Farm\Farm;
use App\FarmMarket\Model\Farm\FarmId;

final class EventStoreFarmCollection extends AggregateRepository implements FarmCollection
{
    public function save(Farm $farm): void
    {
        $this->saveAggregateRoot($farm);
    }
    public function get(FarmId $farmId): ?Farm
    {
        return $this->getAggregateRoot($farmId->toString());
    }
}