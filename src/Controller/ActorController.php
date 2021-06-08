<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Actor;

/**
 * @Route("/actors", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();
        return $this->render('actor/index.html.twig', [
            'actors' => $actors,
        ]);
    }

    /**
     * Getting an actor by id
     * 
     * @Route("/{actor<^[0-9]+$>}", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(Actor $actor): Response
    {
        return $this->render(
            'actor/show.html.twig',
            [
            'actor' => $actor,
        ]);
    }
}
