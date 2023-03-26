<?php

namespace App\Controller;


use App\Entity\Slope;
use App\Form\SlopeType;
use App\Repository\SlopeRepository;
use App\Entity\Lift;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/slope')]
class SlopeController extends AbstractController
{

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'app_slope_show')]
    public function show(Slope $slope): Response{


        $heureActuelle = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $heureOuverture = \DateTime::createFromFormat('H:i:s', $slope->getFirstHour()->format('H:i:s'));
        $heureFermeture = \DateTime::createFromFormat('H:i:s', $slope->getLastHour()->format('H:i:s'));

        if ($heureActuelle < $heureOuverture){
            $diff = $heureOuverture->diff($heureActuelle);
            $tempsRestant = $diff->format('%H heures %i minutes');
            $messageTxt = "La piste est fermée pour le moment. Elle ouvrira dans " ;
            $messageValue =  $tempsRestant;

        } elseif ($heureActuelle <= $heureFermeture){
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


        $station_id = $slope->getStation()->getId();

        return $this->render('slope/show.html.twig', [
            'slope' => $slope,
            'message' => $messageTxt,
            "messageValue" => $messageValue,
            'station_id' => $station_id,
            'exception_message'=> $slope->getExceptionMessage(),

        ]);
    }
}
