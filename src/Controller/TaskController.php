<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddTaskType;

class TaskController extends AbstractController
{
    /**
     * @Route("/admin-add-task/{id}", name="admin-add-task")
     */
    public function addTask(Request $request,$id)
    {
        $projectRepository=$this->getDoctrine()->getRepository(Project::class);
        $project=$projectRepository->findOneBy(['id'=>$id]);
        $task = new Task();
        $date= new \DateTime();
        $form= $this->createForm(AddTaskType::class,$task);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $task->setCreatedAt($date);
            $task->setProject($project);
            $this->getDoctrine()->getManager()->persist($task);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin-project',['id' => $project->getId()]);
        }
        return $this->render('task/addTask.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }
}
