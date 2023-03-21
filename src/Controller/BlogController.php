<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog')]

    public function index(ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findBy([], ['createdAt' => 'DESC'], 10);
        
        return $this->render('blog/index.html.twig', [
            'article' => $article,
        ]);
    }
}
