<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $articles =  $doctrine->getRepository(Article::class)->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
        ]);
    }
    /**
     * @Route("/blog/{slug}", name="blog_show")
     */
    public function show(Article $article): Response
    {
        // $article =  $doctrine->getRepository(Article::class)->find($id);
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }
}
