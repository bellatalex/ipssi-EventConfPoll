<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // User fixtures
        for ($i = 0; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail(getRandomWord(10) . '@' . getRandomWord(5) . '.com');

        }


        $manager->flush();
    }

    function getRandomWord(int $maxLong = 250): string
    {
        $letter = [
            'a',
            'z',
            'e',
            'r',
            't',
            'y',
            'u',
            'i',
            'o',
            'p',
            'q',
            's',
            'd',
            'f',
            'g',
            'h',
            'j',
            'k',
            'l',
            'm',
            'w',
            'x',
            'c',
            'v',
            'b',
            'n'
        ];
        $str = '';
        for ($i = 0; $i <= $maxLong; $i++) {
            $str .= $letter[rand(0, count($letter) - 1)];
        }
        return $str;
    }
}
