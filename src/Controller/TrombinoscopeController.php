<?php


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
        // Récupérer tous les utilisateurs depuis le UserRepository
        $users = $userRepository->findAll();  // change here

        return $this->render('trombinoscope/index.html.twig', [
            'users' => $users,
        ]);
    }
}


