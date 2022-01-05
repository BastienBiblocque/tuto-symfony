<?php

    namespace App\Services;
    use App\Entity\Article;
    use App\Repository\ArticleRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\String\Slugger\AsciiSlugger;

    class  ArticleManagerService{

    private EntityManagerInterface $entityManager;
    private ArticleRepository $repo;


    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $repo){
        $this->entityManager = $entityManager;
        $this->repo = $repo;
    }

    public function insert(Article $article){
        $this->entityManager->persist($this->slugify($article));
        $this->entityManager->flush();
    }
    public function update(Article $article){
        $article = $this->slugify($article);
        $this->entityManager->flush();
    }
    public function delete(Article $article){
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }

    public function slugify(Article $article){
        $slugger = new AsciiSlugger();
        $title = $article->getTitle();
        $article->setSlug($slugger->slug($title));

        return $article;
    }
}
