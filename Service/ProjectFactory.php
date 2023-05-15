<?php

namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProjectFactory
{
    public function __construct(private readonly ProjectRepository $projectRepository, private readonly EntityManagerInterface $em)
    {

    }

    public function createNew(Request $request)
    {
        $project = new Project();
        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));

        $this->em->persist($project);
        $this->em->flush();

        return $this->json('Created new project successfully with id ' . $project->getId());
    }
    
}