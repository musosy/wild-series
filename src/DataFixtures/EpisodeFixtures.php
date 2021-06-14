<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\SeasonFixtures;
use App\Entity\Season;
use App\Service\Slugify;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $seasons = [];
        $i = 0;
        while ($this->hasReference('season_' .$i)) {
            $seasons[] = $this->getReference('season_' .$i);
            $i++;
        }
        foreach ($seasons as $key => $season) {
            for ($j = 1; $j <= 10; $j++) {
                $episode = new Episode();
                $episode->setTitle('Episode ' .$j. ' title.');
                $episode->setNumber($j);
                $episode->setSynopsis('Episode ' .$j. ' synopsis.');
                $episode->setSeason($season);
                $manager->persist($episode);
            }
        }
        $i = 0;
        while ($this->hasReference('seasonB_' . $i)) {
            for ($j = 1; $j <= 10; $j++) {
                $slugify = new Slugify();
                $episode = new Episode();
                $episode->setTitle('Episode ' .$j. ' title.');
                $episode->setNumber($j);
                $episode->setSynopsis('Episode ' .$j. ' synopsis.');
                $episode->setSeason($this->getReference('seasonB_' . $i));
                $episode->setSlug($slugify->generate($episode->getTitle()));
                $manager->persist($episode);
            }
            $i++;
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
