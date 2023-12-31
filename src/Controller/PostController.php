<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;



class PostController extends AbstractController
{

    // PAGE D'ACCUEIL
    
    #[Route('/', name: 'app_accueil')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        
        $formCreatePost = $this->createPost($request, $em);
        // $commentForm = $this->addComment($post, $em);

        // Poste du plus récent au plus ancien
        $limit = 10;
        $posts = $em->getRepository(Post::class)->findBy([], ['createdAt' => 'DESC'], $limit);

        // "limit" et "offset" détermine la quantité de résultats à récupérer dans une liste paginée
        $limit = $request->query->get('limit', 10);
        $offset = $request->query->get('offset', 0);

        // J'utilise $limit et $offset dans la requête pour récupérer tous les postes
        $posts = $em->getRepository(Post::class)->findBy([], ['createdAt' => 'DESC'], $limit, $offset);

        // Je convertis les postes dans un tableau associatif et je les renvoie en format JSON
        $responseData = [];
        foreach ($posts as $post) {
            $responseData[] = [
                'id' => $post->getId(),
                'author' => $post->getAuthor(),
                'content' => $post->getContent(),
                'imageName' => $post->getImageName(),
                'rating' => $post->getRating(),
                'nbrOfResponse' => $post->getNbrOfResponse(),
            ];
        }
        
