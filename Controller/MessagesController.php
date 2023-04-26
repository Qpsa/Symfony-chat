<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use GuzzleHttp\Client;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\MessageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessagesController extends AbstractController
{

    public function __construct(
        private readonly MessageManager    $messageManager,
        private TokenStorageInterface      $token,)
    {

    }

    /**
     * @Route("/messages", name="messages")
     */
    public function index(Request $request)
    {
//        $this->denyAccessUnlessGranted('ROLE_USER');
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->token->getToken()->getUser();
            $message->setSender($currentUser);
            $this->messageManager->save($message);
        }

        return $this->render('messages/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
