<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\NewsRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        UserRepository $userRepository,
        NewsRepository $newsRepository,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository
    ): Response {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Récupérer le nombre d'utilisateurs inscrits
        $registeredUsersCount = $userRepository->countAllRegisteredUsers(); // Assurez-vous d'avoir cette méthode dans votre UserRepository

        // Récupérer le nombre d'actualités
        $newsCount = $newsRepository->countAllNews([]);

        // Récupérer le nombre total de posts en utilisant votre méthode personnalisée
        $postsCount = $postRepository->countAllPosts();

        // Récupérer le nombre de catégories
        $categoryCount = $categoryRepository->countAllCategory([]);

        return $this->render('administration/admin.html.twig', [
            'controller_name' => 'AdminController',
            'user' => $user,
            'registeredUsersCount' => $registeredUsersCount, 
            'newsCount' => $newsCount,
            'postsCount' => $postsCount,
            'categoryCount' => $categoryCount,
        ]);
    }
}

