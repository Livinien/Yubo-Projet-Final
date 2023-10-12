<?php

namespace App\Controller;

use App\Form\PostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(Request $request): Response
    {

        // Création du formulaire pour la création de post
        $formPost = $this->createForm(PostType::class);
        
        // Connecter le formPost à la requête pour récupérer le résultat une fois que l'utilisateur aura soumit le post
        $formPost->handleRequest($request);

        if ($formPost->isSubmitted() && $formPost->isValid()) {
        }

        
        $posts = [
            
            [   
                'id' => '1',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos rerum accusamus sit modi fugiat dicta eos deleniti impedit, architecto reprehenderit tenetur? Harum totam illum voluptatem molestias nulla beatae, cum ex ?',
                'image' => '',
                'rating' => '20',
                'author'=> [
                    'name' => 'Flavien MAYET',
                    'avatar' => 'https://randomuser.me/api/portraits/men/22.jpg'
                ],
                'nbrOfResponse' => 15
            ],
            [   
                'id' => '2',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos rerum accusamus sit modi fugiat dicta eos deleniti impedit, architecto reprehenderit tenetur? Harum totam illum voluptatem molestias nulla beatae, cum ex ?',
                'image' => '',
                'rating' => '0',
                'author'=> [
                    'name' => 'Yin Yon',
                    'avatar' => 'https://randomuser.me/api/portraits/women/17.jpg'
                ],
                'nbrOfResponse' => 15
            ],
            [   
                'id' => '3',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos rerum accusamus sit modi fugiat dicta eos deleniti impedit, architecto reprehenderit tenetur? Harum totam illum voluptatem molestias nulla beatae, cum ex ?',
                'image' => '',
                'rating' => '-15',
                'author'=> [
                    'name' => 'Julie Doe',
                    'avatar' => 'https://randomuser.me/api/portraits/women/3.jpg'
                ],
                'nbrOfResponse' => 15
            ],
        ];
        
        
        return $this->render('accueil/index.html.twig', [
            'form' => $formPost->createView(),
            'posts' => $posts,
        ]);
    }




    #[Route('/{id}', name: 'app_comment')]
    public function comment(Request $request, string $id): Response
    {
        $posts = [
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit...',
            'image' => '',
            'rating' => '20',
            'author' => [
                'name' => 'Flavien MAYET',
                'avatar' => 'https://randomuser.me/api/portraits/men/22.jpg',
            ],
            'nbrOfResponse' => 15,
        ];
    
        return $this->render('accueil/comment.html.twig', [
            'posts' => $posts,
        ]);
    }
}