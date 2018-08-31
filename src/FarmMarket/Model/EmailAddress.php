<?php

declare(strict_types=1);

namespace App\FarmMarket\Model;

use Zend\Validator\EmailAddress as EmailAddressValidator;

final class EmailAddress implements ValueObject
{
    /**
     * @var string
     */
    private $email;

    public static function fromString(string $email): EmailAddress
    {
        $validator = new EmailAddressValidator();
        if (! $validator->isValid($email)) {
            throw new \InvalidArgumentException('Invalid email address');
        }
        return new self($email);
    }

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function sameValueAs(ValueObject $other): bool
    {
        return get_class($this) === get_class($other) && $this->toString() === $other->toString();
    }
}