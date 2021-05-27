<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('program/index.html.twig', [
            'website' => "Wild Séries",
        ]);
    }

    /**
     * @Route("/{page<\d+>}", methods={"GET"}, name="show")
     */
    public function show(int $page = 1): Response
    {
        return $this->render('program/show.html.twig', [
            'website' => "Wild Séries",
            'page' => $page
        ]);
    }
}