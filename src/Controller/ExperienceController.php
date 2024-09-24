<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Experience;
use App\Entity\Category;
use App\Entity\Photo;
use App\Entity\Comment;
use App\Entity\Alert;
use App\Repository\ExperienceRepository;
use App\Repository\CategoryRepository;
use App\Repository\PhotoRepository;
use App\Repository\CommentRepository;
use App\Repository\AlertRepository;
use App\Form\ExperienceType;
use App\Form\CommentType;
use App\Form\AlertType;
use App\Form\ResearchType;



class ExperienceController extends AbstractController
{

    #[Route('/experience', name: 'app_experience')]
    public function index(Request $request, ExperienceRepository $experienceRepository,  CategoryRepository $categoryRepository, CommentRepository $commentRepository, PaginatorInterface $paginator): Response 
    {
        $form = $this->createForm(ResearchType::class);
        $form->handleRequest($request);
    
        $keyword = $form->get('keyword')->getData();
        $nearTown = $form->get('nearTown')->getData();

        
        $query = $experienceRepository->findExperience($keyword, $nearTown);
    
        $experiences = $paginator->paginate(
            // Query ou array
            $query, 
            // Page courante, on commence par la 1ère
            $request->query->getInt('page', 1), 
            // Nombre des experiences affichées sur chaque page
            5 
        );
    
    
        $categories = $categoryRepository->findAll();
        // Récupérer les 5 derniers commentaires
        $lastComments = $commentRepository->findBy([], ['dateComment' => 'DESC'], 10); 
    
        return $this->render('experience/index.html.twig', [
            'experiences' => $experiences,
            'ResearchForm' => $form->createView(),
            'categories' => $categories,
            'lastsComments' => $lastComments,
        ]);
    }

    // Supression de l'experience
    #[Route('/experience/{id}/delete', name: 'delete_experience')]
    public function delete(Experience $experience, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($experience);
        $entityManager->flush();

        return $this->redirectToRoute('app_experience');
    }

    //Visualisation des experiences triées par catégories
    #[Route('/category/{id}', name: 'experiences_by_category')]
    public function experiencesByCategory(Request $request, Category $category, ExperienceRepository $experienceRepository, CategoryRepository $categoryRepository, SessionInterface $session): Response
    {
            $experiences = $experienceRepository->findBy(['category' => $category], ['dateCreation' => 'DESC']);
            $categories = $categoryRepository->findAll();
    
        
            return $this->render('experience/experience_by_category.html.twig', [
                'experiences' => $experiences,
                'categories' => $categories,
            ]);
    }


    // Visualisation de la liste des alerts
    #[Route('/alerts', name: 'list_alerts')]
    public function listAlerts(AlertRepository $alertRepository): Response
    {
        $alerts = $alertRepository->findAll();

        return $this->render('experience/list_alerts.html.twig', [
            'alerts' => $alerts,
        ]);
    }


    // Création de l'alert sur l'experience
    #[Route('/experience/{id}/alert', name: 'alert_experience')]
    public function alertExperience(Experience $experience,Request $request, EntityManagerInterface $entityManager): Response
    {

        $alert = new Alert();
        $alert->setExperience($experience);
        $alert->setUser($this->getUser());
        $alert->setDateAlert(new \DateTime());
        

        $formAlert = $this->createForm(AlertType::class, $alert);
        $formAlert->handleRequest($request);

        if($formAlert->isSubmitted() && $formAlert->isValid()) {
            $entityManager->persist($alert);
            $entityManager->flush();

            $this->addFlash('success', 'L\'Alert was created successfully.');
            return $this->redirectToRoute('show_experience', ['id' => $experience->getId()]);
        }

        return $this->render('experience/alert.html.twig', [
            'experience' => $experience,
            'formAlert' => $formAlert->createView(),
        ]);
    }

}
