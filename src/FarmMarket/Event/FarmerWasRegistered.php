<?php

declare(strict_types=1);

namespace App\FarmMarket\Event;

use App\Entity\User;
use App\FarmMarket\Model\EmailAddress;
use App\FarmMarket\Model\Farm\FarmId;
use Symfony\Component\EventDispatcher\Event;

class FarmerWasRegistered extends Event
{
    const NAME = 'farmer.registered';

    /**
     * @var string
     */
    private $farmerId;

    /**
     * @var string
     */
    private $givenName;

    private $familyName;
    /**
     * @var string
     */
    private $emailAddress;

    /**
     * FarmWasRegistered constructor.
     * @param string $farmerId
     * @param string $givenName
     * @param string $familyName
     * @param string $emailAddress
     */
    public function __construct(
        string $farmerId,
        string $givenName,
        string $familyName,
        string $emailAddress
    ) {
        $this->farmerId = $farmerId;
        $this->givenName = $givenName;
        $this->familyName = $familyName;
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getFarmerId(): string
    {
        return $this->farmerId;
    }

    /**
     * @return string
     */
    public function getGivenName(): string
    {
        return $this->givenName;
    }

    /**
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}
