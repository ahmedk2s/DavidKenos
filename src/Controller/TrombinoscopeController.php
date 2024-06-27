<?php

// src/Controller/TrombinoscopeController.php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrombinoscopeController extends AbstractController
{
    #[Route('/trombinoscope', name: 'app_trombinoscope')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer les 4 derniers utilisateurs depuis le UserRepository
        $users = $userRepository->findLatestUsers(4);

        return $this->render('trombinoscope/index.html.twig', [
            'users' => $users,
        ]);
    }
}

