<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ActorFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 30; $i++) {
            $movie = new Movie();
            $movie->setTitle('Film #' . $i);
            $movie->setYear('2001');
            $movie->setSynopsis('Synopsis du film');
            $movie->setWatchedOn(\DateTime::createFromFormat('d-m-Y', '25-12-2011'));
            $movie->setPoster('https://fr.web.img6.acsta.net/c_310_420/medias/nmedia/18/68/61/99/19425646.jpg');
            $movie->setTrailerLink('https://youtu.be/DXPJqRtkDP0');
            $movie->setMyRate(4.5);
            $movie->setPopularity(93);
            $movie->setMyReview('Un classique à regarder à tout prix !');
            $movie->setCategory($this->getReference('category_0'));

            $manager->persist($movie);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Return here all fixtures classes which ProgramFixtures depends on
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
