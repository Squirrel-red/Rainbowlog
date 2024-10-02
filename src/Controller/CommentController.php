<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Experience;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;




class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }


    // --> Method "Comment's delete"
    #[Route('/comment/{id}/delete', name: 'delete_comment')]
    public function delete(Comment $comment, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('show_experience', ['id' => $comment->getExperience()->getId()]);
    }    


    // --> Method "Comment's modification"
    #[Route('/comment/{id}/modif', name: 'modif_comment')]
    public function modif(Comment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $comment->setDateComment(new \DateTime()); // --> To apply the current's date

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('show_experience', ['id' => $comment->getExperience()->getId()]);
        }
        return $this->render('comment/modif.html.twig', [
            'formComment' => $form->createView(),
        ]);
    }




}
