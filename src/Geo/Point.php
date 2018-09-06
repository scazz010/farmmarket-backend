<?php

declare(strict_types=1);

namespace App\Geo;

class Point
{
    /**
     * @var float
     */
    private $lat;
    /**
     * @var float
     */
    private $long;

    public function __construct(float $lat, float $long)
    {
        $this->lat = $lat;
        $this->long = $long;
    }

    /**
     * @return float
     */
    public function getLong(): float
    {
        return $this->long;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    public function __toString()
    {
        return sprintf("%s,%s", $this->lat,  $this->long);
    }


}