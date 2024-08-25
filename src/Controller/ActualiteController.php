<?php

namespace App\Controller;

use App\Entity\ChocolateShop;
use App\Model\SearchData;
use App\Repository\ChocolateShopRepository;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\SearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ActualiteController extends AbstractController
{
    #[Route('/actualite', name: 'app_actualite')]
    public function index(Request $request, NewsRepository $newsRepository, ChocolateShopRepository $chocolateShopRepository): Response
    {
        $news = $newsRepository->findAll();
        $chocolateShops = $chocolateShopRepository->findAll();

        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $news = $newsRepository->findBySearch($searchData);

            return $this->render('actualite/index.html.twig', [
                'form' => $form,
                'news' => $news,
                'chocolateShops' => $chocolateShops,
            ]);
        }

        return $this->render('actualite/index.html.twig', [
            'controller_name' => 'ActualiteController',
            'news' => $news,
            'chocolateShops' => $chocolateShops,
            'form' => $form->createView(),
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

