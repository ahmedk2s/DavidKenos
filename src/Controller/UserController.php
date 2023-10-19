<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;




#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'user' => $user,
        ]);
    }


#[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])] 
public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
{
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

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur ajouté avec succès !');

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('user/new.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);
}


    #[Route('/utilisateur-{id}', name: 'app_user_show', requirements:['id' => '[a-zA-Z0-9\-_]+'], methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/utilisateur-{id}/edit', name: 'app_user_edit', requirements:['id' => '[a-zA-Z0-9\-_]+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès !');

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/utilisateur-{id}', name: 'app_user_delete', requirements:['id' => '[a-zA-Z0-9\-_]+'], methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
