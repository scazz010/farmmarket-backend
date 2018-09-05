<?php

declare(strict_types=1);

namespace App\FarmMarket\Event;

use App\FarmMarket\Model\EmailAddress;
use App\FarmMarket\Model\Farm\FarmId;
use Symfony\Component\EventDispatcher\Event;

class FarmWasRegistered extends Event
{
    const NAME = 'farm.registered';

    /**
     * @var FarmId
     */
    private $farmId;

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $emailAddress;

    /**
     * FarmWasRegistered constructor.
     * @param FarmId $farmId
     * @param string $name
     * @param string $emailAddress
     */
    public function __construct(FarmId $farmId, string $name, string $emailAddress)
    {
        $this->farmId = $farmId;
        $this->name = $name;
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return FarmId
     */
    public function getFarmId(): FarmId
    {
        return $this->farmId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}