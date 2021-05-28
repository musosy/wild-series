<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Season;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $programs = [];
        $i = 1;
        while ($this->hasReference('program' .$i)) {
            $programs[] = $this->getReference('program' .$i);
            $i++;
        }
        $seasonCounter = 1;
        foreach ($programs as $key => $program) {
            for ($j = 1; $j <= 3; $j++) {
                $season = new Season();
                $season->setNumber($j);
                $season->setYear($j+2010);
                $season->setDescription('Season #' .$j. ' description.');
                $season->setProgram($program);
                $this->addReference('season' .$seasonCounter, $season);
                $seasonCounter++;
                $manager->persist($season);
            }
        }
        $manager->flush();   
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
