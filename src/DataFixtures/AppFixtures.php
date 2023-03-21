<?php

namespace App\DataFixtures;

use App\Entity\Domain;
use App\Entity\Lift;
use App\Entity\Slope;
use App\Entity\Station;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        // let's generate a "super user" = super admin
        $super_user = new User();
        $super_user->setRoles(['ROLE_SU', 'ROLE_ADMIN', 'ROLE_USER']);
        $super_user->setFirstname('Super');
        $super_user->setLastname('User');
        $super_user->setEmail($super_user->getFirstname() . '.' . $super_user->getLastname() . '@gmail.com');
        $super_user->setPassword(
            $this->passwordHasher->hashPassword($super_user, 'viveleski')
        );
        $manager->persist($super_user);
        $manager->flush();

        // let's generate 9 other users
        for ($i = 1; $i <= 9; $i++) {
            $user = new User();
            $user->setFirstname('firstname' . $i);
            $user->setLastname('lastname' . $i);
            $user->setEmail($user->getFirstname() . '.' . $user->getLastname() . '@gmail.com');
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'vivelamontagne')
            );
            $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

            $manager->persist($user);
        }

        $manager->flush();

        // let's generate a domain

        $domain = new Domain();
        $domain->setOwner($super_user);
        $domain->setName('Espace Diamant');
        $domain->setDescription('L\'Espace Diamant est un grand domaine skiable regroupant cinq stations et 
        villages de sports d\'hiver français, situé dans les départements de la Savoie et de la Haute-Savoie en région 
        Auvergne-Rhône-Alpes, dans les Alpes françaises.');

        $manager->persist($domain);
        $manager->flush();

        // let's generate 5 stations per domain (owned by one user)
        $domains = $manager->getRepository(Domain::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        $randKey = rand(0, count($users) - 1);

            foreach ($domains as $domain) {
                for ($i = 1; $i <= 5; $i++) {
                    $station = new Station();
                    $station->setOwner($users[$randKey]);
                    $station->setDomain($domain);
                    $station->setName('Station ' . $i);
                    $station->setDescription('Description de ' . $station->getName());
                    $manager->persist($station);
                }
                $manager->flush();
            }

        // let's generate slopes per station
        $stations = $manager->getRepository(Station::class)->findAll();

        foreach ($stations as $station) {
            for ($i = 1; $i <= rand(10, 20); $i++) {
                $slope = new Slope();
                $slope->setStation($station);
                $slope->setName('Slope ' . $i);
                $slope->setDifficulty(rand(1, 4)); // green(1), blue(2), red(3), black(4)
                $slope->setFirstHour(new \DateTime('10:00'));
                $slope->setLastHour(new \DateTime('18:00'));
                $manager->persist($slope);
            }
            $manager->flush();
        }

        // let's generate lifts per station as well
        foreach ($stations as $station) {
            for ($i = 1; $i <= rand(5, 10); $i++) {
                $lift = new Lift();
                $lift->setStation($station);
                $lift->setName('Lift n°' . $i);
                $lift->setFirstHour(new \DateTime('10:00'));
                $lift->setLastHour(new \DateTime('17:30'));
                $manager->persist($lift);
            }
            $manager->flush();
        }
    }
}
