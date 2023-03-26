<?php

namespace App\Controller;

use App\Entity\ChatHistory;
use App\Repository\ChatHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    private SecurityController $security;
    public function __construct(SecurityController $securityController){
        $this->security = $securityController;
    }

    #[Route('/chat', name: 'app_chat')]
    public function index(ChatHistoryRepository $chatHistoryRepository): Response
    {
        if(!$this->security->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $chatHistory = $chatHistoryRepository->findAll();

        return $this->render('chat/index.html.twig', [
            'chatHistory' => $chatHistory,
            'user' => $this->security->getUser(),
        ]);
    }
}
