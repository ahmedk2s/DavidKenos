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
use Doctrine\ORM\EntityManagerInterface;

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
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN') && !$user->getIsApproved()) {
            return $this->redirectToRoute('app_accueil');
        }

        $this->denyAccessUnlessGranted(UserVoter::ACCESS_ADMIN, $user);

        $isAdminWaitingForApproval = false;
        $adminsWaitingForApproval = [];

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $adminsWaitingForApproval = $userRepository->findAdminsWaitingForApproval();
            $isAdminWaitingForApproval = count($adminsWaitingForApproval) > 0;
        }

        $showApprovalPopup = false;
        if ($user instanceof User && $user->getIsApproved() && !$user->getHasSeenApprovalPopup()) {
            $showApprovalPopup = true;
        }

        if (!$slug) {
            $slug = $this->slugService->createUniqueSlug(
                'accueil-administration-' . $user->getFirstName() . '-' . $user->getLastName(),
                User::class,
                $user->getId()
            );
            return $this->redirectToRoute('app_admin', ['slug' => $slug]);
        }

        $registeredUsersCount = $this->isGranted('ROLE_SUPER_ADMIN')
            ? $userRepository->countAllRegisteredUsers()
            : $userRepository->countUsersByChocolateShop($user->getChocolateShop());
        $newsCount = $newsRepository->countAllNews([]);
        $postsCount = $postRepository->countAllPosts();
        $categoryCount = $categoryRepository->countAllCategory([]);
        $chocolateCount = $chocolateShopRepository->countAllChocolate([]);
        $commentCount = $commentRepository->countAllComments();
        $latestNews = $newsRepository->findLatest(3);

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
            'showApprovalPopup' => $showApprovalPopup,
            'isAdminWaitingForApproval' => $isAdminWaitingForApproval,
            'adminsWaitingForApproval' => $adminsWaitingForApproval,
        ]);
    }

    #[Route('/confirm-approval-popup', name: 'confirm_approval_popup')]
    public function confirmApprovalPopup(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user instanceof User && $user->getIsApproved() && !$user->getHasSeenApprovalPopup()) {
            $user->setHasSeenApprovalPopup(true);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return new Response(null, Response::HTTP_OK);
    }
}

