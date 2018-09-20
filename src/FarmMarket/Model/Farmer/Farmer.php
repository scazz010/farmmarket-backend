<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farmer;

use App\FarmMarket\Model\EmailAddress;
use App\FarmMarket\Model\Farmer\Event\FarmerWasRegistered;
use App\FarmMarket\Model\Farm\Farm;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Symfony\Component\Validator\Constraints\Email;

class Farmer extends AggregateRoot
{
    protected $farmerId;
    protected $givenName;
    protected $familyName;
    protected $emailAddress;

    public static function register(
        string $userId,
        string $givenName,
        string $familyName,
        EmailAddress $email
    ) {
        $self = new self();

        $self->recordThat(
            FarmerWasRegistered::withData(
                $userId,
                $givenName,
                $familyName,
                $email
            )
        );
    }

    public function whenFarmerWasRegistered(FarmerWasRegistered $event)
    {
        $this->familyName = $event->familyName();
        $this->givenName = $event->givenName();
        $this->emailAddress = $event->email();
        $this->farmerId = $event->userId();
    }

    protected function aggregateId(): string
    {
        return $this->farmerId;
    }

    protected function apply(AggregateChanged $e): void
    {
        $handler = $this->determineEventHandlerMethodFor($e);
        if (!method_exists($this, $handler)) {
            throw new \RuntimeException(
                sprintf(
                    'Missing event handler method %s for aggregate root %s',
                    $handler,
                    get_class($this)
                )
            );
        }
        $this->$handler($e);
    }

    protected function determineEventHandlerMethodFor(
        AggregateChanged $e
    ): string {
        return 'when' . implode(array_slice(explode('\\', get_class($e)), -1));
    }
}
