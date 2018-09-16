<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups as Groups;
/**
 * @ORM\Embeddable
 */
class Money
{
    /**
     * @var string $currencyCode
     * @ORM\Column
     */
    private $currencyCode;

    /**
     * @var string amount
     * @ORM\Column
     */
    private $amount;

    public function getBar() : string
    {
        return $this->bar;
    }

    public function setBar(string $bar)
    {
        $this->bar = $bar;
    }
}