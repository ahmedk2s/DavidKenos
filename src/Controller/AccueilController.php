<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsRepository;
use App\Repository\PostRepository;
use App\Entity\User;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(Request $request, NewsRepository $newsRepository, PostRepository $postRepository): Response
    {
        $user = $this->getUser();

        $showNonApprovedAdminPopup = false;

        if ($user instanceof User && $this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN') && !$user->getIsApproved()) {
            $session = $request->getSession();
            if (!$session->get('nonApprovedAdminPopupShown', false)) {
                $showNonApprovedAdminPopup = true;
                $session->set('nonApprovedAdminPopupShown', true);
            }
        }

        $posts = $postRepository->findBy([], ['date_creation' => 'DESC'], 6);
        $news = $newsRepository->findBy([], ['dateCreation' => 'DESC'], 2);

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'posts' => $posts,
            'news' => $news,
            'showNonApprovedAdminPopup' => $showNonApprovedAdminPopup,
        ]);
    }
}
