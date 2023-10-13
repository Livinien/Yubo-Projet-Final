<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        // POST
        
        // CRÉATION D'UN POST
        $post = new Post();
        
        $formCreatePost = $this->createForm(PostType::class, $post);
        $formCreatePost->handleRequest($request);
        if ($formCreatePost->isSubmitted() && $formCreatePost->isValid()) {
            
            $post->setNbrOfResponse(0);
            $post->setRating(0);
            $post->setCreatedAt(new \DateTimeImmutable());
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Votre poste est maintenant en ligne');
        }

        // SOUMETTRE LE FORMULAIRE POUR LA MODIFICATION DE POST
        $formEditPost = $this->createForm(PostType::class);
        $formEditPost->handleRequest($request);
        if ($formEditPost->isSubmitted() && $formEditPost->isValid()) {
            
        }

        // RÉCUPÉRER TOUTES LES INFORMATIONS D'UN POST DANS LA BASE DE DONNÉES ET TRIÉ DANS L'ORDRE DU PLUS RÉCENT AU PLUS ANCIEN EN FONCTION DE LA DATE DU POST.
        $posts = $em->getRepository(Post::class)->findBy([], ['createdAt' => 'DESC']);


        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setRating(0);
            $content = $request->request->get('content');
            $comment->setContent($content);
            $comment->setPost($post);
            $em->persist($post);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a bien été mis en ligne');
        }

        
        return $this->render('accueil/index.html.twig', [
            'formCreatePost' => $formCreatePost->createView(),
            'formEditPost' => $formEditPost->createView(),
            'commentForm' => $commentForm,
            'posts' => $posts,
        ]);
    }

    

    // SUPPRIMER UN POST D'UN UTILISATEUR GRÂCE À SON ID
    #[Route('/deletePost/{id}', name: 'app_delete_post')]
    public function delete(Post $post, EntityManagerInterface $em): RedirectResponse
    {

        if ($post) {
            $em->remove($post);
            $em->flush();

            $this->addFlash('success', 'Le post a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Le post n\'existe pas ou vous n\'avez pas la permission de le supprimer.');
        }

        return $this->redirectToRoute('app_accueil');
    }



    
    
    // COMMENTAIRE
    
    // AJOUTER UN COMMENTAIRE À UN POST
    #[Route('addComment/{id}', name: 'app_comment')]
    public function comment(Request $request, Post $post, EntityManagerInterface $em): Response
    {
        
        

        return $this->render('accueil/comment.html.twig', [
            'post' => $post,
            
        ]);
    }

    
   
}