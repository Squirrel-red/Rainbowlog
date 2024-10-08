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
    // --> On créé la méthode pour la recherche de l'experience par la cle (title, nearTown))
    #[Route('/experience', name: 'app_experience')]
    public function index(Request $request, ExperienceRepository $experienceRepository,  CategoryRepository $categoryRepository, CommentRepository $commentRepository, PaginatorInterface $paginator): Response 
    {
        $form = $this->createForm(ResearchType::class);
        $form->handleRequest($request);
    
        $title = $form->get('title')->getData();
        $nearTown = $form->get('nearTown')->getData();

        
        $query = $experienceRepository->findExperience($title, $nearTown);

        //--> Faire la pagination pour les experiences (afficher 3 par page  --> à modifier)
        $experiences = $paginator->paginate(
            // Query ou array
            $query,  /* query NOT result */
            // Page courante, on commence par la 1ère
            $request->query->getInt('page', 1), /*page number*/
            // Nombre des experiences = 3 affichées sur chaque page
            3
        );


        $categories = $categoryRepository->findAll();
        // --> Afficher les 5 derniers comments
        $lastComments = $commentRepository->findBy([], ['dateComment' => 'DESC'], 5); 
    
        // paramètres pour  template
        return $this->render('experience/index.html.twig', [
            'experiences' => $experiences,
            'ResearchForm' => $form->createView(),
            'categories' => $categories,
            'lastsComments' => $lastComments,
        ]);
    }

    // --> Méthode n°1 pour créer une nouvelle experience(/experience/new)
    #[Route('/experience/new', name: 'new_experience')]
    // --> Méthode n°2 pour éditer une experience existante (/experience/{id}/edit)
    #[Route('/experience/{id}/edit', name: 'edit_experience')]
 
    // -->Fonction accepte (une entité Experience méme  null, une requête HTTP, et l'EntityManager pour la gestion des entités)
    public function new_edit(Experience $experience = null, Request $request, EntityManagerInterface $entityManager): Response
    {
            
            // --> Si  $experience est null) on créé une nouvelle instance de l'entité Experience
            if(!$experience){
                $experience = new Experience();

            // --> User actuel dévient l'autheur de cette novelle experience
                $experience->setPublish($this->getUser());
            }
            // --> On met la date de création de l'experience = la date courante du jour
            $experience->setDateCreation(new \DateTime());
            
            // --> On créé un formulaire (de la classe ExperienceType contenant les données de l'entité Experience)
            $form = $this->createForm(ExperienceType::class, $experience);

            // --> On fait la requête HTTP
            $form->handleRequest($request);
            
            // --> On controle  la soumission et la validation du formulaire
            if($form->isSubmitted() && $form->isValid()) {
                
                // --> On récupère les données du formulaire destinées pour l'entité Experience
                $experience = $form->getData();
                
                //--> On prépare l'entité Experience pour la mise dans la BD
                $entityManager->persist($experience);
                
                // --> On récupère les fichiers d'images du formulaire
                $imageFiles = $form->get('images')->getData();
                
                // --> On fait la boucle des images mis dans le formilaire
                foreach($imageFiles as $imageFile) {

                    // --> On récupère le nom initial de fichier  sans extension ( on ne l'a pas utilisé en futur)
                    $oldFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    
                    // -->On génére un nouveau nom unique de fichier en utilisant uniqid() en  conservant l'extension d'origine
                    $newFileName = uniqid() . '.' . $imageFile->guessExtension();
                  
        
                    // -->On met le fichier téléchargé dans images_directory
                    // --> sous le nouveau nom généré avant
                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newFileName
                        );

                    // --> On detect et traite les erreurs du téléchargement du fichier o cas ou
                    } catch (FileException $e) {
                        
                    }

                    // -->On créé une nouvelle donnée pour l'entité Photo
                    $photo = new Photo();

                    // On lié le path et le nom du fichier
                    $photo->setPath('/img/' . $newFileName);
                   
                    //--> On lié la photo à cette experience
                    $photo->setExperience($experience);

                    // On lié le title de  photo au title de l'expérience
                    $photo->setTitle($experience->getTitle());

                    //-->  Prépare la photo pour la sauvegarde dans la BD
                    $entityManager->persist($photo);
                   
                }
        
                // -->  On sauvegarde toutes les nouvelles données (persist) dans la BD
                $entityManager->flush();
                
                // --> On va vers la route 'app_experience' après la création de l'experience.
                return $this->redirectToRoute('app_experience');
        
            }
                // --> Affiche la vue 'experience/new.html.twig' (forme)
                // --> et l'id de l'experience éditante.
                return $this->render('experience/new.html.twig', [
                'formAddExperience' => $form,
                'edit' => $experience->getId()
            ]);

    }
        
    // --> Suppression de l'experience    
    #[Route('/experience/{id}/delete', name: 'delete_experience')]
    public function delete(Experience $experience, EntityManagerInterface $entityManager)
    {
                // --> Suppression des photos liés
                foreach ($experience->getPhotos() as $photo) {
                    $entityManager->remove($photo);
                }
        
                $entityManager->remove($experience);
                $entityManager->flush();
        
                return $this->redirectToRoute('app_experience');
    }
        
        
    #[Route('/experience/{id}', name: 'show_experience')]
    public function show(int $id, Experience $experience, Request $request, EntityManagerInterface $entityManager): Response
    {
                 $experience = $entityManager->getRepository(Experience::class)->find($id);
        
                 if (!$experience) {
                    
                     throw $this->createNotFoundException('Cette experience is absent.');
                 }
        
                // --> Ajout au compteur de vues
                $experience->getCounterView();
                $entityManager->flush();
        
                $comments = $experience->getComments();
        
                // --> Création et gestion du formulaire de comments
                $comment = new Comment();
                $comment->setConsumer($this->getUser()); // Définir l'consumer courant comme auteur
                $comment->setExperience($experience);// Lier le commentaire à l'experience
                $comment->setDateComment(new \DateTime()); // Mettre automatiquement la date de création à aujourd'hui
        
                $form = $this->createForm(CommentType::class, $comment);
        
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($comment);
                    $entityManager->flush();
        
                    return $this->redirectToRoute('show_experience', ['id' => $experience->getId()]);
                }
                return $this->render('experience/show.html.twig', [
                    'experience' => $experience,
                    'comments' => $comments,
                    'formComment' => $form->createView(),
                ]);
        

     }


    //--> Visualisation des experiences triées par catégories
    #[Route('/category/{id}', name: 'experiences_category')]
    public function experiencesCategory(Request $request, Category $category, ExperienceRepository $experienceRepository, CategoryRepository $categoryRepository, SessionInterface $session): Response
    {
            $experiences = $experienceRepository->findBy(['category' => $category], ['dateCreation' => 'DESC']);
            $categories = $categoryRepository->findAll();
    
        
            return $this->render('experience/experiences_category.html.twig', [
                'experiences' => $experiences,
                'categories' => $categories,
            ]);
    }


    // --> Visualisation tous les  alerts
    #[Route('/alerts', name: 'list_alerts')]
    public function listAlerts(AlertRepository $alertRepository): Response
    {
        $alerts = $alertRepository->findAll();

        return $this->render('experience/list_alerts.html.twig', [
            'alerts' => $alerts,
        ]);
    }


    // --> Création de l'alerte sur l'experience
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
