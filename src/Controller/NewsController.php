<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\ChocolateShop;

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
        $user = $this->getUser();

        $news = new News();
        $news->setUser($user);

        if (!in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            // Assigner automatiquement la chocolaterie de l'admin à l'actualité
            $news->setChocolateShop($user->getChocolateShop());
        }

        $form = $this->createForm(NewsType::class, $news, [
            'chocolate_shop_editable' => in_array('ROLE_SUPER_ADMIN', $user->getRoles()),
        ]);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$news->getChocolateShop() && !in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
                $this->addFlash('error', 'La chocolaterie est requise.');
                return $this->redirectToRoute('app_news_index');
            }

            $slug = $this->slugService->createUniqueSlug($news->getTitle(), News::class);
            $news->setSlug($slug);
            $news->setDateEdition(new \DateTime());
            $entityManager->persist($news);
            $entityManager->flush();

            $this->addFlash('success', 'Actualité ajouté !');
            return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }






    #[Route('/modifier-{slug}', name: 'app_news_edit', requirements: ['slug' => '[a-zA-Z0-9\-_]+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, News $news, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $news);
        $user = $this->getUser();

        $form = $this->createForm(NewsType::class, $news, [
            'chocolate_shop_editable' => in_array('ROLE_SUPER_ADMIN', $user->getRoles())
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($news->getTitle(), News::class);
            $news->setSlug($slug);
            $news->setDateEdition(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Actualité modifié !');

            return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-{id}', name: 'app_news_delete', requirements: ['id' => '[a-zA-Z0-9\-_]+'], methods: ['POST'])]
    public function delete(Request $request, News $news, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $news);
        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
            $entityManager->remove($news);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Actualité supprimé.');

        return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
    }
}
