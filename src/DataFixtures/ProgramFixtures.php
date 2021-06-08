<?php

namespace App\DataFixtures;

use App\Controller\CategoryController;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Program;
use App\Entity\Category;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $programs = [
            [
                'title' => "Walking Dead",
                'description' => "Le policier Rick Grimes se réveille après un long coma. Il découvre avec effarement que le monde, ravagé par une épidémie, est envahi par les morts-vivants.",
                'poster' => "https://m.media-amazon.com/images/M/MV5BZmFlMTA0MmUtNWVmOC00ZmE1LWFmMDYtZTJhYjJhNGVjYTU5XkEyXkFqcGdeQXVyMTAzMDM4MjM0._V1_.jpg",
            ],
            [
                'title' => "The Haunting Of Hill House",
                'description' => "Plusieurs frères et sœurs qui, enfants, ont grandi dans la demeure qui allait devenir la maison hantée la plus célèbre des États-Unis, sont contraints de se réunir pour finalement affronter les fantômes de leur passé.",
                'poster' => "https://m.media-amazon.com/images/M/MV5BMTU4NzA4MDEwNF5BMl5BanBnXkFtZTgwMTQxODYzNjM@._V1_SY1000_CR0,0,674,1000_AL_.jpg",
            ],
            [
                'title' => "American Horror Story",
                'description' => "A chaque saison, son histoire. American Horror Story nous embarque dans des récits à la fois poignants et cauchemardesques, mêlant la peur, le gore et le politiquement correct.",
                'poster' => "https://m.media-amazon.com/images/M/MV5BODZlYzc2ODYtYmQyZS00ZTM4LTk4ZDQtMTMyZDdhMDgzZTU0XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_SY1000_CR0,0,666,1000_AL_.jpg",
            ],
            [
                'title' => "Love Death And Robots",
                'description' => "Un yaourt susceptible, des soldats lycanthropes, des robots déchaînés, des monstres-poubelles, des chasseurs de primes cyborgs, des araignées extraterrestres et des démons assoiffés de sang : tout ce beau monde est réuni dans 18 courts métrages animés déconseillés aux âmes sensibles.",
                'poster' => "https://m.media-amazon.com/images/M/MV5BMTc1MjIyNDI3Nl5BMl5BanBnXkFtZTgwMjQ1OTI0NzM@._V1_SY1000_CR0,0,674,1000_AL_.jpg",
            ],
            [
                'title' => "Penny Dreadful",
                'description' => "Dans le Londres ancien, Vanessa Ives, une jeune femme puissante aux pouvoirs hypnotiques, allie ses forces à celles d Ethan, un garçon rebelle et violent aux allures de cowboy, et de Sir Malcolm, un vieil homme riche aux ressources inépuisables.  Ensemble, ils combattent un ennemi inconnu, presque invisible, qui ne semble pas humain et qui massacre la population.",
                'poster' => "https://m.media-amazon.com/images/M/MV5BNmE5MDE0ZmMtY2I5Mi00Y2RjLWJlYjMtODkxODQ5OWY1ODdkXkEyXkFqcGdeQXVyNjU2NjA5NjM@._V1_SY1000_CR0,0,695,1000_AL_.jpg",
            ],
            [
                'title' => "Fear The Walking Dead",
                'description' => "La série se déroule au tout début de l épidémie relatée dans la série-mère The Walking Dead et se passe dans la ville de Los Angeles, et non à Atlanta. Madison est conseillère dans un lycée de Los Angeles. Depuis la mort de son mari, elle élève seule ses deux enfants : Alicia, excellente élève qui découvre les premiers émois amoureux, et son grand frère Nick qui a quitté la fac et a sombré dans la drogue.",
                'poster' => "https://m.media-amazon.com/images/M/MV5BYWNmY2Y1NTgtYTExMS00NGUxLWIxYWQtMjU4MjNkZjZlZjQ3XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_SY1000_CR0,0,666,1000_AL_.jpg",
            ]
        ];
        foreach ($programs as $key => $data) {
            $program = new Program();
            $program->setTitle($data['title']);
            $program->setSummary($data['description']);
            $program->setPoster($data['poster']);
            $program->setCategory($this->getReference('category_4'));
            if ($program->getTitle() == 'Walking Dead') {
                $program->addActor($this->getReference('actor_0'));
                $program->addActor($this->getReference('actor_1'));
                $program->addActor($this->getReference('actor_2'));
                $program->addActor($this->getReference('actor_3'));
                $program->addActor($this->getReference('actor_4'));
            }
            $manager->persist($program);
            $this->addReference('program_' .$key, $program);
        }
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $program = new Program();
                $program->setTitle('testPrg_' . (10*$i+$j));
                $program->setSummary('testSmr_' . (10*$i+$j));
                $program->setPoster('testPst_' . (10*$i+$j));
                $program->setCategory($this->getReference('category_' . $i));
                $manager->persist($program);
                $this->addReference('programB_' .(10*$i+$j), $program);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            ActorFixtures::class,
        ];
    }
}
