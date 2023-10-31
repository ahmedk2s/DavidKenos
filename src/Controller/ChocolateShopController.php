<?php

namespace App\Controller;

use App\Entity\ChocolateShop;
use App\Form\ChocolateShopType;
use App\Service\SlugService;
use App\Repository\ChocolateShopRepository;
use App\Security\Voter\ChocolateShopVoter;
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
        $this->denyAccessUnlessGranted(ChocolateShopVoter::VIEW);

        return $this->render('chocolate_shop/index.html.twig', [
            'chocolate_shops' => $chocolateShopRepository->findAll(),
        ]);
    }

    #[Route('/nouvelle-chocolaterie', name: 'app_chocolate_shop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(ChocolateShopVoter::CREATE);

        $chocolateShop = new ChocolateShop();
        $form = $this->createForm(ChocolateShopType::class, $chocolateShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($chocolateShop->getCity(), ChocolateShop::class);
            $chocolateShop->setSlug($slug);

            $entityManager->persist($chocolateShop);
            $entityManager->flush();

            return $this->redirectToRoute('app_chocolate_shop_index');
        }

        return $this->render('chocolate_shop/new.html.twig', [
            'chocolate_shop' => $chocolateShop,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier-{slug}', name: 'app_chocolate_shop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ChocolateShop $chocolateShop, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(ChocolateShopVoter::EDIT, $chocolateShop);

        $form = $this->createForm(ChocolateShopType::class, $chocolateShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($chocolateShop->getCity(), ChocolateShop::class);
            $chocolateShop->setSlug($slug);

            $entityManager->flush();

            return $this->redirectToRoute('app_chocolate_shop_index');
        }

        return $this->render('chocolate_shop/edit.html.twig', [
            'chocolate_shop' => $chocolateShop,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-{id}', name: 'app_chocolate_shop_delete', methods: ['POST'])]
    public function delete(Request $request, ChocolateShop $chocolateShop, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(ChocolateShopVoter::DELETE, $chocolateShop);

        if ($this->isCsrfTokenValid('delete' . $chocolateShop->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chocolateShop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chocolate_shop_index');
    }
}
