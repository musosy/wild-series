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
        $i = 0;
        while ($this->hasReference('program_' .$i)) {
            $programs[] = $this->getReference('program_' .$i);
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
                $this->addReference('season_' .$seasonCounter, $season);
                $seasonCounter++;
                $manager->persist($season);
            }
        }
        $i = 0;
        $totalSn = 0;
        while ($this->hasReference('programB_' . $i)) {
            for ($j = 0; $j < 3; $j++) {
                $season = new Season();
                $season->setNumber($j+1);
                $season->setYear($j+2010);
                $season->setDescription('Season #' .($j+1). ' description.');
                $season->setProgram($this->getReference('programB_' . $i));
                $this->addReference('seasonB_' .$totalSn, $season);
                $totalSn++;
                $manager->persist($season);
            }
            $i++;
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
