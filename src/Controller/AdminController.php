<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\Voter\UserVoter;
use App\Service\SlugService;
use App\Repository\UserRepository;
use App\Repository\NewsRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use App\Repository\ChocolateShopRepository;
use App\Repository\CommentRepository;
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
        CategoryRepository $categoryRepository,
        ChocolateShopRepository $chocolateShopRepository,
        CommentRepository $commentRepository
    ): Response {
        $user = $this->getUser();

        // Redirection si l'administrateur n'est pas approuvé
        if ($this->isGranted('ROLE_ADMIN') && !$user->getIsApproved()) {
            return $this->redirectToRoute('app_accueil');
        }

        // Vérifiez si l'utilisateur a le droit d'accéder à l'administration
        $this->denyAccessUnlessGranted(UserVoter::ACCESS_ADMIN, $user);

        // Vérifier si des administrateurs sont en attente de validation
        $isAdminWaitingForApproval = false;
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $adminsWaitingForApproval = $userRepository->findBy(['isApproved' => false, 'roles' => 'ROLE_ADMIN']);
            $isAdminWaitingForApproval = count($adminsWaitingForApproval) > 0;

            // Après la requête
            error_log(print_r($adminsWaitingForApproval, true));

        }

        if (!$slug) {
            $slug = $this->slugService->createUniqueSlug(
                'accueil-administration-' . $user->getFirstName() . '-' . $user->getLastName(),
                User::class,
                $user->getId()
            );
            return $this->redirectToRoute('app_admin', ['slug' => $slug]);
        }



        // Collecte des données pour l'affichage
        $registeredUsersCount = $this->isGranted('ROLE_SUPER_ADMIN')
            ? $userRepository->countAllRegisteredUsers()
            : $userRepository->countUsersByChocolateShop($user->getChocolateShop());

        $newsCount = $newsRepository->countAllNews([]);
        $postsCount = $postRepository->countAllPosts();
        $categoryCount = $categoryRepository->countAllCategory([]);
        $chocolateCount = $chocolateShopRepository->countAllChocolate([]);
        $commentCount = $commentRepository->countAllComments();
        $latestNews = $newsRepository->findLatest(3);



        // Rendu de la vue avec les données collectées
        return $this->render('administration/admin.html.twig', [
            'controller_name' => 'AdminController',
            'user' => $user,
            'registeredUsersCount' => $registeredUsersCount,
            'newsCount' => $newsCount,
            'postsCount' => $postsCount,
            'categoryCount' => $categoryCount,
            'chocolateCount' => $chocolateCount,
            'commentCount' => $commentCount,
            'latestNews' => $latestNews,
            'isAdminWaitingForApproval' => $isAdminWaitingForApproval,
        ]);
    }
}
