<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Auth\User;
use AppBundle\Entity\Event;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class EventFixtures implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();

        for ($i = 0; $i < 12; ++$i) {
            $user = array_rand($users);

            $event = new Event();
            $event->setScheduledAt($faker->dateTimeThisYear('2018-01-01 00:00:00'));
            $event->setAuthor($users[$user]);

            for ($j = 0; $j < 30; ++$j) {
                $user = array_rand($users);
                $event->addRegistered($users[$user]);
            }

            $manager->persist($event);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
