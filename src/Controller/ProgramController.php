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
    public function new(Request $request): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Getting a program by id
     * 
     * @Route("/{program<^[0-9]+$>}", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(Program $program): Response
    {
        return $this->render(
            'program/show.html.twig',
            [
            'program' => $program,
        ]);
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
     * @Route("/season/episode/{episode<^[0-9]+$>}", methods={"GET"}, name="episode_show")
     * @return Response
     */
    public function showEpisode(Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'episode' => $episode,
            'prev' => EpisodePicker::getPrevEpisode($episode, $this->getDoctrine()),
            'next' => EpisodePicker::getNextEpisode($episode, $this->getDoctrine()),
            'extra' => EpisodePicker::getRandomEpisode($episode, $this->getDoctrine()),
        ]);
    }
}