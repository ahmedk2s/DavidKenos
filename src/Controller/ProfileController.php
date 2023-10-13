<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'profile')]
    public function showProfile(PostRepository $postRepository): Response
    {
        // Récupérez l'utilisateur connecté 
        $user = $this->getUser();

        $posts = $postRepository->findAll();
        

        return $this->render('profile/profil.html.twig', [
            'user' => $user,
            'posts' => $posts
        ]);
    }
}
