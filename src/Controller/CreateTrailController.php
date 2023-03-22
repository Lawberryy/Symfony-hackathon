<?php

namespace App\Controller;

use App\Repository\LiftRepository;
use App\Repository\SlopeRepository;
use App\Form\AddLiftTrailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrailController extends AbstractController
{
    #[Route('/create/trail', name: 'app_create_trail')]
    public function index(
        LiftRepository $liftRepository,
        SlopeRepository $slopeRepository
    ): Response
    {
        $form = $this->createForm(AddLiftTrailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('create_trail/index.html.twig', [
            'controller_name' => 'CreateTrailController',
            'lifts' => $liftRepository->findAll(),
            'slope' => $slopeRepository->findAll(),
            // 'id' => $_GET['id']
        ]);
    }
}
