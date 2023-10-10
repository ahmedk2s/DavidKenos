<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;


class LikeController extends AbstractController
{
    #[Route('/Like/post/{id}', name: 'like.post')]
    public function like(\App\Entity\Post $post, EntityManagerInterface $manager): Response 
{
    $user = $this->getUser();

    // Vérifie si l'utilisateur a déjà aimé le post
    if ($post->isLikedByUser($user)) {
        // Supprimez le like existant de l'utilisateur
        $existingLike = $post->getLikes()->filter(function (\App\Entity\Like $like) use ($user) {
            return $like->getUser() === $user;
        })->first();

        $post->removeLike($existingLike);
        $manager->remove($existingLike); // Supprimez le like de la base de données
        $manager->flush();

        return $this->json(['message' => 'Le Like a été supprimé.']);
    }

    // Créez un nouvel objet Like
    $like = new \App\Entity\Like();
    $like->setUser($user);
    $like->setPost($post);
    
    // Ajoutez le like au post
    $post->addLike($like);
    
    // Persistez les changements
    $manager->persist($like);
    $manager->flush();
    
    return $this->json(['message' => 'Le Like a été ajouté.']);
}
}
