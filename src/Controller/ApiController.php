<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/projects", name="api-liste")
     */
    public function listProjects(SerializerInterface $serializer)
    {
        $projectRepository=$this->getDoctrine()->getRepository(Project::class);
        $projects=$projectRepository->findAll();
        
            $jsonProjects=$serializer->serialize($projects, 'json', ['groups' => ['list']]);       
            return new JsonResponse($jsonProjects,200,[],true);       
    }
    /**
     * @Route("/api/project/{id}", name="api-project")
     */
    public function detailProject(SerializerInterface $serializer,$id)
    {
        $projectRepository=$this->getDoctrine()->getRepository(Project::class);
        $project=$projectRepository->findOneBy(['id'=>$id]);    
        $jsonTasks=$serializer->serialize($project, 'json',['groups' => ['task']]);       
        return new JsonResponse($jsonTasks,200,[],true);       
    }
}
