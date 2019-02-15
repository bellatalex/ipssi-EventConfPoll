<?php
/**
 * Created by PhpStorm.
 * User: alexg78bis
 * Date: 2019-02-14
 * Time: 09:58
 */

namespace App\Manager;


use App\Entity\Conference;
use App\Entity\Stars;
use App\Repository\ConferenceRepository;
use App\Repository\StarsRepository;

class ConferenceManager
{
    private $conferenceRepository;
    private $starsRepository;

    public function __construct(ConferenceRepository $conferenceRepository, StarsRepository $starsRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
        $this->starsRepository = $starsRepository;
    }

    public function getAll(): ?array
    {
        return $this->conferenceRepository->findAll();
    }

    public function getById(int $id): ?Conference
    {
        return $this->conferenceRepository->find($id);
    }
    public function findBySearch(string $search)
    {
        return $this->conferenceRepository->findBySearch($search);
    }
    public function findTopConf()
    {
        return $this->starsRepository->findTopConf();
    }
}