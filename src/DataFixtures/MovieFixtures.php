<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ActorFixtures;
use App\Service\CallApiService;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MovieFixtures extends Fixture
{
    private $slugify;
    private $callApiService;
    public const TITLES = [
        'The Man From Earth',
        'Drive',
        'Scarface',
        'Interstellar',
        'Fracture',
        'The Game',
        'Usual Suspects',
    ];

    public function __construct(Slugify $slugify, CallApiService $callApiService)
    {
        // Normal dependency injection in the constructor
        $this->slugify = $slugify;

        $this->callApiService = $callApiService;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::TITLES as $title) {
            $movie = new Movie();
            $movie->setTitle($title);
            $movie->setSlug($this->slugify->generate($movie->getTitle()));
            $movie->setYear(substr($this->callApiService->getMovieData($movie)['release_date'], 0, 4));
            $movie->setSynopsis($this->callApiService->getMovieData($movie)['overview']);
            $movie->setPoster($this->callApiService->getMovieData($movie)['poster_path']);
            $movie->setBackground($this->callApiService->getMovieData($movie)['backdrop_path']);
            $movie->setPopularity($this->callApiService->getMovieData($movie)['vote_average'] * 10);
            $movie->setMyRate(4.5);
            $movie->setMyReview('Un classique à regarder à tout prix !');
            $movie->setCategory($this->getReference('category_0'));
            $movie->addActor($this->getReference('actor_1'));

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
