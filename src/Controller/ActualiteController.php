<?php

namespace App\Controller;

use App\Entity\ChocolateShop;
use App\Repository\ChocolateShopRepository;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActualiteController extends AbstractController
{
    #[Route('/actualite', name: 'app_actualite')]
    public function index(NewsRepository $newsRepository, ChocolateShopRepository $chocolateShopRepository): Response
    {
        $news = $newsRepository->findAll();
        $chocolateShops = $chocolateShopRepository->findAll();

        return $this->render('actualite/index.html.twig', [
            'controller_name' => 'ActualiteController',
            'news' => $news,
            'chocolateShops' => $chocolateShops
        ]);
    }

    #[Route('/actualite/{id}/chocolaterie', name: 'actualite_by_chocolaterie')]
    public function showByChocolaterie($id, NewsRepository $newsRepository): Response
    {
        $chocolateShop = $this->getDoctrine()->getRepository(ChocolateShop::class)->find($id);

        if (!$chocolateShop) {
            throw $this->createNotFoundException('Chocolaterie non trouvée');
        }

        $news = $newsRepository->findBy(['chocolateShop' => $chocolateShop]);

        return $this->render('actualite/chocolaterie_news.html.twig', [
            'chocolateShop' => $chocolateShop,
            'news' => $news,
        ]);
    }
}
