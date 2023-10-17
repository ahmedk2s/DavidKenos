<?php

namespace App\Controller;

use App\Entity\ChocolateShop;
use App\Form\ChocolateShopType;
use App\Repository\ChocolateShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chocolate-shop')]
class ChocolateShopController extends AbstractController
{
    #[Route('/', name: 'app_chocolate_shop_index', methods: ['GET'])]
    public function index(ChocolateShopRepository $chocolateShopRepository): Response
    {
        return $this->render('chocolate_shop/index.html.twig', [
            'chocolate_shops' => $chocolateShopRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chocolate_shop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chocolateShop = new ChocolateShop();
        $form = $this->createForm(ChocolateShopType::class, $chocolateShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chocolateShop);
            $entityManager->flush();

            $this->addFlash('success', 'Chocolaterie ajouté avec succès !');

            return $this->redirectToRoute('app_chocolate_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chocolate_shop/new.html.twig', [
            'chocolate_shop' => $chocolateShop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chocolate_shop_show', methods: ['GET'])]
    public function show(ChocolateShop $chocolateShop): Response
    {
        return $this->render('chocolate_shop/show.html.twig', [
            'chocolate_shop' => $chocolateShop,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chocolate_shop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ChocolateShop $chocolateShop, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChocolateShopType::class, $chocolateShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Chocolaterie modifié avec succès !');

            return $this->redirectToRoute('app_chocolate_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chocolate_shop/edit.html.twig', [
            'chocolate_shop' => $chocolateShop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chocolate_shop_delete', methods: ['POST'])]
    public function delete(Request $request, ChocolateShop $chocolateShop, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chocolateShop->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chocolateShop);
            $entityManager->flush();
        }

        $this->addFlash('success', 'La chocolaterie a été supprimé avec succès.');

        return $this->redirectToRoute('app_chocolate_shop_index', [], Response::HTTP_SEE_OTHER);
    }
}
