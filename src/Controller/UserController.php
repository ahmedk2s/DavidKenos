<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use App\Service\SlugService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/utilisateurs')]
class UserController extends AbstractController
{
    private SlugService $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    #[Route('/index-des-utilisateurs', name: 'app_user_index', methods: ['GET'])]
public function index(UserRepository $userRepository): Response
{
    $user = $this->getUser();

    if ($this->isGranted('ROLE_SUPER_ADMIN')) {
        $users = $userRepository->findAll();
    } elseif ($this->isGranted('ROLE_ADMIN')) {
        // Récupérer tous les utilisateurs de la chocolaterie sauf les super administrateurs
        $users = $userRepository->findUsersByRoleAndChocolateShop('ROLE_SUPER_ADMIN', $user->getChocolateShop(), true);
    } else {
        throw $this->createAccessDeniedException('Vous n\'avez pas la permission de voir cette page.');
    }

    return $this->render('user/index.html.twig', [
        'users' => $users,
        'user' => $user,
    ]);
}



    #[Route('/creer-utlisateur', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $user = new User();
        $form = $this->createForm(UserCreateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $slug = $this->slugService->createUniqueSlug($user->getFirstName() . ' ' . $user->getLastName(), User::class);
            $user->setSlug($slug);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur ajouté !');
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier-{slug}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($user->getFirstName() . ' ' . $user->getLastName(), User::class, $user->getId());
            $user->setSlug($slug);

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié!');
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé.');
        }

        return $this->redirectToRoute('app_user_index');
    }
}
