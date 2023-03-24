<?php

namespace App\Controller;

use App\Entity\LinkTrail;
use App\Entity\Trail;
use App\Controller\SecurityController;
use App\Repository\LiftRepository;
use App\Repository\SlopeRepository;
use App\Repository\TrailRepository;
use App\Repository\LinkTrailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        TrailRepository $TrailRepository,
        EntityManagerInterface $entityManager,
        LinkTrailRepository $LinkTrail,
        Request $request,
    ): Response
    {

        // Get lifts from staion 1
        $allLifts = $liftRepository->findBy(['station' => 1]);
        $allSlopes = $slopeRepository->findBy(['station' => 1]);


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
        if ($formLift->isSubmitted() && $formLift->isValid()) {
            $data = $formLift->getData();

            $newLinkTrail = new LinkTrail();
            $newLinkTrail->setTrailId($lastTrail);
            $newLinkTrail->setLiftId($data['addLift']);
            $newLinkTrail->setPosition($last+ 1);

            $entityManager->persist($newLinkTrail);
            $entityManager->flush();
        } else if ($formSlope->isSubmitted() && $formSlope->isValid()) {
            $data = $formSlope->getData();

            $newLinkTrail = new LinkTrail();
            $newLinkTrail->setTrailId($lastTrail);
            $newLinkTrail->setSlopeId($data['addSlope']);
            $newLinkTrail->setPosition($last+ 1);

            $entityManager->persist($newLinkTrail);
            $entityManager->flush();
        }

        dd($LinkTrail->findBy(['trail_id' => $lastTrail->getId()])[0]->getLiftId()->getName());


        return $this->render('create_trail/index.html.twig', [
            'controller_name' => 'CreateTrailController',
            'lifts' => $allLifts,
            'slopes' => $allSlopes,
            'trails' => $LinkTrail->findBy(['trail_id' => $lastTrail->getId()]),
            'formLift' => $formLift->createView(),
            'formSlope' => $formSlope->createView(),
        ]);
    }
}
