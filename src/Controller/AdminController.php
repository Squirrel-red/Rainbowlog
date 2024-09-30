<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends AbstractController
{

// --> Exemple    
    // #[Route('/admin', name: 'app_admin')]
    // public function index(): Response
    // {
    //     return $this->render('admin/index.html.twig', [
    //         'controller_name' => 'AdminController',
    //     ]);
    // }

//  --> On créé la méthode pour la visualisation des users, compter tous les users et les users blockés
#[Route('/admin/index', name: 'admin_index')]
    #[IsGranted("ROLE_ADMIN")]
    public function index(UserRepository $userRepository) : Response
    {
        // $users = $userRepository->findAll();
        $users = $userRepository->findBy([], ['id' => 'ASC']);
        $usersCount= $userRepository->count([]);
        $usersBlockedCount= $userRepository->count(['isBlocked'=> "Yes"]);
        
        
        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'usersCount' => $usersCount,
            'usersBlockedCount' => $usersBlockedCount,
            
        ]);
    }


    // --> On créé la méthode pour afficher la liste des catégories
    #[Route('/admin/categories', name: 'admin_category_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function listCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/catalog_category.html.twig', [
            'categories' => $categories,
        ]);
    }

    // --> On créé la méthode pour modifier la catégorie
    #[Route('/admin/category/{id}/modif', name: 'admin_category_modif')]
    #[IsGranted('ROLE_ADMIN')]
    public function editCategory(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/modif_category.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    // --> On créé la méthode pour supprimer la catégorie
    #[Route('/admin/category/{id}/delete', name: 'admin_category_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteCategory(Category $category, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {

        if($category->getExperiences()->count() > 0) {
            $this->addFlash('error', 'This category includes some experiences. It needs delete them.');
            return $this->redirectToRoute('admin_category_list');
        }

        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'Category has been deleted successfully.');
        return $this->redirectToRoute('admin_category_list');
    } 

    // --> On créé la méthode pour ajouter la nouvelle la catégorie
    #[Route('/admin/category/add', name: 'admin_category_add')]
    #[IsGranted('ROLE_ADMIN')]
    public function newCategory(Request $request, EntityManagerInterface $entityManager): Response
    {

        $categorie = new Category();
        $form = $this->createForm(CategoryType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('admin_category_list');
        }
        return $this->render('admin/add_category.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
