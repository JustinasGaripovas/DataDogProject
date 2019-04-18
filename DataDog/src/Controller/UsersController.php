<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users_index")
     */
    public function index(UserRepository $repository)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $users = $repository->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/users/{id}/delete", name="user_delete")
     */
    public function deleteUser(Request $request, User $user)
    {

        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $em = $this->getDoctrine()->getManager();
        if(in_array("ROLE_ADMIN", $user->getRoles() )) {
            $em->remove($user);
        }
        $em->flush();
        return $this->redirectToRoute('users_index');
    }

    /**
     * @Route("/users/{id}/makeadmin", name="user_to_admin")
     */
    public function userToAdmin(Request $request, User $user)
    {

        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $em = $this->getDoctrine()->getManager();
        //$em->remove($user);
        $user->SetRoles(array('ROLE_ADMIN'));
        $em->flush();
        return $this->redirectToRoute('users_index');
    }
}
