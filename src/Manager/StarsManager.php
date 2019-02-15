<?php

namespace App\Manager;

use App\Entity\Conference;
use App\Entity\User;
use App\Repository\StarsRepository;

class StarsManager
{
    private $starsRepository;

    public function __construct(StarsRepository $starsRepository)
    {
        $this->starsRepository = $starsRepository;
    }

    public function getAll(): ?array
    {
        return $this->starsRepository->findAll();
    }

    public function getLikeByConf(int $event): ?array
    {
        return $this->starsRepository->findBy(['event' => $event]);
    }

    public function didUserAlreadyVote(Conference $conference, User $user): bool
    {
        $stars = $this->starsRepository->findBy(['conference' => $conference->getId(), 'user' => $user->getId()]);

        return count($stars) >= 1;
    }

}