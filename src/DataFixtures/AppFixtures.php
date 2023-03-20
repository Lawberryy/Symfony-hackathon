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
        // let's generate 20 users
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setFirstname('firstname' . $i);
            $user->setLastname('lastname' . $i);
            $user->setEmail($user->getFirstname() . '.' . $user->getLastname() . '@gmail.com');
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'vivelamontagne')
            );
            if ($i === 1) {
                $user->setRoles(['ROLE_SU', 'ROLE_ADMIN', 'ROLE_USER']); // ROLE_SU = admin global
            } else {
                $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
            }

            $manager->persist($user);
        }

        $manager->flush();

        // let's generate a domain
        $users = $manager->getRepository(User::class)->findAll();
        $randKey = rand(0, count($users) - 1);

        $domain = new Domain();
        $domain->setOwner($users[$randKey]);
        $domain->setName('Espace Diamant');
        $domain->setDescription('LEspace Diamant est un grand domaine skiable regroupant cinq stations et 
        villages de sports dhiver français, situé dans les départements de la Savoie et de la Haute-Savoie en région 
        Auvergne-Rhône-Alpes, dans les Alpes françaises.');
        // $domain->setIconUrl();

        $manager->persist($domain);
        $manager->flush();

        // let's generate 5 stations per domain (owned by one user)
        $domains = $manager->getRepository(Domain::class)->findAll();

        foreach ($users as $user) {
            foreach ($domains as $domain) {
                for ($i = 1; $i <= 5; $i++) {
                    $station = new Station();
                    $station->setOwner($user);
                    $station->setDomain($domain);
                    $station->setName('Station ' . $i);
                    $station->setDescription('Description de ' . $station->getName());
                    // $station->setIconUrl();
                    $manager->persist($station);
                }
            }
            $manager->flush();
        }

        // let's generate slopes per station
        $stations = $manager->getRepository(Station::class)->findAll();

        foreach ($stations as $station) {
            for ($i = 1; $i <= rand(10, 100); $i++) {
                $slope = new Slope();
                $slope->setStation($station);
                $slope->setName('Slope ' . $i);
                $slope->setDifficulty(rand(1, 4)); // green(1), blue(2), red(3), black(4)
                $slope->setFirstHour('10:00');
                $slope->setLastHour('18:00');
                // $slope->setException();
                // $slope->setExceptionMessage('');
                $manager->persist($slope);
            }
            $manager->flush();
        }

        // let's generate lifts per station as well
        foreach ($stations as $station) {
            for ($i = 1; $i <= rand(10, 50); $i++) {
                $lift = new Lift();
                $lift->setStation($station);
                $lift->setName('Lift n°' . $i);
                $lift->setFirstHour('10:00');
                $lift->setLastHour('17:30');
                // $lift->setException();
                // $lift->setExceptionMessage('');
                $manager->persist($lift);
            }
            $manager->flush();
        }
    }
}
