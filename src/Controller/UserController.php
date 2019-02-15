<?php

namespace App\Controller;

use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/admin/users/delete/{id}", name="user_admin_delete")
     */
    public function deleteUser(UserManager $userManager, EntityManagerInterface $entityManager, int $id)
    {

        $users = $userManager->getOne($id);
        if ($users !== null) {
            $entityManager->remove($users);
            $entityManager->flush();

            $this->addFlash('success', 'success');

            return $this->redirectToRoute('user_admin_list');

        }

        $this->addFlash('error', 'This user doesn\'t exist');
        return $this->redirectToRoute('user_admin_list');
    }

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
