<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Services\ArticleManagerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Config\Framework\RequestConfig;

class BlogController extends AbstractController
{

    private ManagerRegistry $doctrine;
    private ArticleManagerService $articleManager;

    public function __construct(ManagerRegistry $doctrine, ArticleManagerService $articleManager)
    {
        $this->doctrine = $doctrine;
        $this->articleManager = $articleManager;
    }

    /**
     * @Route("/admin/blog", name="admin_blog")
     */
    public function index(): Response
    {
        $articles =  $this->doctrine->getRepository(Article::class)->findAll();
        return $this->render('admin/blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/blog/edit/{id}", name="admin_blog_edit")
     */
    public function edit(Article $article, Request $request): Response
    {
        // Contruction du formulaire grâce au Builder t'as vue c'est magique tout ca tout ca
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->articleManager->update($article);
            return $this->redirectToRoute('admin_blog');
        } else{
            return $this->render('admin/blog/edit.html.twig', [
                'form' => $form->createView()// Magie magie on génére le formulaire
            ]);
        }
    }


    /**
     * @Route("/admin/blog/create", name="admin_blog_create")
     */
    public function create(Request $request): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
            $this->articleManager->insert($article);
            return $this->redirectToRoute('admin_blog');
        } else{
            return $this->render('admin/blog/create.html.twig',[
                'form' => $form->createView()// Magie magie on génére le formulaire
            ]);
        }
    }

    /**
         * @Route("/admin/blog/delete/{id}", name="admin_blog_delete")
     */
    public function delete(Article $article): Response
    {

        $this->articleManager->delete($article);
        return $this->redirectToRoute('admin_blog');;
    }
}
