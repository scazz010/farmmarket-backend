<?php
namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Ramsey\Uuid\UuidInterface;

class Unit extends \Codeception\Module
{
    public function haveAFarmInDatabase(UuidInterface $uuid)
    {
    }
}
