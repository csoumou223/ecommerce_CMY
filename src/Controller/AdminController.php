<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/editRole/{id}", name="editRole")
     */
    public function editRole(User $user = null){
        if($user == null){
            $this->addFlash('danger', "Utilisateur introuvable");
            return $this->redirectToRoute('admin');
        }

        if(in_array('ROLE_ADMIN', $user->getRoles())){
            $user->setRoles(['ROLE_USER']);
        }
        else{
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash("success", 'Rôle mis à jour');
        return $this->redirectToRoute('admin');

    }
}
