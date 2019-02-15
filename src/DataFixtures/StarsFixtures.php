<?php
// src/DataFixtures/FakerFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StarsFixtures extends Fixture
{
    /*public function randUser(Stars $em)
    {
        $em->getId();
        return $em;
    }*/
    public function load(ObjectManager $manager)
    {

    }
}