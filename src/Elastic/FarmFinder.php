<?php

declare(strict_types=1);

namespace App\Elastic;

use App\Geo\Point;
use Elastica\Query\BoolQuery;
use Elastica\Query\GeoBoundingBox;
use Elastica\Query\GeoDistance;
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
        $disatncesToSearch = ["1km", "3km", "10km", "20km"];

        /* We are looking for 5 results, but we'll take 3 if the next size up doesn't get us to ideal */
        $idealNumberOfResults = 5;
        $minimumNumberOfResults = 3;

        $previousResults = null;

        foreach ($disatncesToSearch as $distance) {
            $boolQuery = new BoolQuery();
            $boolQuery->addMust(new GeoDistance('location', $point->__toString(), $distance));
            $results = $this->farmFinder->find($boolQuery);

            if (count($results) >= $idealNumberOfResults) {
                return $results;
            }

            /* We have enough results, and expanding our search didn't really get us anywhere */
            if (count($results) < $idealNumberOfResults && count($previousResults) >= $minimumNumberOfResults) {
                return $previousResults;
            }

            $previousResults = $results;
        }

        return $previousResults;
    }
}