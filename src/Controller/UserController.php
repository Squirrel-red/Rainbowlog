<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Evaluation;
use App\Form\EvaluationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class UserController extends AbstractController
{
    // --> On créé la méthode pour afficher les données d'un user  
    // #[Route('/user', name: 'app_index')]
    // public function index(UserRepository $userRepository): Response
    // {
    //     $users = $userRepository->findAll();
    //     return $this->render('user/index.html.twig', [
    //         'users'  => $users,
    //     ]);
    // }

    // --> On créé la méthode pour afficher la liste des users   
    #[Route('/user', name: 'app_user')]
    public function catalogUser(UserRepository $userRepository): Response
    {
        // On affiche la liste des users
        // $users = $userRepository->findAll();

        // On trie les utilisateurs sur l'id' dans l'ordre croissant
        //idem SELECT * FROM user ORDER BY id ASC
        $users = $userRepository->findBy([], ['id' => 'ASC']);

        return $this->render('user/catalog.html.twig', [
            'users'  => $users,
        ]);
    }


    // --> On créé la méthode pour supprimer le compte d'un user
    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function deleteUser(User $user, UserRepository $userRepository): RedirectResponse
    {

        // On supprime un user
        $userRepository->hideUser($user);

        // On ajoute un message
        $this->addFlash('success', 'Account has been anonymised successfully.');
        return $this->redirectToRoute('app_user'); 
        // return $this->redirectToRoute('user_catalog'); 
    }


    // --> On créé la méthode pour bloquer un des users
    #[Route('/user/block/{id}', name: 'user_block')]
    public function blockUser(User $user, UserRepository $userRepository): Response
    {
        $userRepository->blockUser($user);
        $users = $userRepository->findAll();
        $this->addFlash('success', 'User has been blocked successfully.');
    
        // return $this->redirectToRoute('app_user', [
        //     'users' => $users,
        // ]);
        return $this->render('user/catalog.html.twig', [
            'users'  => $users,
        ]);
    }


    // --> On créé la méthode pour débloquer un des users
    #[Route('/user/unblock/{id}', name: 'user_unblock')]
    public function unblockUser(User $user, UserRepository $userRepository): Response
    {
        $userRepository->unblockUser($user);
        $users = $userRepository->findAll();
        $this->addFlash('success', 'User has been unblocked successfully.');

        // return $this->redirectToRoute('app_user', [
        //     'users' => $users,
        // ]);
        return $this->render('user/catalog.html.twig', [
            'users'  => $users,
        ]);
    }

    
    // --> On créé la méthode pour estimer l'evaluation (rating) des users
    #[Route('/user/eval/{id}', name: 'user_eval')]
    public function eval(User $user, Request $request, EntityManagerInterface $entityManager, Evaluation $evaluation): Response
    {
        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evaluation->setUser($user);
            $entityManager->persist($evaluation);
            $entityManager->flush();

            return $this->redirectToRoute('app_user', ['id' => $user->getId()]);
        }

        return $this->render('user/eval.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

        // --> On créé la méthode pour estimer la moyenne de l'évaluation ( propriété :rating)  d'un user
    #[Route('/user/{id}', name: 'user_avg')]
    public function averageRating(User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $averageRating = $userRepository->getAverageRating($user);
        
        return $this->render('user/catalog.html.twig', [
            'user' => $user,
            'averageRating' => $averageRating,   
        ]);
    }
}