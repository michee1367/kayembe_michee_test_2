<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        
        $User = new User();
        $User->setName("Michee")
             ->setNumber(823281659)
             ->setPassword("12345");
             $manager->persist($User);
             

        $manager->flush();
    }
}
