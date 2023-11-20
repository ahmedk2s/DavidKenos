<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Form\ProfileEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'profile')]
    public function showProfile(PostRepository $postRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $posts = $postRepository->findBy(['user' => $user]);

        return $this->render('profile/profil.html.twig', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    #[Route('/profil/edit', name: 'profile_edit')]
public function editProfile(Request $request, EntityManagerInterface $entityManager): Response {
    $user = $this->getUser();
    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    $form = $this->createForm(ProfileEditType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // /** @var UploadedFile $profilePictureFile */
        $profilePictureFile = $form->get('profilePictureFilename')->getData();

        if ($profilePictureFile) {
            $newFilename = uniqid().'.'.$profilePictureFile->guessExtension();

            try {
                $profilePictureFile->move(
                    $this->getParameter('products'), // Assurez-vous de configurer ce paramètre
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
            }

            $user->setProfilePictureFilename($newFilename);
        }

         $coverPictureFile = $form->get('coverPictureFilename')->getData();

        if ($coverPictureFile) {
            $newFilename = uniqid().'.'.$coverPictureFile->guessExtension();

            try {
                $coverPictureFile->move(
                    $this->getParameter('products'), // Assurez-vous de configurer ce paramètre
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
            }

            $user->setCoverPictureFilename($newFilename);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre profil a été mis à jour.');
        return $this->redirectToRoute('profile');
    }

    return $this->render('profile/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
