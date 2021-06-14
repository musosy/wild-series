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
        $i = 0;
        while ($this->hasReference('season_' . $i)) {
            for ($j = 1; $j <= 5; $j++) {
                $slugify = new Slugify();
                $episode = new Episode();
                $episode->setTitle('Episode ' .$j. ' title.');
                $episode->setNumber($j);
                $episode->setSynopsis('Episode ' .$j. ' synopsis.');
                $episode->setSeason($this->getReference('season_' . $i));
                $episode->setSlug($slugify->generate($episode->getTitle()));
                $manager->persist($episode);
            }
            $i++;
        }
        $i = 0;
        while ($this->hasReference('seasonB_' . $i)) {
            for ($j = 1; $j <= 5; $j++) {
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
