<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Auth\Role;
use AppBundle\Entity\Auth\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures implements FixtureInterface, DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $roles = $manager->getRepository(Role::class)->findAll();

        for ($i = 0; $i < 50; ++$i) {
            $role = array_rand($roles);
            $email = $faker->safeEmail;

            $user = new User();
            $user->setUsername($email);
            $user->setEmail($email);
            $user->setSlug(strtok($email, '@'));
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName);
            $user->addRole($roles[$role]);

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
        ];
    }
}
