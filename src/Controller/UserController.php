<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


use App\Entity\User;
use App\Form\AvatarType;
use App\Form\UserEditType;
use App\DTO\ChangePasswordModel;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;



class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


        // ^ SHOW USER - profile
        #[Route('/profile/user/{pseudo}', name: 'show_user')]
        #[IsGranted("ROLE_USER")]
        public function show(User $user = null, Security $security, Request $request, EntityManagerInterface $entityManager): Response {
    
        if(!$user) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
        }
    
        $user = $security->getUser();
    
        // Si l'utilisateur n'est pas connecté, redirection vers la page d'accueil ou une autre page.
        // if (!$user instanceof User) {
        //     return $this->redirectToRoute('app_login');
        // }
    
        // ^ edit avatar
        $form = $this->createForm(AvatarType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
           
            $avatarFile = $form->get('avatar')->getData();
                if ($avatarFile) {
    
                    $oldAvatar = $user->getAvatar();
                    if ($oldAvatar) {
                        $oldAvatarPath = $this->getParameter('avatars_directory').'/'.$oldAvatar;
                        $newPath = $this->getParameter('oldPhotos_directory').'/'.$oldAvatar;
    
                        if (file_exists($oldAvatarPath)) {
                            rename($oldAvatarPath, $newPath);
                        }
                    }
    
                    $newFilename = uniqid().'.'.$avatarFile->guessExtension();
    
                    try {
                        $avatarFile->move(
                            $this->getParameter('avatars_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
                    $user->setAvatar($newFilename);
                    $entityManager->flush();
                }
    
                return $this->redirectToRoute('show_user', ['id' => $user->getId()]);
            }
    
    
            // ^ edit infos
            $formInfos = $this->createForm(UserEditType::class, $user);
            $formInfos->handleRequest($request);
            if ($formInfos->isSubmitted() && $formInfos->isValid()) {
    
                $user = $formInfos->getData();
                // $entityManager->persist($user);
                $entityManager->flush();
    
                $this->addFlash('success', 'Your profile has been updated.');
                return $this->redirectToRoute('show_user', ['pseudo' => $user->getPseudo()]);
            }
    
            return $this->render('user/show.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
                'formEditUser' => $formInfos, 
            ]);
        }
    
    
    
        // ^ EDIT PASSWORD USER
        #[Route('/profile/user/{id}/editPassword', name: 'editPassword_user')]
        #[IsGranted("ROLE_USER")]
        public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
        {
            // check that the user is logged in
            $user = $this->getUser();
            if (!$user instanceof User) {
                return $this->redirectToRoute('app_login');
            }
    
            // Create and instand of DTO (Data Transfer Object) to handle the password modification
            $changePasswordModel = new ChangePasswordModel();
    
            $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $changePasswordModel->getNewPassword() // use DTO
                );
                $user->setPassword($hashedPassword);
    
                // persist and flush the new data (password) in user
                $entityManager->persist($user);
                $entityManager->flush();
    
                $this->addFlash('success', 'Your password has been changed.');
                return $this->redirectToRoute('show_user', ['id' => $user->getId()]);
            }
            return $this->render('user/editPassword.html.twig', [
                'changePasswordForm' => $form->createView(),
            ]);
        }
    
        // ^ DELETE USER BY USER
        #[Route('/profile/user/{id}/delete', name: 'delete_user')]
        #[IsGranted("ROLE_USER")]
        public function delete(User $user, EntityManagerInterface $entityManager, Security $security, SessionInterface $session) : Response {
    
            $user = $security->getUser();
    
            // Deletion of user contacts (role photographer) (contact)
            $contacts = $user->getContacts();
            foreach ($contacts as $contact) {
                $entityManager->remove($contact);
            }          
            
            // Deletion of user images (role photographer) (photo)
            $images = $user->getPhotos();
            foreach ($images as $image) {
                $entityManager->remove($image);
            }
            
            $entityManager->remove($user);
            $entityManager->flush();
           
            // logout

    
            $this->addFlash('success', 'Your account has been deleted.');
            return $this->redirectToRoute('app_home');
        }
    
        // ^ DELETE USER BY ADMIN
        #[Route('/admin/user/{id}/delete', name: 'delete_user_admin')]
        #[IsGranted("ROLE_ADMIN")]
        public function deleteAdmin(User $user, UserRepository $userRepository, EntityManagerInterface $entityManager, Security $security, SessionInterface $session,) : Response {
    
            $userId = $user->getId();
    
            $user = $userRepository->find($userId);
     
            // Check if the user exist
            if (!$user) {
                throw $this->createNotFoundException('User not found');
            }
    
            
            // Deletion of user contacts (role photographer) (contact)
            $contacts = $user->getContacts();
            foreach ($contacts as $contact) {
                $entityManager->remove($contact);
            }

            // Deletion of user images (role photographer) (photo)
            $images = $user->getPhotos();
            foreach ($images as $image) {
                $entityManager->remove($image);
            }
           
            $entityManager->remove($user);
            $entityManager->flush();
           
            // logout

            $this->addFlash('success', 'This account has been deleted.');
            return $this->redirectToRoute('list_users');
        }
}
