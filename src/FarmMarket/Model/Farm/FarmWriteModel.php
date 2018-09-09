<?php

namespace App\FarmMarket\Model\Farm;

use App\Entity\Image;
use App\Geo\Point;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FarmRepository")
 * @ORM\Table(name="farms")
 */
class FarmWriteModel extends \App\Entity\Farm
{
    public function setLocation(Point $point) {
        $this->location = $point;
    }
    public function setId(UuidInterface $id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPreviewImage(Image $image) {
        $this->previewImage = $image;
    }


    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

}