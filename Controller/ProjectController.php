<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Project;

class ProjectController extends AbstractController
{

    public function __construct(private readonly ProjectRepository $projectRepository, private readonly EntityManagerInterface $em)
    {

    }
    /**
     * @Route("/project", name="project_index", methods={"GET"})
     */
    public function index(): Response
    {
        $products = $this->projectRepository->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
            ];
        }


        return $this->json($data);
    }

    /**
     * @Route("/project", name="project_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {

        $project = new Project();
        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));

        $this->em->persist($project);
        $this->em->flush();

        return $this->json('Created new project successfully with id ' . $project->getId());
    }

    /**
     * @Route("/project/{id}", name="project_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $project = $this->getDoctrine()
            ->getRepository(Project::class)
            ->find($id);

        if (!$project) {

            return $this->json('No project found for id' . $id, 404);
        }

        $data =  [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/project/{id}", name="project_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $project = $this->em->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }

        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));
        $this->em->flush();

        $data =  [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/project/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $project = $this->em->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }

        $this->em->remove($project);
        $this->em->flush();

        return $this->json('Deleted a project successfully with id ' . $id);
    }


}