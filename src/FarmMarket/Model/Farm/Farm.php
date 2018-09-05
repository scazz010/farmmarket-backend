<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm;

use App\FarmMarket\Model\Farm\Event\FarmLocationWasUpdated;
use App\Geo\Point;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

use App\FarmMarket\Model\EmailAddress;
use App\FarmMarket\Model\Farm\Event\FarmWasRegistered;

class Farm extends AggregateRoot
{
    /**
     * @var FarmId
     */
    private $farmId;

    private $name;

    private $email;

    /**
     * @var Point
     */
    private $location;

    public static function registerFarm(
        FarmId $farmId,
        $name,
        EmailAddress $email,
        Point $location=null
    ) : Farm
    {
        $self = new self();

        $self->recordThat(FarmWasRegistered::withData(
            $farmId,
            $name,
            $email
        ));

        if ($location) {
            $self->recordThat(FarmLocationWasUpdated::withData($farmId, $location));
        }

        return $self;
    }

    protected function whenFarmWasRegistered(FarmWasRegistered $event)
    {
        $this->farmId = $event->farmId();
        $this->name = $event->name();
        $this->email = $event->emailAddress();
    }

    protected function whenFarmLocationWasUpdated(FarmLocationWasUpdated $event)
    {
        $this->location = $event->location();
    }

    protected function aggregateId(): string
    {
        return $this->farmId->toString();
    }

    protected function apply(AggregateChanged $e): void
    {
        $handler = $this->determineEventHandlerMethodFor($e);
        if (! method_exists($this, $handler)) {
            throw new \RuntimeException(sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                get_class($this)
            ));
        }
        $this->{$handler}($e);
    }

    protected function determineEventHandlerMethodFor(AggregateChanged $e): string
    {
        return 'when' . implode(array_slice(explode('\\', get_class($e)), -1));
    }
}