<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm\Event;

use App\FarmMarket\Model\EmailAddress;
use App\FarmMarket\Model\Farm\FarmId;
use Prooph\EventSourcing\AggregateChanged;

final class FarmWasRegistered extends AggregateChanged
{
    /**
     * @var FarmId
     */
    private $farmId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var EmailAddress
     */
    private $emailAddress;


    public static function withData(FarmId $farmId, string $farmerId, $name, EmailAddress $emailAddress): FarmWasRegistered
    {
        /** @var self $event */
        $event = self::occur($farmId->toString(), [
            'farmerId' => $farmerId,
            'name' => $name,
            'email' => $emailAddress->toString(),
        ]);

        $event->farmId = $farmId;
        $event->name = $name;
        $event->emailAddress = $emailAddress;
        return $event;
    }

    public function farmId(): FarmId
    {
        if (null === $this->farmId) {
            $this->farmId = FarmId::fromString($this->aggregateId());
        }
        return $this->farmId;
    }

    public function name(): string
    {
        if (null === $this->name) {
            $this->name = $this->payload['name'];
        }
        return $this->name;
    }

    public function emailAddress(): EmailAddress
    {
        if (null === $this->emailAddress) {
            $this->emailAddress = EmailAddress::fromString($this->payload['email']);
        }
        return $this->emailAddress;
    }

}