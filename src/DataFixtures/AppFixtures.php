<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $eggs = Product::makeSalable(Uuid::uuid4(), "egg", null, 6);
        $manager->persist($eggs);

        $milk = Product::makeSalable(Uuid::uuid4(), "milk", "ml", 500);
        $manager->persist($milk);

        $honey = Product::makeSalable(Uuid::uuid4(), "honey", "oz", 12);
        $manager->persist($honey);

        $hay = Product::makeSalable(Uuid::uuid4(), 'hay', 'bale', 1);
        $manager->persist($hay);

        $apples = Product::makeSalable(Uuid::uuid4(), 'apple');
        $manager->persist($apples);

        $manager->flush();
    }
}
