<?php

namespace App\DataFixtures;

use App\Entity\Series;
use App\DataFixtures\CategoryFixtures;
use App\Service\CallApiService;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeriesFixtures extends Fixture
{

    private $slugify;
    private $callApiService;
    public const TITLES = [
        'Breaking Bad',
        'Heroes',
        'Mindhunter',
        'Suits',
        'The Walking Dead',
        'My Name',
        'Squid Game',
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
            $series = new Series();
            $series->setTitle($title);
            $series->setSlug($this->slugify->generate($series->getTitle()));
            $series->setStartYear(substr($this->callApiService->getSeriesData($series)['first_air_date'], 0, 4));
            $series->setSynopsis($this->callApiService->getSeriesData($series)['overview']);
            $series->setPoster($this->callApiService->getSeriesData($series)['poster_path']);
            $series->setBackground($this->callApiService->getSeriesData($series)['backdrop_path']);
            $series->setPopularity($this->callApiService->getSeriesData($series)['vote_average'] * 10);
            $series->setMyRate(3.3);
            $series->setMyReview('J\'ai trouvé la série pas mal du tout');
            $series->setSeasonsCount(3);
            $series->setEpisodesCount(24);
            $series->setCategory($this->getReference('category_5'));
            $series->setAllocineLink('https://www.allocine.fr/series/ficheserie_gen_cserie=22146.html');

            $manager->persist($series);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Return here all fixtures classes which ProgramFixtures depends on
        return [
            CategoryFixtures::class,
        ];
    }
}
