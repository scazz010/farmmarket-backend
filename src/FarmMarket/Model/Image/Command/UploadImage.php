<?php

declare(strict_types=1);

namespace App\FarmMarket\Model\Image\Command;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

use App\FarmMarket\Model\EmailAddress;
use App\FarmMarket\Model\Farm\FarmId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zend\Validator\EmailAddress as EmailAddressValidator;

final class UploadImage extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(Uuid $imageId, UploadedFile $image) : UploadImage
    {
        return new self([
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

    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'image_id');
        Assertion::uuid($payload['image_id']);

        Assertion::keyExists($payload, 'image');
        /** @var UploadedFile $file */
        $file = $payload['image'];
        Assertion::startsWith($file->getMimeType(), 'image');

        $this->payload = $payload;
    }
}