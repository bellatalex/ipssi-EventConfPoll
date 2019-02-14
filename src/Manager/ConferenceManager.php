<?php
/**
 * Created by PhpStorm.
 * User: alexg78bis
 * Date: 2019-02-14
 * Time: 09:58
 */

namespace App\Manager;


use App\Entity\Conference;
use App\Repository\ConferenceRepository;

class ConferenceManager
{
    private $conferenceRepository;

    public function __construct(ConferenceRepository $conferenceRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
    }

    public function getAll(): ?array
    {
        return $this->conferenceRepository->findAll();
    }

    public function getById(int $id): ?Conference
    {
        return $this->conferenceRepository->find($id);
    }

}