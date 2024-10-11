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

        // On trie les utilisateurs sur le password dans l'ordre croissant alphabétique
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
 
    }


    // // --> On créé la méthode pour trouver un user par pseudo
    // #[Route('/user/find/{id}', name: 'user_find')]
    // public function findUser(User $user, UserRepository $userRepository): RedirectResponse
    // {
    
    //      // On on cherche le user par pseudo
    //      $user = $userRepository->findUserByPseudo("Anonyme");
    
    //      return $this->render('user/catalog.html.twig', [
    //         'user'  => $user,
    //     ]);
    //    }


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

    // // --> On créé la méthode pour estimer la moyenne de l'évaluation ( propriété :rating)  d'un user
    // #[Route('/user/average/{id}', name: 'user_average')]
    // public function average(User $user, UserRepository $userRepository): Response
    // {

    //     $averageRating = $userRepository->getRating($user);
    //     $rating = new Evaluation();
    //     // $rating->setUser($user);
       
    //     return $this->render('user/catalog.html.twig', [
    //         'user' => $user,
    //         'averageRating' => $averageRating,
    //     ]);
    // }
    
    // --> On créé la méthode pour estimer l'evaluation (rating) des users
    #[Route('/user/eval/{id}', name: 'user_eval')]
    public function eval(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $rating = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setUser($user);
            $entityManager->persist($rating);
            $entityManager->flush();

            return $this->redirectToRoute('app_user', ['id' => $user->getId()]);
        }

        return $this->render('user/eval.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}