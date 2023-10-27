<?php

namespace App\Controller;

use App\Entity\ChocolateShop;
use App\Form\ChocolateShopType;
use App\Form\PostType;
use App\Service\SlugService;
use App\Repository\ChocolateShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/chocolaterie')]
class ChocolateShopController extends AbstractController
{

    private $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    #[Route('/index-chocolaterie', name: 'app_chocolate_shop_index', methods: ['GET'])]
    public function index(ChocolateShopRepository $chocolateShopRepository): Response
    {
        $user = $this->getUser();

        return $this->render('chocolate_shop/index.html.twig', [
            'chocolate_shops' => $chocolateShopRepository->findAll(),
            'user' => $user,
        ]);
    }

    #[Route('/nouvelle-chocolaterie', name: 'app_chocolate_shop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chocolateShop = new ChocolateShop();
        $form = $this->createForm(ChocolateShopType::class, $chocolateShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($chocolateShop->getCity(), ChocolateShop::class);
            $chocolateShop->setSlug($slug);
            $entityManager->persist($chocolateShop);
            $entityManager->flush();

            $this->addFlash('success', 'Chocolaterie ajouté !');

            return $this->redirectToRoute('app_chocolate_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chocolate_shop/new.html.twig', [
            'chocolate_shop' => $chocolateShop,
            'form' => $form,
        ]);
    }


    #[Route('/modifier-{slug}', name: 'app_chocolate_shop_edit', requirements: ['slug' => '[a-zA-Z0-9\-_]+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, ChocolateShop $chocolateShop, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChocolateShopType::class, $chocolateShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($chocolateShop->getCity(), ChocolateShop::class);
            $chocolateShop->setSlug($slug);
            $entityManager->flush();

            $this->addFlash('success', 'Chocolaterie modifié !');

            return $this->redirectToRoute('app_chocolate_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chocolate_shop/edit.html.twig', [
            'chocolate_shop' => $chocolateShop,
            'form' => $form,
        ]);
    }

    #[Route('/supprimer-{id}', name: 'app_chocolate_shop_delete', requirements: ['id' => '[a-zA-Z0-9\-_]+'], methods: ['POST'])]
    public function delete(Request $request, ChocolateShop $chocolateShop, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chocolateShop->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chocolateShop);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Chocolaterie supprimé.');

        return $this->redirectToRoute('app_chocolate_shop_index', [], Response::HTTP_SEE_OTHER);
    }
}
