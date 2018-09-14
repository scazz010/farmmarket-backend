<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farmer\Event;

use App\FarmMarket\Model\EmailAddress;
use Prooph\EventSourcing\AggregateChanged;

final class FarmerWasRegistered extends AggregateChanged
{
    public static function withData(
        string $userId,
        string $givenName,
        string $familyName,
        EmailAddress $email
    ) : FarmerWasRegistered
    {
        $event = self::occur(
            $userId,
            [
                'givenName' => $givenName,
                'familyName'=> $familyName,
                'email' => $email->toString()
            ]
        );

        return $event;
    }
}