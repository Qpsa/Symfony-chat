<?php

namespace App\Service;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageManager
{
    public function __construct(
        private EntityManagerInterface     $em,
        )
    {


    }

    public function save(Message $message): void
    {
        // Save
        $this->em->persist($message);
        $this->em->flush();
    }
}