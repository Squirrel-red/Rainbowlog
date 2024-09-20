<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    // #[Route('/user', name: 'app_user')]
    // public function index(): Response
    // {
    //     return $this->render('user/index.html.twig', [
    //         'controller_name' => 'UserController',
    //     ]);
    // }
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        // On affiche la liste des utilisateurs
        // $users = $userRepository->findAll();

        // On trie les utilisateurs sur le assword dans l'ordre croissant alphabétique
        //idem SELECT * FROM user ORDER BY nom ASC
        $users = $userRepository->findBy([], ['password' => 'ASC']);


        return $this->render('user/index.html.twig', [
            'users'  => $users
        ]);
    }
}