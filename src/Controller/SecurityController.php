<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{

    // INSCRIPTION DE L'UTILISATEUR
    
    #[Route('/signup', name: 'signup')]
    public function signup(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {

        // Création de l'instance de la classe "User"
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        

        // Soumission du formulaire d'inscription
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $newEmail = $user->getEmail();
            $oldUser = $em->getRepository(User::class)->findOneBy(['email' => $newEmail]);

            // Si l'email de l'utilisateur existe déjà dans la BDD
            if ($oldUser){
                $this->addFlash('danger', "L'email a déjà été utilisé");
            }
            
            // Hachage de mot de passe et envoyé les informations en BDD
            else {
                $user->setPassword($passwordHasher->hashpassword($user, $user->getPassword()));
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Votre inscription a bien été effectuée');
                return $this->redirectToRoute('login');
            }
        }

        // Le rendu de la page du formulaire d'inscription 
        return $this->render('security/signup.html.twig', [
            'form' => $userForm->createView(),
        ]);
    }
    


    
    // CONNEXION DE L'UTILISATEUR
    
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Utilisateur connecté et redirection pas d'accueil
        if($this->getUser()) {
            return $this->redirectToRoute('app_accueil');
        }
        
        // Je récupère la dernière erreur d'authentification / le dernier nom de l'utilisateur 
        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();
        
        
        // Le rendu de la page du formulaire de connexion + erreur et nom d'utilisateur
        return $this->render('security/login.html.twig', [
            'error' => $error,
            'username' => $username
        ]);
    }


    
    // DÉCONNEXION DE L'UTILISATEUR
    
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
       
    }
}