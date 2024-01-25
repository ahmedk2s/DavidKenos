<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Entity\News;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(NewsRepository $newsRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Déterminez si l'utilisateur est un administrateur non approuvé
        $isAdminNonApproved = $user && $this->isGranted('ROLE_ADMIN') && !$user->getIsApproved();

        $postRepository = $entityManager->getRepository(Post::class);
        $posts = $postRepository->findBy([], ['date_creation' => 'DESC'], 6);

        $newsRepository = $entityManager->getRepository(News::class);
        $news = $newsRepository->findBy([], ['dateCreation' => 'DESC'], 2);

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'posts' => $posts,
            'news' => $news,
            'isAdminNonApproved' => $isAdminNonApproved,
        ]);
    }
}