        // $comments = $em->getRepository(Comment::class)->findBy([], ['createdAt' => 'DESC']);

        
        // Le rendu de la page d'accueil
        // Affichage du formulaire de la création d'un poste
        return $this->render('accueil/index.html.twig', [
            'formCreatePost' => $formCreatePost->createView(),
            // 'formEditPost' => $formEditPost->createView(),
            // 'commentForm' => $commentForm,
            'posts' => $posts,
            // 'comments' => $comments,
            'data' => json_encode($responseData),
        ]);
    }

    
    
    
    // CRÉATION D'UN POSTE
    
    #[Route('/app_accueil/create_post', name: 'create_post')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function createPost(Request $request, EntityManagerInterface $em): Form
    {
    
        // Création d'un nouveau poste associé à l'utilisateur connecté
        $user = $this->getUser();
        $post = new Post();

        // Création du formulaire pour "Poste"
        $formCreatePost = $this->createForm(PostType::class, $post);
        $formCreatePost->handleRequest($request);

        // Soumission du formulaire de la mise en ligne d'un poste en BDD
        if ($formCreatePost->isSubmitted() && $formCreatePost->isValid()) {
            
            
            // AJOUTER UNE IMAGE À UN POSTE (NON OBLIGATOIRE)
            
            // Cela récupère les données de la nouvelle image téléchargée via le formulaire lors de la création + récupération du nom de l'image associé à l'entité "poste" avant la création
            $imageFile = $formCreatePost['imageFile']->getData();
            $oldImage = $post->getImageFile();
            
            // Vérification si une image a été télécharger + récupère le nom d'origine de l'image
            if ($imageFile) {
                $newFilename = $imageFile->getClientOriginalName();

                // Vérifie si une image avec le même nom existe déjà
                $newImagePath = $this->getParameter('images') . '/' . $newFilename;

                // Si une nouvelle image a été téléchargée et si une image avec le même nom existe déjà sur le serveur alors tu m'affiches un message d'erreur 
                if ($oldImage !== $newFilename && file_exists($newImagePath)) {
                    $this->addFlash('error', 'Une image avec le même nom existe déjà.');
                }

                try {
                    // Cela déplace l'image téléchargé vers le répertoire "uploads"
                    $imageFile->move($this->getParameter('images'), $newFilename);
                    $post->setImageName($newFilename);

                    // Supprime l'ancienne image associée au poste si elle existe
                    if ($oldImage && file_exists($this->getParameter('images') . '/' . $oldImage)) {
                        unlink($this->getParameter('images') . '/' . $oldImage);
                    }
                    
                } catch (FileException $e) {
                    // Gère l'exception si le fichier ne peut pas être déplacé
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
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

        return $formCreatePost;
    }



    
    // MODIFIER UN POSTE
    
    #[Route('/app_accueil/edit_post/{id}', name: 'edit_post')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function editPost(Request $request, EntityManagerInterface $em, $id): Response
    {
    
        // Docblock PHP pour améliorer la documentation et l'analyse statique du code
        /** @var Post $post */

        // Récupérer l'entité d'un poste à l'aide de son id
        $post = $em->getRepository(Post::class)->find($id);
        
        // Création du formulaire pour "Poste"
        $formEditPost = $this->createForm(PostType::class, $post);
        $formEditPost->handleRequest($request);

        // Soumission du formulaire de la modification d'un poste en BDD
        if ($formEditPost->isSubmitted() && $formEditPost->isValid()) {
            
            
            // AJOUTER UNE IMAGE À UN POSTE (NON OBLIGATOIRE)
            
            // Cela récupère les données de la nouvelle image via le formulaire puis récupère le nom de l'image associé à l'entité "poste"

            // Comparaison de l'ancienne image avec la nouvelle pour effectuer la modification
            $imageFile = $formEditPost['imageFile']->getData();
            $oldImage = $post->getImageFile();
            
            // Vérification si une image a été télécharger + récupère le nom d'origine de l'image
            if ($imageFile) {
                $newFilename = $imageFile->getClientOriginalName();

                // Vérifie si une image avec le même nom existe déjà
                $newImagePath = $this->getParameter('images') . '/' . $newFilename;

                // Si une nouvelle image a été téléchargée et si une image avec le même nom existe déjà sur le serveur alors tu m'affiches un message d'erreur 
                if ($oldImage !== $newFilename && file_exists($newImagePath)) {
                    $this->addFlash('error', 'Une image avec le même nom existe déjà.');
                }

                try {
                    // Cela déplace l'image téléchargé vers le répertoire "uploads"
                    $imageFile->move($this->getParameter('images'), $newFilename);
                    $post->setImageName($newFilename);

                    // Supprime l'ancienne image associée au poste si elle existe déjà dans le répertoire
                    if ($oldImage && file_exists($this->getParameter('images') . '/' . $oldImage)) {
                        unlink($this->getParameter('images') . '/' . $oldImage);
                    }
                    
                } catch (FileException $e) {
                    // Gère l'exception si le fichier ne peut pas être déplacé
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    
                }
                
                // Si l'utilisateur ne souhaite pas associé d'image avec l'entité "poste"
                //S'il ne souhaite pas ajouter d'image dans le poste alors cela a une valeur "null"
            } else {
                $post->setImageName(null);
            }
    
            $em->flush();
            $this->addFlash('success', 'Le poste a été modifié avec succès.');
            
            return $this->redirectToRoute('app_accueil');
    
        }

        // Affichage du formulaire de la modification d'un poste
        return $this->render('accueil/editPost.html.twig', [
            'formEditPost' => $formEditPost->createView(),
        ]);
    }

    
    
    // AJOUTER UN COMMENTAIRE À UN POSTE
    
    // #[Route('/app_accueil/comment/{id}', name: 'add_comment')]
    // #[isGranted('IS_AUTHENTICATED_FULLY')]
    // public function addComment(Post $post, EntityManagerInterface $em): Form
    // {
    //     $comment = new Comment();
    //     $commentForm = $this->createForm(CommentType::class, $comment);
    //     $commentForm->handleRequest($request);

    //     if ($commentForm->isSubmitted() && $commentForm->isValid()) {
    //         $postId = $request->get("postId");
    //         $postForm = $em->getRepository(Post::class)->find($postId);
    //         $comment->setCreatedAt(new \DateTimeImmutable());
    //         $comment->setRating(0);
    //         $comment->setAuthor($user);
    //         $comment->setPost($postForm);
            
    //         $em->persist($comment);
    //         $em->flush();
    //         $this->addFlash('success', 'Votre commentaire a bien été mis en ligne');
    //     }

    //     return $commentForm;
    // }
    
    

    
    // SUPPRIMER UN POSTE
    
    #[Route('/app_accueil/delete/{id}', name: 'delete_post')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function delete($id, EntityManagerInterface $em): Response
    {

        if ($id) {

            // Récupérer l'entité "poste" pour commencer à effectuer la suppresion d'un grâce à son entité "poste"
            $postRepository = $em->getRepository(Post::class);
            
            // Récupérer l'id d'un poste lié à son entité "poste" 
            $post = $postRepository->find($id);

            $em->remove($post);
            $em->flush();

            $this->addFlash('success', 'Le poste a été supprimé avec succès.');
            
            // Si le poste n'existe pas alors cela me renvoi un message d'erreur
        } else {
            $this->addFlash('error', 'Le poste n\'existe pas ou vous n\'avez pas la permission de le supprimer.');
        }

        return $this->redirectToRoute('app_accueil');
    }
}