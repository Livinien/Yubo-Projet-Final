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

        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($passwordHasher->hashpassword($user, $user->getPassword()));
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Votre inscription a bien été effectuée');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/signup.html.twig', [
            'form' => $userForm->createView(),
            
        ]);
    }
    


    
    // CONNEXION DE L'UTILISATEUR
    
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if($this->getUser()) {
            return $this->redirectToRoute('app_accueil');
        }
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();
        

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