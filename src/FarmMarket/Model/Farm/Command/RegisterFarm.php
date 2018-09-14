<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm\Command;

use App\Geo\Point;
use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use App\FarmMarket\Model\EmailAddress;
use App\FarmMarket\Model\Farm\FarmId;
use Zend\Validator\EmailAddress as EmailAddressValidator;

final class RegisterFarm extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(
        string $farmId,
        string $farmerId,
        string $name,
        string $email,
        Point $location
    ): RegisterFarm
    {
        return new self([
            'farm_id' => $farmId,
            'farmer_id' => $farmerId,
            'name' => $name,
            'email' => $email,
            'location' => $location
        ]);
    }


    public function farmId(): FarmId
    {
        return FarmId::fromString($this->payload['farm_id']);
    }

    public function name(): string
    {
        return $this->payload['name'];
    }

    public function emailAddress(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['email']);
    }

    public function location(): Point
    {
        return $this->payload['location'];
    }

    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'farm_id');
        Assertion::uuid($payload['farm_id']);
        Assertion::keyExists($payload, 'name');
        Assertion::string($payload['name']);
        Assertion::keyExists($payload, 'email');
        $validator = new EmailAddressValidator();
        Assertion::true($validator->isValid($payload['email']));
        $this->payload = $payload;
    }
}