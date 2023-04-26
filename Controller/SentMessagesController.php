<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MessageRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SentMessagesController extends AbstractController
{

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly MessageRepository $messageRepository,
        private EntityManagerInterface $em,
        private TokenStorageInterface $token)
    {

    }

    /**
     * @Route("/sentmessages", name="sentmessages", methods={"GET"})
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->token->getToken()->getUser();

        $messages = $this->messageRepository->findAllSentMessagesBy($user);

        $pagerfanta = new Pagerfanta(new QueryAdapter($messages));
        $pagerfanta->setMaxPerPage(10);

        $data = [];

        foreach ($pagerfanta->getCurrentPageResults() as $message) {
                $data[] = [
                    'id' => $message->getId(),
                    'text' => $message->getText(),
                    'sender' => $message->getSender()->getId(),
                    'receiver' => $message->getReceiver()->getId(),
                ];
        }

        return $this->json($data);
    }


}
