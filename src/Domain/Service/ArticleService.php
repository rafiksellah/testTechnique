<?php

namespace App\Domain\Service;

use App\Domain\Entity\Article;
use Symfony\Bundle\SecurityBundle\Security;
use App\Domain\Repository\ArticleRepository;

class ArticleService
{
    private $articleRepository;
    private $security;

    public function __construct(ArticleRepository $articleRepository, Security $security)
    {
        $this->articleRepository = $articleRepository;
        $this->security = $security;
    }

    public function getAllArticles(): array
    {
        return $this->articleRepository->findAll();
    }

    public function createArticle(string $title, string $content): Article
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \Exception("User not authenticated");
        }

        $article = new Article();
        $article->setTitle($title);
        $article->setContent($content);
        $article->setPublishedAt(new \DateTimeImmutable());
        $article->setAuthor($user);

        $this->articleRepository->save($article);

        return $article;
    }

    public function updateArticle(Article $article, string $title, string $content): Article
    {
        $user = $this->security->getUser();
        if ($article->getAuthor() !== $user) {
            throw new \Exception("You cannot update this article");
        }

        $article->setTitle($title);
        $article->setContent($content);

        $this->articleRepository->save($article);

        return $article;
    }

    public function deleteArticle(Article $article): void
    {
        $user = $this->security->getUser();
        if ($article->getAuthor() !== $user) {
            throw new \Exception("You cannot delete this article");
        }

        $this->articleRepository->delete($article);
    }
}
