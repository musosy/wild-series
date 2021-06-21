<?php

namespace App\Controller;

use App\Entity\Comment;
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
use App\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Finder\Exception\AccessDeniedException;
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
            $program->setOwner($this->getUser());
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
     * @Route("/{program}/edit", methods={"GET", "POST"}, name="program_edit")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "slug"}} )
     * @return Response
    */
    public function edit(Program $program, Request $request)
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!($this->getUser() == $program->getOwner())) {
                throw new AccessDeniedException('Only the owner can edit the program!');
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /** 
     * @Route("/{program}/{season}/{episode}/{comment}/edit", methods={"GET", "POST"}, name="comment_edit")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "slug"}} )
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season": "id"}} )
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode": "slug"}} )
     * @ParamConverter("comment", class="App\Entity\Comment", options={"mapping": {"comment": "id"}} )
     * @return Response
    */
    public function commentEdit(Program $program, Season $season, Episode $episode, Comment $comment, Request $request)
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/comment_edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/{program}/{season}/{episode}/{comment}/delete", methods={"GET", "POST"}, name="comment_delete")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "slug"}} )
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season": "id"}} )
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode": "slug"}} )
     * @ParamConverter("comment", class="App\Entity\Comment", options={"mapping": {"comment": "id"}} )
     * @return Response
     */
    public function commentDelete(Program $program, Season $season, Episode $episode, Comment $comment, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_index');
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
     * @Route("/{program}/{season}", methods={"GET"}, name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season": "id"}})
     * @return Response
     */
    public function showSeason(Program $program, Season $season)
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * Get a specific episode of a program
     * 
     * @Route("/{program}/{season}/{episode}", methods={"GET", "POST"}, name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode": "slug"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode, EpisodePicker $epPick, Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setUser($this->getUser());
            $comment->setEpisode($episode);
            $entityManager->persist($comment);
            $entityManager->flush();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);
        }
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'extra' => $epPick->getRandomEpisode($episode, $this->getDoctrine()),
            'form' => $form->createView(),
        ]);
    }
}
