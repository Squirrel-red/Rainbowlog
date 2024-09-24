<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
use App\Form\SearchUserType;
use App\Repository\UserRepository;
use App\Form\PublishedUserPageType;
use App\Form\PublishedPhotographerPageType;
use Doctrine\ORM\EntityManagerInterface;


class DashboardController extends AbstractController
{

// View
#[Route('/admin/dashboard', name: 'app_dashboard')]
    #[IsGranted("ROLE_ADMIN")]
    public function index(UserRepository $userRepository) : Response
    {
        $users = $userRepository->findAll();
        $usersCount= $userRepository->count([]);
        $photographers= $userRepository->findUsersbyRole('ROLE_USER');
        $photographersCount = count($photographers);
        $photographerspageCount = $userRepository->count(['isPublished' => 1]);
        $usersLoggedThisWeek = $userRepository->countUsersLoggedInThisWeek();
        
        
        return $this->render('dashboard/index.html.twig', [
            'usersCount' => $usersCount,
            'photographersCount' => $photographersCount, 
            'photographerspageCount' => $photographerspageCount,
            'usersLoggedThisWeek' => $usersLoggedThisWeek, 
        ]);
    }

   //#[Route('/dashboard', name: 'app_dashboard')]
   // public function index(): Response
   // {
   //     return $this->render('dashboard/index.html.twig', [
   //         'controller_name' => 'DashboardController',
   //     ]);
   // }

    // user's listing

    // user's details
   
    // ^ topic's listing
    // #[Route('/admin/dashboard/topics', name: 'list_topics')]
    // #[IsGranted("ROLE_ADMIN")]
    // public function indexExpos(TopicRepository $topicRepository): Response
    // {
    
    //     $topics = $topicRepository->findBy(['name' => 'NATURE']);
    //     $ongoingTopics = $topicRepository->findBy([
    //             'name' => 'NATURE',
    //             'status' => ['OPEN', 'CLOSED'],
    //     ]);
    //     $pastTopicss = $topicRepository->findBy([
    //         'name' => 'NATURE',
    //         'status' => ['ARCHIVED'],
    //     ]);

    //         return $this->render('dashboard/indexTopics.html.twig', [
    //             'topicss' => $topicss,
    //             'ongoingTopics' => $ongoingTopics,
    //             'pastTopics' => $pastTopics,

    //         ]);
    // }   


}
