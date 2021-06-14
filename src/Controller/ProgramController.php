<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use App\Service\EpisodePicker;
use App\Service\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows of Program's entity
     * 
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            [
                'programs' => $programs
            ]
        );
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_to'))
                ->subject('Nouvelle série publiée');
            $email->html($this->renderView('Program/newEmail.html.twig', ['program' => $program, 'email' => $email]));
            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Getting a program by slug
     * 
     * @Route("/{program}", methods={"GET"}, name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "slug"}})
     * @return Response
     */
    public function show(Program $program): Response
    {
        return $this->render(
            'program/show.html.twig',
            [
                'program' => $program,
            ]
        );
    }

    /**
     * Get a specific season of a program
     * 
     * @Route("/season/{season<^[0-9]+$>}", methods={"GET"}, name="season_show")
     * @return Response
     */
    public function showSeason(Season $season)
    {
        return $this->render('program/season_show.html.twig', [
            'season' => $season,
        ]);
    }

    /**
     * Get a specific episode of a program
     * 
     * @Route("/season/episode/{episode}", methods={"GET"}, name="episode_show")
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode": "slug"}})
     * @return Response
     */
    public function showEpisode(Episode $episode, EpisodePicker $epPick)
    {
        return $this->render('program/episode_show.html.twig', [
            'episode' => $episode,
            'prev' => $epPick->getPrevEpisode($episode, $this->getDoctrine()),
            'next' => $epPick->getNextEpisode($episode, $this->getDoctrine()),
            'extra' => $epPick->getRandomEpisode($episode, $this->getDoctrine()),
        ]);
    }
}
