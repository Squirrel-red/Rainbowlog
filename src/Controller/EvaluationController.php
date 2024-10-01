<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Evaluation;
use App\Entity\User;
use App\Form\EvaluationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class EvaluationController extends AbstractController
{

    #[Route('/evaluation/{id}', name: 'app_evaluation')]
    public function eval(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $rating = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // --> On fait le lien entre l'user et  rating
            $rating->setUser($user);

            // --> On stocke le rating dans la BD
            $entityManager->persist($rating);
            $entityManager->flush();

            $this->addFlash('success', 'Rating has been registered successfully.');

            return $this->redirectToRoute('app_user', ['id' => $user->getId()]);
        }

        return $this->render('user/eval.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    // --> Example du codage des méthode
    // #[Route('/evaluation', name: 'app_evaluation')]
    // public function index(): Response
    // {
    //     return $this->render('evaluation/index.html.twig', [
    //         'controller_name' => 'EvaluationController',
    //     ]);
    // }
}
