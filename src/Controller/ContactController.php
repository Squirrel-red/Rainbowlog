<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    //  Exemple
    // #[Route('/contact', name: 'app_contact')]
    // public function index(): Response
    // {
    //     return $this->render('contact/index.html.twig', [
    //         'controller_name' => 'ContactController',
    //     ]);
    // }


    // --> On créé la méthode pour lister les messages déjà réçus triés par la date d'envoie
    #[Route('/contact', name: 'app_contact')]
    public function index(ContactRepository $contactRepository): Response
    {
        $user = $this->getUser();
    
        $contacts = $contactRepository->findBy(['receiver' => $user], ["dateMessage" => "DESC"]);
        return $this->render('contact/index.html.twig', [
          'contacts' => $contacts
        ]);
    }

    // --> On créé la méthode pour afficher les messages reçus ( on utilise pour l'onglet "Messages")
        #[Route('/contacts/received', name: 'received_contacts')]
        public function receivedContacts(EntityManagerInterface $entityManager, ContactRepository $contactRepository, UserRepository $userRepository): Response
        {
            //--> on recupère l'user connecté
            $user = $this->getUser();
            //--> on écupère les messages reçus par l'user connecté, triés par date d'envoi
            $contacts = $contactRepository->findBy(['receiver' => $user], ['dateMessage' => 'DESC']);
            //--> on met à jour le nombre de nouveaux messages pour l'user
            $userRepository->countByNewMessages($user);
            //-->Persiste les modifications de l'user dans la BD
            $entityManager->persist($user); 
            $entityManager->flush();
            return $this->render('contact/received.html.twig', [
                'contacts' => $contacts,
                
            ]);
        }

    // --> On créé la méthode pour lire le message (le marquer comme vu )
    #[Route('/contacts/read/{id}', name: 'read_messages')]
    public function readMessage(Contact $contact, EntityManagerInterface $entityManager, ContactRepository $contactRepository, User $user ): Response
    {
        $contact->setSeen(true); // On met le message vu (boolean true)
        $entityManager->flush(); // Sauvegarde les changements dans BD

        //--> on recupère l'user connecté
            $user = $this->getUser();

        // --> on recupère les messages envoyés par l'user connecté
            $contactReceiver = $contactRepository->findBy(['receiver' => $user]);
            $userReceiver = $contact->getReceiver();// on recupère le destinataire du message
            // --> on alimente le compteur de nouveaux messages pour le destinataire -1 pour l'affichage dans l'onglet Messages d'un user
            $userReceiver->setNewMessages($userReceiver->getNewMessages() - 1);
            // --> Persiste les modifications pour le destinataire et le message dans la BD
             $entityManager->persist($userReceiver);
             $entityManager->persist($contact);
             $entityManager->flush();// On met  les nouvelles données dans la BD
            

        return $this->redirectToRoute('received_contacts');
        
    }

    // -->  Oncréé la méthode pour lister les messages envoyés
    #[Route('/contacts/sent', name: 'app_contact_sent')]
    public function sent(ContactRepository $contactRepository): Response
    {
            //--> on recupère l'user connecté
            $user = $this->getUser();
    
            // --> on recupère les messages envoyés par l'user connecté
            $contactSent = $contactRepository->findBy(['sender' => $user]);
    
            return $this->render('contact/sent.html.twig', [
                'contactSent' => $contactSent,
            ]);
    }

    // --> On créé la méthode pour afficher les messages envoyés par l'user
    #[Route('/contacts/user/{id}', name: 'contacts_user')]
    public function contactsUser(ContactRepository $contactRepository): Response
    {
            //--> on recupère l'user connecté
            $user = $this->getUser();
            // --> on recupère les messages envoyés par l'user connecté, triés par date d'envoie
            $contacts = $contactRepository->findBy(['sender' => $user], ['dateMessage' => 'DESC']);
    
            return $this->render('contact/user_contacts.html.twig', [
                'contacts' => $contacts,
                'user' => $user
                ]);
    }

    // --> On créé la méthode pour mettre à jour le contrôleur, c'est neccessaire pour la nouvelle action d'envoi de message
    #[Route('/contact/{id}/new', name: 'new_contact')]
    public function new(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {   
        //--> on recupère l'user connecté
        // $currentUser = $this->getUser();
        // Vérifie si l'user actuel essaie d'envoyer un message
        // if ($currentUser && $currentUser->getId() === $user->getId()) {
        //     $this->addFlash('error', 'You cannot send this message');
        //     return $this->redirectToRoute('app_home'); // On rédirige vers la page d'accuiel
        // }
        $contact = new Contact();// --> un nouvel objet Contact
        $contact->setReceiver($user);// --> on lié le destinataire au message
        $form = $this->createForm(ContactType::class, $contact);//--> on créé un formulaire  avec le type ContactType
        $form->handleRequest($request);// --> la requête du formulaire
        if($form->isSubmitted() && $form->isValid()) {// --> on controle si le formulaire est soumis et valide
            // --> On lie l'user connecté comme expéditeur
            $contact->setSender($this->getUser()); // 
            $contact->setDateMessage(new \DateTime());// on fait la date  du jour = la date d'envoi
            $userReceiver = $contact->getReceiver();// on recupère le destinataire du message
            // --> on alimente le compteur de nouveaux messages pour le destinataire +1
            $userReceiver->setNewMessages($userReceiver->getNewMessages() + 1);
            // --> Persiste les modifications pour le destinataire et le message dans la BD
            $entityManager->persist($userReceiver);
            $entityManager->persist($contact);
            $entityManager->flush();// On met  les nouvelles données dans la BD

            return $this->redirectToRoute('received_contacts');
        }
        return $this->render('contact/new.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }
            
}
