<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SlugService;
use App\Repository\UserRepository;
use App\Repository\NewsRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    #[Route('/admin/{slug}', name: 'app_admin', defaults: ["slug" => ""])]
    public function index(
        string $slug,
        UserRepository $userRepository,
        NewsRepository $newsRepository,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $user = $this->getUser();

        // Si aucun slug n'est fourni, crée un slug unique pour l'accueil de l'administration
        if (!$slug) {
            $slug = $this->slugService->createUniqueSlug(
                'accueil-administration-' . $user->getFirstName() . '-' . $user->getLastName(),
                User::class,
                $user->getId()
            );
            return $this->redirectToRoute('app_admin', ['slug' => $slug]);
        }
        
        $registeredUsersCount = $userRepository->countAllRegisteredUsers(); 
        
        $newsCount = $newsRepository->countAllNews([]);
       
        $postsCount = $postRepository->countAllPosts();
        
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
