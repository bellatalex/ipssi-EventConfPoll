<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\Stars;
use App\Form\ConferenceAddType;
use App\Form\StarsType;
use App\Manager\ConferenceManager;
use App\Manager\StarsManager;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{

    /**
     * @Route("/admin/conferences/add", name="conference_admin_add")
     */
    public function adminAdd(EntityManagerInterface $entityManager, Request $request, Swift_Mailer $swift_Mailer, UserManager $userManager)
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceAddType::class, $conference);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conference);
            $entityManager->flush();

            $this->sendMail($conference->getName(), $swift_Mailer, $userManager->getAllUserEmail());

            return $this->redirectToRoute('conferences_admin_list');
        }

        return $this->render('security/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * List of conference with admin control
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
     * all Conference (public view)
     * @Route("/conferences/", name="conferences_list")
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

        if ($conference !== null) {
            $userAlreadyVote = $starsManager->didUserAlreadyVote($conference, $this->getUser());

            if (!$userAlreadyVote) {
                $stars = new Stars();
                $stars->setUser($this->getUser());
                $stars->setConference($conference);

                $form = $this->createForm(StarsType::class, $stars);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($stars);
                    $entityManager->flush();
                    return $this->redirectToRoute('conferences_list');
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

    /**
     * Conference notVoted
     * @Route("/conferences/notVoted/", name="conferences_notVoted")
     */
    public function notVotedConferences(ConferenceManager $conferenceManager)
    {
        // We get the id of all conferences
        $allConferencesId = [];
        foreach ($conferenceManager->getAll() as $conference) {
            $allConferencesId[] = $conference->getId();
        }

        // We get the id of all the conferences which are already voted
        $votedConferencesId = [];
        foreach ($this->getUser()->getStars() as $star) {
            $votedConferencesId[] = $star->getConference()->getId();
        }

        // We get the id of all the conference which aren't already voted by the user
        $conferenceToShow = array_diff($allConferencesId, $votedConferencesId);

        // We get the informaiton of those conferences
        $conferences = [];
        foreach ($conferenceToShow as $id) {
            $conferences[] = $conferenceManager->getById($id);
        }

        return $this->render('conference/list.html.twig', [
            'conferences' => $conferences,
        ]);
    }

    /**
     * Conference already voted
     * @Route("/conferences/voted/", name="conferences_voted")
     */
    public function votedConferences(ConferenceManager $conferenceManager)
    {

        // We get the id of all the conferences which are already voted
        $votedConferences = [];
        foreach ($this->getUser()->getStars() as $star) {
            $votedConferences[] = $star->getConference();
        }

        //

        return $this->render('conference/list.html.twig', [
            'conferences' => $votedConferences,
        ]);
    }

    /**
     * Search method for search bar
     * @param string $search
     * @Route("/conferences/search", name="searchBar")
     */
    public function searchbar(ConferenceManager $conferenceManager){
        $search = $_POST['search'];
        $conferences = $conferenceManager->findBySearch($search);

        return $this->render('conference/list.html.twig', [
            'conferences' => $conferences
        ]);
    }
    public function sendMail($name, \Swift_Mailer $mailer, array $userMails)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('do-not-reply@event-conf-poll.com')
            ->setBcc($userMails)
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            )/*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        ;
        $mailer->send($message);
    }

    /**
     * 10 Top Conferences
     * @Route("/conferences/topConf", name="topConf")
     */
    public function topConf(ConferenceManager $conferenceManager)
    {
        $conferences = $conferenceManager->findTopConf();

        return $this->render('conference/list.html.twig', [
            'conferences' => $conferences
        ]);
    }

    /**
     * @Route("/amdin/conferences/deleteAll", name="deleteAll")
     */
    public function deleteAll(
        ConferenceManager $conferenceManager,
        EntityManagerInterface $entityManager,
        StarsManager $starsManager
    ) {
        $conferences = $conferenceManager->getAll();
        foreach ($conferences as $item) {
            $entityManager->remove($item);
            $entityManager->flush();
        }


        $stars = $starsManager->getAll();
        foreach ($stars as $item) {
            $entityManager->remove($item);
            $entityManager->flush();
        }
        return $this->redirectToRoute("index");
    }

}
