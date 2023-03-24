<?php

namespace App\Websocket;

use App\Entity\ChatHistory;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MessageHandler implements MessageComponentInterface
{
    use ContainerAwareTrait;

    protected SplObjectStorage $connections;


    public function __construct()
    {
        $this->connections = new SplObjectStorage();
        $this->container = new Container();
    }

    public function onOpen(ConnectionInterface $conn)
    {
       $this->connections->attach($conn);
       echo "Nouvelle connexion! ({$conn->resourceId})" . PHP_EOL;
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->connections->attach($conn);
        $conn->close();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    #[NoReturn] public function onMessage(ConnectionInterface $from, $msg)
    {
        //afficher le message dans la console
        echo "Message reçu: {$msg}" . PHP_EOL;
        foreach ($this->connections as $connection) {
            $connection->send($msg);
        }
        /*

        $message = json_decode($msg);
        //trouver l'user correspondant à l'id dans $message['sender']
        $em = $this->container->get(EntityManagerInterface::class);
        echo "EntityManager chargé avec succès";
        $user = $em->getRepository(User::class)->find($message->sender);
        //créer une nouvelle entité ChatHistory
        $chatHistory = new ChatHistory();
        $chatHistory->setSender($user);
        $chatHistory->setMessage($message->message);
        $chatHistory->setSentAt(\DateTimeImmutable::createFromMutable(new \DateTime($message->sentAt, new \DateTimeZone('UTC'))));
        //persister l'entité
        $em->persist($chatHistory);
        $em->flush();

        */
    }
}