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
        $domain->setDescription('L\'Espace Diamant est un grand domaine skiable regroupant cinq stations et villages de sports d\'hiver français, situé dans les départements de la Savoie et de la Haute-Savoie en région Auvergne-Rhône-Alpes, dans les Alpes françaises.');

        $manager->persist($domain);
        $manager->flush();


        // let's generate 5 specific stations for the domain ('Espace Diamant')
        $domains = $manager->getRepository(Domain::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        $weathers = ['sunny', 'snowy', 'cloudy', 'rainy', 'foggy'];


        foreach ($domains as $domain) {
            for ($i = 1; $i <= 5; $i++) {
                $station = new Station();
                $station->setOwner($users[rand(1, count($users) - 1)]);
                // on commence à 1 (et non 0) pour éliminer le super user de la liste des owners possibles
                $station->setDomain($domain);
                if ($i == 1) {
                    $station->setName('Les Saisies');
                    $station->setDescription('La station de ski des Saisies est une station village à taille humaine, familiale et conviviale, qui a su conserver, au fil de son évolution, une architecture traditionnelle et harmonieuse au sein d\'une nature préservée. 
Surnommée par certain le "Tyrol Français" (pour la beauté de ses paysages et la vue imprenable sur les massifs alentours et le Mont-Blanc) ou bien encore "le grenier à neige de la Savoie" (pour ses conditions d\'enneigement exceptionnelles), la station des Saisies est située à 40 minutes d\'Albertville, entre le Beaufortain et le Val d\'Arly, trait d\'union entre la Savoie et la Haute Savoie.
Le domaine d\'un naturel plutôt facile aux abords même de la station des Saisies est idéal pour les enfants et débutants.');
                }
                elseif ($i == 2) {
                    $station->setName('Crest-Volant Cohennoz');
                    $station->setDescription('Située en Savoie, au cœur du Val d’Arly, la station de ski de Crest-Voland Cohennoz présente la particularité de rassembler deux station-villages blotties au pied des pistes de l’Espace Diamant et ses 192 km de pistes.
Les sœurs-voisines sont composées toutes deux, d’un bourg de tradition savoyarde respectivement perché à 1230 m d’altitude pour Crest-Voland et 935 m pour Cohennoz, et de nombreux hameaux : le Crest, le Tovat, les Panissats, le Cernix...
La station, qui plait aux particulièrement familles et aux skieurs en quête de simplicité et de convivialité, est exposée en versant sud de la moyenne vallée de l’Arly, face à la chaîne des Aravis et à l’emblématique Mont Charvin.');
                }
                elseif ($i == 3) {
                    $station->setName('Notre-Dame-de-Bellecombe');
                    $station->setDescription('Située en Savoie, considérée comme le berceau du Val d’Arly, blottie entre Mont-Blanc, Beaufortain et Aravis, la station de ski de Notre Dame de Bellecombe est un village au caractère à la fois familial et authentique où la nature est demeurée intacte et la culture locale préservée.
Très facilement accessible depuis Albertville (via l’autoroute A43 ou la ligne TGV), depuis Sallanches (via l’A40) ou bien encore depuis les aéroports de Genève (à 1 heure) et de Lyon St-Exupéry (à 1h45), Notre Dame de Bellecombe se situe à 1150 m d’altitude. L’ensemble des quartiers des Biolles, des Fontaines, de la Cour, du Bourgeaillet et des Alpages consitutue le coeur de la station. On y trouve l’ensemble des services (écoles de ski, caisses des remontées mécaniques) et commerces (boulangerie, pharmacie...) nécessaires à la bon déroulement d’un séjour au ski.
Plus haut, la station présente deux étages supérieurs avec caisses de remontées mécaniques, écoles de ski et quelques commerces à 1350 m (Notre Dame de Bellecombe Mont Rond) et à 1450 m.');
                }
                elseif ($i == 4) {
                    $station->setName('Praz-sur-Arly');
                    $station->setDescription('Située en Haute-Savoie, dans le Val d’Arly, entre Megève et Flumet, à deux pas du Mont-Blanc, du Beaufortain et des Aravis, la station de ski de Praz sur Arly est implantée à 1035 m d’altitude et connectée à l’Espace Diamant et ses 192 km de pistes. Célèbre pour ses vols en montgolfière et sa base majeure de l’aérostation de montagne, Praz sur Arly cultive également l’authenticité, son patrimoine montagnard et son esprit familial (station labélisée Famille Plus).
Le départ du domaine skiable de Praz sur Arly, entouré du Crêt du Midi (1884 m) et de Ban Rouge (1983 m), est voisin de celui de Flumet et de Notre Dame de Bellecombe, stations avec lesquelles Praz sur Arly partage le même espace de glisse l’Espace Diamant.');

                }
                else {
                    $station->setName('Flumet');
                    $station->setDescription('Située dans le département de la Savoie, au cœur du Haut Val d’Arly, la station-village de Flumet - Saint Nicolas La Chapelle, à l’architecture typiquement savoyarde, charme ceux qui viennent chercher calme et traditions montagnardes, au pied d’un grand domaine skiable : l’Espace Diamant et ses 192 km de pistes face au Mont-Blanc.
La station se compose d’un ancien bourg médiéval, capitale historique du Val d’Arly sur la route reliant Albertville à Chamonix : Flumet - Saint Nicolas La Chapelle, perché à 900 m d’altitude sur un éperon rocheux dominant deux torrents de montagne : l’Arly et l’Arrondine.
Au pied du Mont Charvin, les centaines de chalets éparpillés autour du clocher à bulbe de Saint-Nicolas, confèrent aux lieux un esprit d’authenticité montagnarde préservée. À quelques encablures de là se trouvent deux hameaux touristiques reliés par navettes régulières gratuites et qui ouvrent sur le domaine skiable de l’Espace Diamant : Flumet - Les Seigneurs (1000 m d’altitude) et Flumet - Les Évettes (1030 m), dominés tous deux par le sommet omniprésent dit du « Gâteau ».');
                }
                $station->setWeather($weathers[rand(0, count($weathers) - 1)]);
                $manager->persist($station);
            }
            $manager->flush();
        }


        // let's generate slopes per station
        $stations = $manager->getRepository(Station::class)->findAll();

        foreach ($stations as $station) {
            for ($i = 1; $i <= rand(10, 20); $i++) {
                $randomDuration = "00:" . rand(1, 9) . ":" . rand(0, 59);
                $slope = new Slope();
                $slope->setStation($station);
                $slope->setName('Slope ' . $i);
                $slope->setDifficulty(rand(1, 4)); // green(1), blue(2), red(3), black(4)
                $slope->setFirstHour(new \DateTime('10:00'));
                $slope->setLastHour(new \DateTime('18:00'));
                $slope->setDuration(new \DateTime($randomDuration));
                $slope->setPeakHour(new \DateTime('12:00'));
                $slope->setSnowQuality(rand(1, 5)); // poor(1), bad(2), average(3), good(4), excellent(5)
                
                $manager->persist($slope);
            }
            $manager->flush();
        }

        // let's generate lifts per station as well
        $liftTypes = ['chairlift', 'gondola', 'drag lift'];

        foreach ($stations as $station) {
            for ($i = 1; $i <= rand(5, 10); $i++) {
                $randomDuration = "00:" . rand(1, 9) . ":" . rand(0, 59);
                $lift = new Lift();
                $lift->setStation($station);
                $lift->setName('Lift n°' . $i);
                $lift->setFirstHour(new \DateTime('10:00'));
                $lift->setLastHour(new \DateTime('17:30'));
                $lift->setDuration(new \DateTime($randomDuration));
                $lift->setType($liftTypes[rand(0, count($liftTypes) - 1)]);
                $lift->setPeakHour(new \DateTime('12:30'));
                $lift->setComfort(rand(1, 5)); // poor(1), bad(2), average(3), good(4), excellent(5)
                $manager->persist($lift);
            }
            $manager->flush();
        }
    }
}
