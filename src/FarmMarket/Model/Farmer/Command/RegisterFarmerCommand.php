<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farmer\Command;

use App\FarmMarket\Model\EmailAddress;
use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Zend\Validator\EmailAddress as EmailAddressValidator;

final class RegisterFarmerCommand extends Command
    implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(
        string $userId,
        string $givenName,
        string $familyName,
        string $email
    ): RegisterFarmerCommand {
        return new self([
            'user_id' => $userId,
            'given_name' => $givenName,
            'family_name' => $familyName,
            'email' => $email
        ]);
    }

    public function userId(): string
    {
        return $this->payload['user_id'];
    }

    public function givenName(): string
    {
        return $this->payload['given_name'];
    }

    public function familyName(): string
    {
        return $this->payload['family_name'];
    }

    public function email(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['email']);
    }

    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'user_id');
        Assertion::keyExists($payload, 'email');

        $validator = new EmailAddressValidator();
        Assertion::true($validator->isValid($payload['email']));

        $this->payload = $payload;
    }
}
