<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog')]

    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $article = $articleRepository->findBy([], ['createdAt' => 'DESC']);
        
        $article = $paginator->paginate(
            $article,
            $request->query->getInt('page', 1),
            10 // items per page
        );
        return $this->render('blog/index.html.twig', [
            'article' => $article,
        ]);
    }
}
