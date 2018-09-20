<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm\Command;

use App\FarmMarket\Model\Farm\FarmId;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

final class RegisterFarmToSellProduct extends Command
    implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(FarmId $farmId, string $productId)
    {
        return new self([
            'farm_id' => $farmId->toString(),
            'product_id' => $productId
        ]);
    }

    public function farmId(): FarmId
    {
        return FarmId::fromString($this->payload['farm_id']);
    }

    protected function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }
}
