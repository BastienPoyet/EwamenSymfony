<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ManagerController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->redirectToRoute('app_login');
    }
    /**
     * @Route("/admin-setlogin", name="admin-setlogin")
     */
    public function setLogin()
    {
        $userCo=$this->getUser();
        $date= new \DateTime();
        $userCo->setLastLogin($date);
        $this->getDoctrine()->getManager()->persist($userCo);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin-projects');
    }
}
