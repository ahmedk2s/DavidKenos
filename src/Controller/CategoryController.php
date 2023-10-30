<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\SlugService;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{

     private $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    #[Route('/index-des-categories', name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'user' => $user,
        ]);
    }

    #[Route('/nouveau', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($category->getName(), Category::class);
            $category->setSlug($slug);
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie ajouté !');

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }


    #[Route('/modifier-{slug}', name: 'app_category_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Category $category, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
{
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $slug = $this->slugService->createUniqueSlug($category->getName(), Category::class);
        $category->setSlug($slug);
        $entityManager->flush();

        $this->addFlash('success', 'Catégorie modifié !');

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }

    $categories = $categoryRepository->findAll(); 

    return $this->render('category/edit.html.twig', [
        'category' => $category,
        'form' => $form,
        'categories' => $categories, 
    ]);
}


    #[Route('/supprimer-{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Catégorie supprimé.');

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
