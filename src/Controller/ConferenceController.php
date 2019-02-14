<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\Stars;
use App\Form\ConferenceAddType;
use App\Form\StarsType;
use App\Manager\ConferenceManager;
use App\Manager\StarsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{

    /**
     * @Route("/admin/conferences/add", name="conference_admin_add")
     */
    public function adminAdd(EntityManagerInterface $entityManager, Request $request)
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceAddType::class, $conference);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conference);
            $entityManager->flush();
            return $this->redirectToRoute('conferences_admin_list');
        }

        return $this->render('security/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Conference non notée
     * @Route("/admin/conferences/list", name="conferences_admin_list")
     */
    public function adminList(
        ConferenceManager $conferenceManager
    ) {
        $conferences = $conferenceManager->getAll();

        return $this->render('conference/list.html.twig', [
            'conferences' => $conferences,
            'edit' => true
        ]);
    }

    /**
     * edit a conference
     * @Route("/admin/conferences/edit/{id}", name="conferences_admin_edit")
     */
    public function adminEdit(
        EntityManagerInterface $entityManager,
        ConferenceManager $conferenceManager,
        Request $request,
        int $id
    ) {
        $conference = $conferenceManager->getById($id);
        if ($conference !== null) {

            $form = $this->createForm(ConferenceAddType::class, $conference);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager->persist($conference);
                $entityManager->flush();

                $this->addFlash('success', 'The conference has been edited');
                return $this->redirectToRoute('conferences_admin_list');
            }
            return $this->render('security/form.html.twig', [
                'form' => $form->createView()
            ]);

        }

        $this->addFlash('danger', 'This conference not exist.');
        return $this->redirectToRoute('conferences_admin_list');
    }

    /**
     * Delete a conference
     * @Route("/admin/conferences/delete/{id}", name="conferences_admin_delete")
     */
    public function adminDelete(
        EntityManagerInterface $entityManager,
        ConferenceManager $conferenceManager,
        int $id
    ) {
        $conference = $conferenceManager->getById($id);
        if ($conference !== null) {

            $entityManager->remove($conference);
            $entityManager->flush();
            $this->addFlash('success', 'This conference has been succefuly deleted.');
            return $this->redirectToRoute('conferences_admin_list');
        }

        $this->addFlash('danger', 'This conference not exist.');
        return $this->redirectToRoute('conferences_admin_list');
    }


    /**
     * Conference non notée
     * @Route("/conferences/", name="conferences_notLiked")
     */
    public function conferences(
        ConferenceManager $conferenceManager
    ) {
        $conferences = $conferenceManager->getAll();

        return $this->render('conference/list.html.twig', [
            'conferences' => $conferences
        ]);
    }

    /**
     * Conference vote
     * @Route("/conferences/vote/{id}", name="conferences_vote")
     */
    public function vote(
        ConferenceManager $conferenceManager,
        EntityManagerInterface $entityManager,
        StarsManager $starsManager,
        int $id,
        Request $request
    ) {
        $conference = $conferenceManager->getById($id);

        $userAlreadyVote = $starsManager->didUserAlreadyVote($conference, $this->getUser());

        if ($conference !== null) {
            if (!$userAlreadyVote) {
                $stars = new Stars();
                $stars->setUser($this->getUser());
                $stars->setEvent($conference);

                $form = $this->createForm(StarsType::class, $stars);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($stars);
                    $entityManager->flush();
                    return $this->redirectToRoute('conferences_admin_list');
                }

                return $this->render('conference/detail.html.twig', [
                    'form' => $form->createView(),
                    'conference' => $conference
                ]);
            }

            return $this->render('conference/detail.html.twig', [
                'conference' => $conference
            ]);
        }

        $this->addFlash('danger', 'This conference not exist.');
        return $this->redirectToRoute('conferences_admin_list');
    }


}
