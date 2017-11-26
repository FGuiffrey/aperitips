<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Auth\User;
use AppBundle\Entity\Event;
use AppBundle\Entity\Subject;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class SubjectFixtures implements FixtureInterface, DependentFixtureInterface
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
        $events = $manager->getRepository(Event::class)->findAll();

        // Accepted subjects
        for ($i = 0; $i < (count($events) * 2); ++$i) {
            $event = array_rand($events);

            $subject = new Subject();
            $subject->setName($faker->text(100));
            $subject->setSlug($faker->slug(10));
            $subject->setContent($faker->text(1000));
            $subject->setStatus(Subject::STATUS_ACCEPTED);
            $subject->setEvent($events[$event]);

            for ($j = 0; $j < rand(1, 2); ++$j) {
                $user = array_rand($users);
                $subject->addSpeaker($users[$user]);
            }

            for ($k = 0; $k < 20; ++$k) {
                $user = array_rand($users);
                $subject->addVote($users[$user]);
            }

            $manager->persist($subject);
        }

        $manager->flush();

        // Pending subjects
        for ($i = 0; $i < 10; ++$i) {
            $subject = new Subject();
            $subject->setName($faker->text(100));
            $subject->setSlug($faker->slug(10));
            $subject->setContent($faker->text(1000));
            $subject->setStatus(Subject::STATUS_PENDING);

            for ($j = 0; $j < rand(1, 2); ++$j) {
                $user = array_rand($users);
                $subject->addSpeaker($users[$user]);
            }

            for ($k = 0; $k < 20; ++$k) {
                $user = array_rand($users);
                $subject->addVote($users[$user]);
            }

            $manager->persist($subject);
        }

        $manager->flush();

        // Rejected subjects
        for ($i = 0; $i < 10; ++$i) {
            $subject = new Subject();
            $subject->setName($faker->text(100));
            $subject->setSlug($faker->slug(10));
            $subject->setContent($faker->text(1000));
            $subject->setStatus(Subject::STATUS_REJECTED);
            $subject->setReason($faker->text(100));

            for ($j = 0; $j < rand(1, 2); ++$j) {
                $user = array_rand($users);
                $subject->addSpeaker($users[$user]);
            }

            for ($k = 0; $k < 20; ++$k) {
                $user = array_rand($users);
                $subject->addVote($users[$user]);
            }

            $manager->persist($subject);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            EventFixtures::class,
        ];
    }
}
