<?php

namespace App\Presentation\Controller;

use App\Domain\Entity\Article;
use App\Domain\Service\ArticleService;
use App\Presentation\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ArticleController extends AbstractController
{
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    #[Route('/article', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        $articles = $this->articleService->getAllArticles();

        $canEditArticles = [];
        foreach ($articles as $article) {
            $canEditArticles[$article->getId()] = $article->getAuthor() === $user;
        }

        return $this->render('article/all_articles.html.twig', [
            'articles' => $articles,
            'canEditArticles' => $canEditArticles,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/article/new', name: 'new_article', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if (!$user) {
                return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
            }

            $article->setAuthor($user);
            $article->setPublishedAt(new \DateTimeImmutable());

            $this->articleService->createArticle(
                $article->getTitle(),
                $article->getContent()
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}', name: 'article_detail', methods: ['GET'])]
    public function show(Article $article): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est l'auteur de l'article
        $canEdit = $user && $article->getAuthor() === $user;

        return $this->render('article/detail.html.twig', [
            'article' => $article,
            'canEdit' => $canEdit,
        ]);
    }

    #[Route('/article/{id}/edit', name: 'edit_article', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response
    {
        $user = $this->getUser();

        if ($article->getAuthor() !== $user) {
            throw new AccessDeniedException('You are not allowed to edit this article.');
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->updateArticle(
                $article,
                $article->getTitle(),
                $article->getContent()
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/article/{id}/delete', name: 'delete_article', methods: ['DELETE', 'POST'])]
    public function delete(Request $request, Article $article): Response
    {
        $user = $this->getUser();

        if ($article->getAuthor() !== $user) {
            throw new AccessDeniedException('You are not allowed to delete this article.');
        }

        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            try {
                $this->articleService->deleteArticle($article);

                $this->addFlash('success', 'Article deleted successfully.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error: ' . $e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_home');
    }
}
