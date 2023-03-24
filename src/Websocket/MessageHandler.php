<?php

namespace App\Websocket;

use App\Entity\ChatHistory;
use App\Entity\User;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Doctrine\ORM\EntityManagerInterface;

class MessageHandler implements MessageComponentInterface
{

    private SplObjectStorage $connections;
    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->connections = new SplObjectStorage();
        $this->entityManager = $entityManager;
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

        $message = json_decode($msg, true);

        try{
            $chatHistory = new ChatHistory();
            $chatHistory->setMessage($message['message']);
            $chatHistory->setSender($this->entityManager->getRepository(User::class)->find($message['sender']));
            $dateTime = (new \DateTimeImmutable())->setTimestamp($message['sent_at']);
            $chatHistory->setSentAt($dateTime);
            $this->entityManager->persist($chatHistory);
            $this->entityManager->flush();
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
}