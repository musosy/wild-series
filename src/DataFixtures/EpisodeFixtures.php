<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\SeasonFixtures;
use App\Entity\Season;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $seasons = [];
        $i = 1;
        while ($this->hasReference('season' .$i)) {
            $seasons[] = $this->getReference('season' .$i);
            $i++;
        }
        foreach ($seasons as $key => $season) {
            for ($j = 1; $j <= 10; $j++) {
                $episode = new Episode();
                $episode->setTitle('Episode #' .$j. ' title.');
                $episode->setNumber($j);
                $episode->setSynopsis('Episode #' .$j. ' synopsis.');
                $episode->setSeason($season);
                $manager->persist($episode);
            }
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
