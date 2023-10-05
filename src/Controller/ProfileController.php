<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profil", name="profile")
     */
    public function showProfile(): Response
    {
        // Récupérez l'utilisateur connecté 
        $user = $this->getUser();


        return $this->render('profile/show.html.twig', [
            'user' => $user, 
        ]);
    }
}
