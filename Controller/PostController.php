<?php

namespace App\Controller;

use App\Service\MessageFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(
        private readonly MessageFactory    $messageFactory)
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
        $this->messageFactory->sendMessage($request);
    }
}
