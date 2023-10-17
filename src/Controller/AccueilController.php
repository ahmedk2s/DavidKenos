<?php

namespace App\Controller;

// use App\Repository\UserRepository;
// use App\Repository\NewsRepository;
// use App\Repository\PostRepository;
// use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(
        // UserRepository $userRepository,
        // NewsRepository $newsRepository,
        // PostRepository $postRepository,
        // CategoryRepository $categoryRepository
    ): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Récupérer le nombre d'utilisateurs connectés
        // $connectedUsersCount = $userRepository->countConnectedUsers();

        // Récupérer le nombre d'actualités
        // $newsCount = $newsRepository->count([]);

        // Récupérer le nombre de posts
        // $postCount = $postRepository->count([]);

        // Récupérer le nombre de catégories
        // $categoryCount = $categoryRepository->count([]);

        return $this->render('administration/accueil.html.twig', [
            'controller_name' => 'AccueilController',
            'user' => $user,
            // 'connectedUsersCount' => $connectedUsersCount,
            // 'newsCount' => $newsCount,
            // 'postCount' => $postCount,
            // 'categoryCount' => $categoryCount,
        ]);
    }
}
