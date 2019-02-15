<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        // Conference Fixtures
        for ($i = 0; $i <= 5; $i++) {
            $conference = new Conference();
            $conference->setName($i . ' ' . $this->getRandomWord(10));
            $conference->setDescription($this->getRandomWord());
            $manager->persist($conference);

        }

        // Admin fixtures
        $user = new User();
        $user->setEmail('admin@mail.com');
        $user->setFirstname('admin');
        $user->setLastname('admin');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'test'
        ));

        $manager->persist($user);

        // User fixtures
        for ($i = 0; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail($this->getRandomWord(10) . '@' . $this->getRandomWord(5) . '.com');
            $user->setFirstname($this->getRandomWord(20));
            $user->setLastname($this->getRandomWord(20));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'the_new_password'
            ));

            $manager->persist($user);
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
            $str .= $letter[random_int(0, count($letter) - 1)];
        }
        return $str;
    }
}
