<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;

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
     * Getting a program by id
     * 
     * @Route("/{id<^[0-9]+$>}", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(int $id): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);
        $seasons = $program->getSeasons();
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id: ' .$id. ' found in program\'s table.'
            );
        }
        return $this->render(
            'program/show.html.twig',
            [
            'program' => $program,
            'seasons' => $seasons
        ]);
    }
    
    /**
     * Get a specific season of a program
     * 
     * @Route("/{id<^[0-9]+$>}/season/{seasonId<^[0-9]+$>}", methods={"GET"}, name="season_show")
     * @return Response
     */
    public function showSeason(int $id, int $seasonId)
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);
        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findOneBy(['id' => $seasonId]);
        $episodes = $season->getEpisodes();
        return $this->render('program/show_season.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes
        ]);
    }
}