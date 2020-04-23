<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddProjectType;
use App\Form\ChangeStatutType;

class ProjectController extends AbstractController
{
    /**
     * @Route("/admin-projects", name="admin-projects")
     */
    public function listProject()
    {
        $projectRepository=$this->getDoctrine()->getRepository(Project::class);
        $projects=$projectRepository->findAll();
        $taskRepository=$this->getDoctrine()->getRepository(Task::class);
        $countTasks=$taskRepository->getCountByProject();
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
            'countTasks'=>$countTasks
        ]);
    }
    /**
     * @Route("/admin-add-project", name="admin-add-project")
     */
    public function addProject(Request $request)
    {
        $project = new Project();
        $date= new \DateTime();
        $form= $this->createForm(AddProjectType::class,$project);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $project->setStartedAt($date);
            $project->setStatut("Nouveau");
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin-projects');
        }
        return $this->render('project/addProject.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin-project/{id}", name="admin-project")
     */
    public function detailProject($id,Request $request){
        $projectRepository=$this->getDoctrine()->getRepository(Project::class);
        $project=$projectRepository->findOneBy(['id'=>$id]);
        $taskRepository=$this->getDoctrine()->getRepository(Task::class);
        $tasks=$taskRepository->getTasksByProject($id);
        $countTasks=$taskRepository->getCountByProject();
        $form= $this->createForm(ChangeStatutType::class,$project);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($project->getStatut()=="Terminé"){
                $date=new \DateTime();
                $project->setEndedAt($date);
            }
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin-project',['id' => $project->getId()]);
        }
        return $this->render('project/detailProject.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            'form' => $form->createView(),
            'countTasks'=>$countTasks

        ]);
    }
}
