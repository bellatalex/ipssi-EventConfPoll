<?php

namespace App\Manager;
use App\Entity\Like;
use App\Repository\LikeRepository;
class LikeManager
{
    private $likeRepository;
    public function __construct(LikeRepository $likeRepository)
   {
       $this->likeRepository = $likeRepository;
   }
    public function getLikeByUser(int $user): ?Like
    {
        return $this->likeRepository->findBy(['user'=>$user]);
    }
    public function getLikeByConf(int $event): ?array
    {
        return $this->likeRepository->findBy(['event'=>$event]);
    }

}