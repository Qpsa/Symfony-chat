<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\MessageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(
        private readonly MessageRepository $messageRepository,
        private readonly MessageManager    $messageManager,
        private readonly UserRepository    $userRepository)
    {

    }

    /**
     * @Route("/post", name="post_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/post", name="app_post", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $message = new Message();

        $message->setSender($this->userRepository->find($request->get('sender')));
        $message->setReceiver($this->userRepository->find($request->get('receiver')));
        $message->setText($request->get('text'));

        $this->messageManager->save($message);

        return $this->json('Created new message with id ' . $message->getId());

    }
}
