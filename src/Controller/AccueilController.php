<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post; // Assurez-vous d'importer l'entité Post ici

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(NewsRepository $newsRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Récupérez les six derniers posts de la base de données, triés par ordre décroissant de la date de création
        $postRepository = $entityManager->getRepository(Post::class); // Utilisez l'entité Post ici
        $posts = $postRepository->findBy([], ['date_creation' => 'DESC'], 6);

        $news = $newsRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'posts' => $posts,
            'news' => $news,
        ]);
    }
}

