<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_index')]
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $article = $articleRepository->findBy([], ['createdAt' => 'DESC']);

        $article = $paginator->paginate(
            $article,
            $request->query->getInt('page', 1),
            10 // items per page
        );

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
    public function new(Request $request, EntityManagerInterface $entityManager,  Security $security): Response
    {

    $article = new Article();

    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $article->setAuthor($security->getUser());
        $entityManager->persist($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_blog');
    }

    return $this->render('article/new.html.twig', [
        'form' => $form->createView(),
    ]);

}
}