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
        $i = 0;
        $totalSn = 0;
        while ($this->hasReference('program_' . $i)) {
            for ($j = 0; $j < 3; $j++) {
                $season = new Season();
                $season->setNumber($j+1);
                $season->setYear($j+2010);
                $season->setDescription('Season #' .($j+1). ' description.');
                $season->setProgram($this->getReference('program_' . $i));
                $this->addReference('season_' .$totalSn, $season);
                $totalSn++;
                $manager->persist($season);
            }
            $i++;
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
