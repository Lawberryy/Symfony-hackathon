<?php

namespace App\Controller;

use App\Entity\LinkTrail;
use App\Entity\Trail;
use App\Controller\SecurityController;
use App\Repository\LiftRepository;
use App\Repository\SlopeRepository;
use App\Repository\TrailRepository;
use App\Repository\StationRepository;
use App\Repository\LinkTrailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrailController extends AbstractController
{
    private $security;
    public function __construct(SecurityController $security)
    {
        $this->security = $security;
    }

    #[Route('/create/trail', name: 'app_create_trail')]
    public function index(
        LiftRepository $liftRepository,
        SlopeRepository $slopeRepository,
        StationRepository $stationRepository,
        TrailRepository $TrailRepository,
        EntityManagerInterface $entityManager,
        LinkTrailRepository $LinkTrail,
        Request $request,
    ): Response
    {

        if(!isset($_GET['station']) || $_GET['station'] == 0) {
            $station = $stationRepository->findAll()[0];
        } else {
            $station = $_GET['station'];
        }

        $station = $stationRepository->findBy(['id' => $station])[0];

        // Get lifts from staion 1
        $allLifts = $liftRepository->findBy(['station' => $station->getId()]);
        $allSlopes = $slopeRepository->findBy(['station' => $station->getId()]);


        $arrayLifts = [];
        foreach ($allLifts as $lift) {
            $arrayLifts[$lift->getName()] = $lift;
        }

        $arraySlopes = [];
        foreach ($allSlopes as $slope) {
            $arraySlopes[$slope->getName()] = $slope;
        }

        $formLift = $this->createFormBuilder()
            ->add('addLift', ChoiceType::class, [
                'choices' => $arrayLifts,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->getForm();

        $formSlope = $this->createFormBuilder()
            ->add('addSlope', ChoiceType::class, [
                'choices' => $arraySlopes,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->getForm();

        $formTrail = $this->createFormBuilder()
            ->add('TrailName', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->getForm();

        $formChangeStation = $this->createFormBuilder()
            ->add('changeStation', ChoiceType::class, [
                'choices' => $stationRepository->findAll(),
                'choice_label' => function ($station) {
                    return $station->getName();
                },
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->getForm();

        $user = $this->security->getUser();

        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $trail = $TrailRepository->findOneBy(['owner' => $user->getId()], ['id' => 'DESC']);

        if(($trail && $trail->isIsCompleted()) || !$trail) {
            //Create new trail si le dernier trail est terminé ou inéxistant
            $trail = new Trail();
            $trail->setName('');
            $trail->setTotalDuration(new \DateTime('00:00'));
            $trail->setOwner($user);
            $trail->setIsCompleted(false);

            $entityManager->persist($trail);
            $entityManager->flush();
        }

        // Get last trail
        $lastTrail = $TrailRepository->findOneBy([], ['id' => 'DESC']);

        // Get last position
        $last = $LinkTrail->findOneBy(['trail_id' => $lastTrail->getId()], ['position' => 'DESC']);
        if(!$last) {
            $last = 0;
        } else {
            $last = $last->getPosition();
        }

        // Add data in database 
        $formLift->handleRequest($request);
        $formSlope->handleRequest($request);
        $formTrail->handleRequest($request);
        $formChangeStation->handleRequest($request);
        if ($formLift->isSubmitted() && $formLift->isValid()) {
            $data = $formLift->getData();

            $newLinkTrail = new LinkTrail();
            $newLinkTrail->setTrailId($lastTrail);
            $newLinkTrail->setLiftId($data['addLift']);
            $newLinkTrail->setPosition($last+ 1);

            $entityManager->persist($newLinkTrail);
            $entityManager->flush();

            return $this->redirectToRoute('app_create_trail');
        } else if ($formSlope->isSubmitted() && $formSlope->isValid()) {
            $data = $formSlope->getData();

            $newLinkTrail = new LinkTrail();
            $newLinkTrail->setTrailId($lastTrail);
            $newLinkTrail->setSlopeId($data['addSlope']);
            $newLinkTrail->setPosition($last+ 1);

            $entityManager->persist($newLinkTrail);
            $entityManager->flush();

            return $this->redirectToRoute('app_create_trail');
        }

        $myTrail = $LinkTrail->findBy(['trail_id' => $lastTrail->getId()]);

        // Calcul total temps de trajet
        $totalDuration = new \DateTime('00:00');
        foreach ($myTrail as $trailPart) {
            if ($trailPart->getLiftId()) {
                $liftDuration = $trailPart->getLiftId()->getDuration();
                $liftDateTime = new \DateTime('@' . $liftDuration->getTimestamp());
                $totalDuration->add((new \DateTime('00:00:00'))->diff($liftDateTime));
            } elseif ($trailPart->getSlopeId()) {
                $slopeDuration = $trailPart->getSlopeId()->getDuration();
                $slopeDateTime = new \DateTime('@' . $slopeDuration->getTimestamp());
                $totalDuration->add((new \DateTime('00:00:00'))->diff($slopeDateTime));
            }
        }
        
        if ($formTrail->isSubmitted() && $formTrail->isValid()) {
            $data = $formTrail->getData();

            $lastTrail->setTotalDuration($totalDuration);
            $lastTrail->setName($data['TrailName']);
            $lastTrail->setIsCompleted(true);

            $entityManager->persist($lastTrail);
            $entityManager->flush();

            return $this->redirectToRoute('app_create_trail');
        }

        if ($formChangeStation->isSubmitted() && $formChangeStation->isValid()) {
            $data = $formChangeStation->getData();

            return $this->redirectToRoute('app_create_trail', ['station' => $data['changeStation']->getId()]);
        }

        return $this->render('create_trail/index.html.twig', [
            'controller_name' => 'CreateTrailController',
            'lifts' => $allLifts,
            'slopes' => $allSlopes,
            'trails' => $myTrail,
            'totalDuration' => $totalDuration,
            'formLift' => $formLift->createView(),
            'formSlope' => $formSlope->createView(),
            'formTrail' => $formTrail->createView(),
            'formChangeStation' => $formChangeStation->createView(),
        ]);
    }
}
