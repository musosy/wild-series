<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    //! Annotations not working -> gives error
    //TODO configure routing with yaml for now
    
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'website' => "Wild Séries",
        ]);
    }
}