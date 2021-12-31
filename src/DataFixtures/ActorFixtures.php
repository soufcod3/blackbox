<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 4; $i++) {
            $actor = new Actor();
            $actor->setFirstName('Actor');
            $actor->setLastName('#' . $i);
            $actor->setPortrait('https://fr.web.img6.acsta.net/c_310_420/commons/v9/common/empty/empty_portrait.png');

            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
        }

        $manager->flush();
    }

}
