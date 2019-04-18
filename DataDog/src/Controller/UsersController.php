<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(UserRepository $repository)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $users = $repository->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }
}
