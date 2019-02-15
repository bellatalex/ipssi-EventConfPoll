<?php

namespace App\Controller;

use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="user_admin_list")
     */
    public function getUsers(UserManager $userManager)
    {

        $users = $userManager->getAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
