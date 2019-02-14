<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceAddType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{

    /**
     * @Route("/admin/conferences/add", name="conference_admin_add")
     */
    public function addConference(EntityManagerInterface $entityManager,Request $request)
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceAddType::class, $conference);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conference);
            $entityManager->flush();
            $this->redirectToRoute('conference_admin_add'); // TODO: change route to list admin
        }

        return $this->render('security/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
