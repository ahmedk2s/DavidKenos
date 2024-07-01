<?php


namespace App\Controller;

use App\Form\SearchType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrombinoscopeController extends AbstractController
{
    #[Route('/trombinoscope', name: 'app_trombinoscope')]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $loggedInUser = $this->getUser(); // Récupérer l'utilisateur connecté

        // Créer le formulaire de recherche
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $users = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('q')->getData();
            $users = $userRepository->findByFirstnameOrLastname($query);
        } else {
            // Récupérer tous les utilisateurs sauf l'utilisateur connecté
            $users = $userRepository->findAllExceptLoggedInUser($loggedInUser);
        }

        return $this->render('trombinoscope/index.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
        ]);
    }
}



