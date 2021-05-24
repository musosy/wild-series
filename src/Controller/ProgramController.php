<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProgramController extends AbstractController
{
    //! Annotations not working -> gives error
    //TODO configure routing with yaml for now
    
    public function index(): Response
    {
        return $this->render('program/index.html.twig', [
            'website' => "Wild Séries",
        ]);
    }
}