<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 15/02/2019
 * Time: 14:35
 */

namespace App\Manager;


use App\Entity\User;
use App\Repository\UserRepository;

class UserManager
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll(): ?array
    {
        return $this->userRepository->findAll();
    }

    public function getOne(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getAllUserEmail() :?array{
        $usersEmails = [];
        foreach ($this->getAll() as $user){
            $usersEmails[] = $user->getEmail();
        }

        return $usersEmails;
    }


}

