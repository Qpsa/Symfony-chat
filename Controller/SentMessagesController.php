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
use Symfony\Component\Serializer\SerializerInterface;

class SentMessagesController extends AbstractController
{
    const MAX_RESULTS_PER_PAGE = '10';

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly MessageRepository $messageRepository,
        private EntityManagerInterface $em,
        private TokenStorageInterface $token,
        private readonly SerializerInterface $serializer)
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
        $pagerfanta->setMaxPerPage(self::MAX_RESULTS_PER_PAGE);

        foreach ($pagerfanta->getCurrentPageResults() as $message) {
                $text[] = [
                    'id' => $message->getId(),
                    'text' => $message->getText(),
                    'sender' => $message->getSender()->getId(),
                    'receiver' => $message->getReceiver()->getId(),
                ];
            $jsonContent = $this->serializer->serialize($text, 'json');
            $array = json_decode($jsonContent, true);
        }

        return $this->json($array);
    }


}
