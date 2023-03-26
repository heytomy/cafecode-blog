<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_index')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/{slug}', name: 'article_show')]
    public function show(?Article $article): Response
    {
        if (!$article){
            return $this->redirectToRoute('app_blog');
        }
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'article_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_blog');
    }

    return $this->render('article/new.html.twig', [
        'form' => $form->createView(),
    ]);

}
}