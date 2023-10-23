<?php

namespace App\Controller;

use App\Entity\Post;
// use App\Form\EditType;
use App\Form\PostType;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        // POST
        
        // CRÉATION D'UN POST
        $user = $this->getUser();
        $post = new Post();

        $formCreatePost = $this->createForm(PostType::class, $post);
        $formCreatePost->handleRequest($request);

        if ($formCreatePost->isSubmitted() && $formCreatePost->isValid()) {
            
            
            // AJOUTER UNE IMAGE À UN POST (NON OBLIGATOIRE)
            $imageFile = $formCreatePost['image']->getData();
            $oldImage = $post->getImage();
            
            if ($imageFile) {
                $newFilename = $imageFile->getClientOriginalName();

                // Vérifiez si une image avec le même nom existe déjà
                $newImagePath = $this->getParameter('images') . '/' . $newFilename;

                if ($oldImage !== $newFilename && file_exists($newImagePath)) {
                    // Une image avec le même nom existe déjà, ne la remplacez pas
                    $this->addFlash('error', 'Une image avec le même nom existe déjà.');
                }

                try {
                    $imageFile->move($this->getParameter('images'), $newFilename);
                    $post->setImage($newFilename);

                    // Supprimez l'ancienne image associée au post si elle existe
                    if ($oldImage && file_exists($this->getParameter('images') . '/' . $oldImage)) {
                        unlink($this->getParameter('images') . '/' . $oldImage);
                    }
                    
                } catch (FileException $e) {
                    // Gérez l'exception si le fichier ne peut pas être déplacé
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    // Redirigez vers le formulaire en cas d'erreur
                }
            }

            

            $post->setNbrOfResponse(0);
            $post->setRating(0);
            $post->setAuthor($user);
            $post->setCreatedAt(new \DateTimeImmutable());

            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Votre poste est maintenant en ligne');
        }

        
        // MODIFIER UN POST
    
        // $formEditPost = $this->createForm(EditType::class, $post);
        // $formEditPost->handleRequest($request);
    
        // if ($formEditPost->isSubmitted() && $formEditPost->isValid()) {
        //     // Traitez les modifications du post ici, par exemple, la mise à jour de l'image.
    
        //     $imageFile = $formEditPost['image']->getData();
        //     $oldImage = $post->getImage();
            
        //     if ($imageFile) {
        //         $newFilename = $imageFile->getClientOriginalName();
    
        //         // Vérifiez si une image avec le même nom existe déjà
        //         $newImagePath = $this->getParameter('images') . '/' . $newFilename;
    
        //         if ($oldImage !== $newFilename and file_exists($newImagePath)) {
        //             $this->addFlash('error', 'Une image avec le même nom existe déjà.');
        //         }
    
        //         try {
        //             $imageFile->move($this->getParameter('images'), $newFilename);
        //             $post->setImage($newFilename);
    
        //             if ($oldImage and file_exists($this->getParameter('images') . '/' . $oldImage)) {
        //                 unlink($this->getParameter('images') . '/' . $oldImage);
        //             }
        //         } catch (FileException $e) {
        //             $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
        //         }
        //     }
    
        //     // Enregistrez les modifications dans la base de données
        //     $em->flush();
    
        //     $this->addFlash('success', 'Le post a été modifié avec succès.');
    
        //     return $this->redirectToRoute('app_accueil');
        // }

       

        // RÉCUPÉRER TOUTES LES INFORMATIONS D'UN POST DANS LA BASE DE DONNÉES ET TRIÉ DANS L'ORDRE DU PLUS RÉCENT AU PLUS ANCIEN EN FONCTION DE LA DATE DU POST.
        $posts = $em->getRepository(Post::class)->findBy([], ['createdAt' => 'DESC']);
        $comments = $em->getRepository(Comment::class)->findBy([], ['createdAt' => 'DESC']);
        
        

        // AJOUTER UN COMMENTAIRE À UN POST
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $postId = $request->get("postId");
            $postForm = $em->getRepository(Post::class)->find($postId);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setRating(0);
            $comment->setAuthor($user);
            $comment->setPost($postForm);
            
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a bien été mis en ligne');
        }
        
        // dd($comments);
        
        return $this->render('accueil/index.html.twig', [
            'formCreatePost' => $formCreatePost->createView(),
            // 'formEditPost' => $formEditPost->createView(),
            'commentForm' => $commentForm,
            'posts' => $posts,
            'comments' => $comments,
        ]);
    }



    

    // SUPPRIMER UN POST D'UN UTILISATEUR GRÂCE À SON ID
    #[Route('/app_accueil/{id}', name: 'delete_post')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Post $post, EntityManagerInterface $em): RedirectResponse
    {

        if ($post) {

            // Supprimez le post de la base de données.
            $em->remove($post);
            $em->flush();

            $this->addFlash('success', 'Le post a été supprimé avec succès.');
            
        } else {
            $this->addFlash('error', 'Le post n\'existe pas ou vous n\'avez pas la permission de le supprimer.');
        }

        return $this->redirectToRoute('app_accueil');
    }

   
}