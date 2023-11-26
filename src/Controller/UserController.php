<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

  // PAGE PROFIL D'UN UTILISATEUR DE YUBO
  
  #[Route('/user/{id}', name: 'user')]
  #[isGranted('IS_AUTHENTICATED_FULLY')]
  public function userProfile(User $user): Response
  {

    // Récupère l'utilisateur connecté à son compte.
    $currentUser = $this->getUser();

    // Être rediriger sur la page profil de l'utilisateur connecté grâce au name "current_user".
    if($currentUser === $user) {
        return $this->redirectToRoute('current_user');
    }
    
    // Afficher les détails d'un autre utilisateur de Yubo.
    return $this->render('user/show.html.twig', [
        'user' => $user
    ]);
  }
    
    
    // SUR LA PAGE PROFIL DE L'UTILISATEUR CONNECTÉ
    // CHANGER SON MOT DE PASSE
    
    #[Route('/user', name: 'current_user')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function currentUserProfile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
      $user = $this->getUser();
      $userForm = $this->createForm(UserType::class, $user);
      $userForm->remove('password');
      $userForm->add('newPassword', PasswordType::class, [
        'label' => 'Nouveau mot de passe',
        'required' => false,
        'constraints' => [
          new Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
          "Vous devez impérativement avoir 8 caractères minimum, contenant des chiffres, lettres, majuscules, minuscules et des caractères spéciaux pour modifier votre mot de passe de profil.")
        ],
      
        'help' => "*Vous avez besoin d'un mot de passe de 8 caractères minimum, contenant des chiffres, lettres, majuscules, minuscules et des caractères spéciaux.",
        
      ]);
    
      // Cela permet au formulaire de traiter les données soumises et de mettre à jour le mot de passe.
      $userForm->handleRequest($request);
      
      // Soumission du nouveau mot de passe en BDD
      if ($userForm->isSubmitted() && $userForm->isValid()) {
        
        $newPassword = $user->getNewPassword();
        
        // Hachage et envoie du nouveau mot de passe en BDD
        if ($newPassword) {
          $hash = $passwordHasher->hashPassword($user, $newPassword);
          $user->setPassword($hash);
        }
        
        $em->flush();
        $this->addFlash('success', 'Modifications sauvegardées !');
      }
  
      // Afficher le formulaire de la page profil
      return $this->render('user/index.html.twig', [
        'form' => $userForm->createView()
      ]);
    }


    
    // SUPPRIMER SON COMPTE

    #[Route('/delete/{id}', name: 'delete_user')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function deleteProfile(Request $request, EntityManagerInterface $em, $id): Response
    {
      
      // Récupérer l'entité "utilisateur" pour commencer à effectuer la suppresion de son compte d'un grâce à son entité "utilisateur".
      $userRepository = $em->getRepository(User::class);

      // Récupérer l'id de l'utilisateur lié à son entité "utilisateur".
      $user = $userRepository->find($id);
      
      // Cela déconnecte l'utilisateur actuellement connecté en réinitialisant le token d'authentification à null, ce qui permet sa suppresion totale.
      $this->container->get('security.token_storage')->setToken(null);

      // Si l'utilisateur n'est pas trouvé, une exception s'affiche.
      if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé.');
      }

      $em->remove($user);
      $em->flush();

      $this->addFlash('success', 'Votre compte utilisateur a bien été supprimé !');

      return $this->redirectToRoute('app_accueil');
    }
}