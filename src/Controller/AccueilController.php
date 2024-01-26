<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, NewsRepository $newsRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Initialiser les variables pour les popups
        $showApprovalPopup = false;
        $showNonApprovedAdminPopup = false;

        if ($user && $this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            $session = $request->getSession();

            if ($user->getIsApproved()) {
                if (!$session->get('wasRecentlyApproved', false)) {
                    $showApprovalPopup = true;
                    $session->set('wasRecentlyApproved', true);
                }
            } else {
                if (!$session->get('nonApprovedAdminPopupShown', false)) {
                    $showNonApprovedAdminPopup = true;
                    $session->set('nonApprovedAdminPopupShown', true);
                }
            }
        }

        // Récupérer les posts et les news pour les afficher
        $postRepository = $entityManager->getRepository(Post::class);
        $posts = $postRepository->findBy([], ['date_creation' => 'DESC'], 6);

        $newsRepository = $entityManager->getRepository(News::class);
        $news = $newsRepository->findBy([], ['dateCreation' => 'DESC'], 2);

        // Rendre la vue avec les données et les variables des popups
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'posts' => $posts,
            'news' => $news,
            'showApprovalPopup' => $showApprovalPopup,
            'showNonApprovedAdminPopup' => $showNonApprovedAdminPopup,
        ]);
    }
}
