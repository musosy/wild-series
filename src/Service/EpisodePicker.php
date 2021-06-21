<?php

namespace App\Service;


use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EpisodePicker extends AbstractController
{
    static function getNextEpisode(Episode $episode, $doc)
    {
        return $doc->getRepository(Episode::class)
        ->findOneBy([
            'id' => ($episode->getId() + 1)
        ]);
    }
    static function getPrevEpisode(Episode $episode, $doc)
    {
        return $doc->getRepository(Episode::class)
        ->findOneBy([
            'id' => ($episode->getId() - 1)
        ]);
    }

    static function getRandomEpisode(Episode $episode, bool $program = true)
    {
        $programs = $episode->getSeason()
            ->getProgram()
            ->getCategory()
            ->getPrograms();
        
        //Get the current episode's program
        $prg = $episode->getSeason()->getProgram();

        //If asked changes it to a random prg of the same category
        $program ? $prg = $programs->get(array_rand($programs->toArray())) : false;

        //Get a random season of the new prg
        $sn = $prg->getSeasons()[array_rand($prg->getSeasons()->toArray())];
        //Get a random ep from the new sn
        $ep = $sn->getEpisodes()[array_rand($sn->getEpisodes()->toArray())];
        return $ep;
    }
}