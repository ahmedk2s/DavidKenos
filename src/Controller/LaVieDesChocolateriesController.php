<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LaVieDesChocolateriesController extends AbstractController
{
    #[Route('/la-vie-des-chocolateries/', name: 'app_la_vie_des_chocolateries')]
    public function index(CategoryRepository $categoryRepository, PostRepository $postRepository, UserRepository $userRepository, Request $request): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();
        $posts = $postRepository->findAll();
        $latestUsers = $userRepository->findBy([], ['id' => 'DESC'], 5);

        $selectedCategoryId = $request->query->get('category');
        if ($selectedCategoryId) {
            $selectedCategory = $categoryRepository->find($selectedCategoryId);
            $posts = $postRepository->findBy(['category' => $selectedCategory]);
        }

        return $this->render('la_vie_des_chocolateries/la_vie_des_chocolateries.html.twig', [
            'categories' => $categories,
            'posts' => $posts,
            'user' => $user,
            'users' => $latestUsers,
        ]);
    }
}
