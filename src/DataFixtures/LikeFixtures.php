<?php
// src/DataFixtures/FakerFixtures.php
namespace App\DataFixtures;

use App\Entity\Like;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LikeFixtures extends Fixture
{
    /*public function randUser(Like $em)
    {
        $em->getId();
        return $em;
    }*/
    public function load(ObjectManager $manager)
    {

        // on créé 10 personnes
        for ($i = 0; $i < 10; $i++) {
            $like = new Like();
            $like->setEvent($i);
            $like->setNote(rand (1,5));
            $like->setUser($i);
            $manager->persist($like);
        }

        $manager->flush();
    }
}