<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StationRepository;
use App\Repository\LiftRepository;
use App\Entity\Lift;
use DateTime;
use DateTimeZone;
use DateInterval;

class LiftController extends AbstractController
{
    #[Route('/lift', name: 'app_lift')]
    public function index(): Response
    {
        return $this->render('lift/index.html.twig', [
            'controller_name' => 'LiftController',
        ]);
    }

    #[Route('/lift/{id}', name: 'app_lift_show')]
    public function show(Lift $lift): Response{


        $heureActuelle = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $heureOuverture = DateTime::createFromFormat('H:i:s', $lift->getFirstHour()->format('H:i:s'));
        $heureFermeture = DateTime::createFromFormat('H:i:s', $lift->getLastHour()->format('H:i:s'));

        if ($heureActuelle < $heureOuverture){
            $diff = $heureOuverture->diff($heureActuelle);
            $tempsRestant = $diff->format('%H heures %i minutes');
            $messageTxt = "La piste est fermée pour le moment. Elle ouvrira dans " ;
            $messageValue =  $tempsRestant;

        } elseif ($heureActuelle >= $heureOuverture && $heureActuelle <= $heureFermeture){
            $diff = $heureFermeture->diff($heureActuelle);
            $tempsRestant = $diff->format('%H heures %i minutes');
            $messageTxt = "La piste est ouverte. Elle fermera dans " ;

            $messageValue =  $tempsRestant;
        } else {
            $diff = $heureActuelle->diff($heureFermeture);
            $tempsRestant = $diff->format('%H heures %i minutes');
            $messageTxt = "La piste est fermée depuis " ;

            $messageValue =  $tempsRestant;
        }


        $heureTest = $heureActuelle->format('%H heures %i minutes');
        $station_id = $lift->getStation()->getId();

        return $this->render('lift/show.html.twig', [
            'lift' => $lift,
            'message' => $messageTxt,
            "messageValue" => $messageValue,
            'station_id' => $station_id,
            'exception_message'=> $lift->getExceptionMessage(),

        ]);
    }
}