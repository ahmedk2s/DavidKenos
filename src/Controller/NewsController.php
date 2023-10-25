<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Service\SlugService;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/actualites')]
class NewsController extends AbstractController
{
    private $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    #[Route('/index-des-actualites', name: 'app_news_index', methods: ['GET'])]
    public function index(NewsRepository $newsRepository): Response
    {
        $user = $this->getUser();

        return $this->render('news/index.html.twig', [
            'news' => $newsRepository->findAll(),
            'user' => $user,
        ]);
    }

    #[Route('/nouvelle-actualite', name: 'app_news_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $news = new News();
        $news->setUser($this->getUser());
        $news->setDateCreation(new \DateTime());
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($news->getTitle(), News::class);
            $news->setSlug($slug);
            $news->setDateEdition(new \DateTime());
            $entityManager->persist($news);
            $entityManager->flush();

            $this->addFlash('success', 'Actualité ajouté avec succès !');

            return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/voir-{slug}', name: 'app_news_show', requirements: ['slug' => '[a-zA-Z0-9\-_]+'], methods: ['GET'])]
    public function show(News $news): Response
    {
        $dateCreation = $news->getDateCreation();
        if ($dateCreation) {
            $dateCreation = $dateCreation->format('d/m/Y');
        } else {
            $dateCreation = '';
        }

        $dateEdition = $news->getDateEdition();
        if ($dateEdition) {
            $dateEdition = $dateEdition->format('d/m/Y');
        } else {
            $dateEdition = '';
        }

        return $this->render('news/show.html.twig', [
            'news' => $news,
            'dateCreation' => $dateCreation,
            'dateEdition' => $dateEdition,
        ]);
    }

    #[Route('/modifier-{slug}', name: 'app_news_edit', requirements: ['slug' => '[a-zA-Z0-9\-_]+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, News $news, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $news->setDateEdition(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Actualité modifié avec succès !');

            return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-{slug}', name: 'app_news_delete', requirements: ['slug' => '[a-zA-Z0-9\-_]+'], methods: ['POST'])]
    public function delete(Request $request, News $news, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
            $entityManager->remove($news);
            $entityManager->flush();
        }

        $this->addFlash('success', 'L\actualité a été supprimé avec succès.');

        return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
    }
}
