<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm;

use App\FarmMarket\Model\ValueObject;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class FarmId implements ValueObject
{
    /**
     * @var UuidInterface
     */
    private $uuid;
    public static function generate(): FarmId
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $farmId): FarmId
    {
        return new self(Uuid::fromString($farmId));
    }

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public function toUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function sameValueAs(ValueObject $other): bool
    {
        return get_class($this) === get_class($other) && $this->uuid->equals($other->uuid);
    }
}