<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Farm\Command;

use App\FarmMarket\Model\Farm\FarmId;
use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UpdateFarmPreviewImage extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(string $farmId, Uuid $imageId, UploadedFile $image) : UpdateFarmPreviewImage
    {
        return new self([
            'farm_id' => $farmId,
            'image_id' => $imageId,
            'image' => $image
        ]);
    }

    public function imageId() : Uuid
    {
        return $this->payload['image_id'];
    }

    public function image() : UploadedFile
    {
        return $this->payload['image'];
    }

    public function farmId(): FarmId
    {
        return FarmId::fromString($this->payload['farm_id']);
    }

    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'farm_id');
        Assertion::uuid($payload['farm_id']);

        Assertion::keyExists($payload, 'image_id');
        Assertion::uuid($payload['image_id']);

        Assertion::keyExists($payload, 'image');
        /** @var UploadedFile $file */
        $file = $payload['image'];
        Assertion::startsWith($file->getMimeType(), 'image');

        $this->payload = $payload;
    }
}