<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farmer\Event;

use App\FarmMarket\Model\EmailAddress;
use Prooph\EventSourcing\AggregateChanged;

final class FarmerWasRegistered extends AggregateChanged
{
    private $userId;

    private $givenName;

    private $familyName;

    private $email;

    public static function withData(
        string $userId,
        string $givenName,
        string $familyName,
        EmailAddress $email
    ): FarmerWasRegistered {
        $event = self::occur($userId, [
            'givenName' => $givenName,
            'familyName' => $familyName,
            'email' => $email->toString()
        ]);

        $event->familyName = $familyName;
        $event->givenName = $givenName;
        $event->userId = $userId;
        $event->email = $email;

        return $event;
    }

    public function familyName(): string
    {
        return $this->familyName;
    }

    public function givenName(): string
    {
        return $this->givenName;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function email(): EmailAddress
    {
        return $this->email;
    }
}
