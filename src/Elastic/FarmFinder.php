<?php

declare(strict_types=1);

namespace App\Elastic;

use App\Geo\Point;
use Elastica\Query\BoolQuery;
use Elastica\Query\GeoBoundingBox;
use FOS\ElasticaBundle\Finder\FinderInterface;

class FarmFinder
{
    /**
     * @var FinderInterface
     */
    private $farmFinder;

    public function __construct(FinderInterface $farmFinder)
    {
        $this->farmFinder = $farmFinder;
    }

    public function findFarmsNearPoint(Point $point)
    {
        $boolQuery = new BoolQuery();

        $boundingBox = new GeoBoundingBox('location', [
            "51.598294,-0.994532",
            "51.598291,-0.974532",
        ]);

        $boolQuery->addMust($boundingBox);

        $results = $this->farmFinder->find($boolQuery);
        return $results;
    }
}