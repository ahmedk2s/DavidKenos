<?php

namespace App\Controller;

use App\Repository\ChocolateShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsRepository;

class ActualiteController extends AbstractController
{
    #[Route('/actualite', name: 'app_actualite')]
    public function index(NewsRepository $newsRepository, ChocolateShopRepository $chocolateShopRepository,): Response
    {

        $news = $newsRepository->findAll();
        $chocolateShops = $chocolateShopRepository->findAll();

        return $this->render('actualite/index.html.twig', [
            'controller_name' => 'ActualiteController',
            'news' => $news,
            'chocolateShops' => $chocolateShops 
        ]);
    }
}
