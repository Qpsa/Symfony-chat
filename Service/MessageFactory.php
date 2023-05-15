<?php

namespace App\Service;

use App\Entity\Message;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class MessageFactory
{
    public function __construct(
        private readonly MessageManager    $messageManager,
        private readonly UserRepository    $userRepository)
    {

    }

    public function sendMessage(Request $request)
    {
        $message = new Message();

        $message->setSender($this->userRepository->find($request->get('sender')));
        $message->setReceiver($this->userRepository->find($request->get('receiver')));
        $message->setText($request->get('text'));

        $this->messageManager->save($message);

        return $this->json('Created new message with id ' . $message->getId());
    }
    }