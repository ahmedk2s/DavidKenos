<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Form\ProfileEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\SlugService;

class ProfileController extends AbstractController
{
    private $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }
    
    #[Route('/profil/{slug}', name: 'profile', defaults: ["slug" => ""])]
    public function showProfile(string $slug, PostRepository $postRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        if (!$slug) {
            $slug = $this->slugService->createUniqueSlug(
                'profile-' . $user->getFirstName() . '-' . $user->getLastName(),
                User::class,
                $user->getId()
            );
            return $this->redirectToRoute('profile', ['slug' => $slug]);
        }

        $posts = $postRepository->findBy(['user' => $user]);

        return $this->render('profile/profil.html.twig', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    #[Route('/modifier-{slug}', name: 'profile_edit')]
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

            $slug = $this->slugService->createUniqueSlug($user->getFirstName() . ' ' . $user->getLastName(), User::class, $user->getId());
            $user->setSlug($slug);

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
