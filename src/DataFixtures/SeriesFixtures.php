<?php

namespace App\DataFixtures;

use App\Entity\Series;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 30; $i++) {
            $series = new Series();
            $series->setTitle('Série #' . $i);
            $series->setStartYear('2000');
            $series->setSynopsis('Synopsis de la série');
            $series->setWatchedOn(\DateTime::createFromFormat('d-m-Y', '25-12-2011'));
            $series->setPoster('https://fr.web.img5.acsta.net/c_310_420/pictures/19/12/12/12/13/2421997.jpg');
            $series->setTrailerLink('https://youtu.be/TJFVV2L8GKs');
            $series->setMyRate(4.2);
            $series->setPopularity(82);
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
