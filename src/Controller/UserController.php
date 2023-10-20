<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserController extends AbstractController
{

    
    #[Route('/user/{id}', name: 'user')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function userProfile(User $user): Response
    {

        $currentUser = $this->getUser();

        if($currentUser === $user) {
            return $this-> redirectToRoute('current_user');
        }
        
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }
    
    
    
    // CHANGER SON MOT DE PASSE DEPUIS SA PAGE PROFIL
    
    #[Route('/user', name: 'current_user')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function currentUserProfile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
      $user = $this->getUser();
      $userForm = $this->createForm(UserType::class, $user);
      $userForm->remove('password');
      $userForm->add('newPassword', PasswordType::class, [
        'label' => 'Nouveau mot de passe',
        'required' => false
        ]);
    
      $userForm->handleRequest($request);
      
      if ($userForm->isSubmitted() && $userForm->isValid()) {
        
        $newPassword = $user->getNewPassword();
        
        if ($newPassword) {
          $hash = $passwordHasher->hashPassword($user, $newPassword);
          $user->setPassword($hash);
        }
        
        $em->flush();
        $this->addFlash('success', 'Modifications sauvegardées !');
      }
  
      return $this->render('user/index.html.twig', [
        'form' => $userForm->createView()
      ]);
    }


    
    // SUPPRIMER SON COMPTE

    #[Route('/delete', name: 'delete_user')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function deleteProfile(Request $request, EntityManagerInterface $em, $id): Response
    {

        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_accueil'); 
    }
}