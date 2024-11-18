<?php

namespace App\Tests\Functional;

use App\Domain\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ArticleTest extends WebTestCase
{
    public function testIfCreateArticleIsSuccessfull(): void
    {
        $client = static::createClient();
        /** @var UrlGeneratorInterface */
        $urlGeneratorInterface = $client->getContainer()->get('router');

        /** @var EntityManagerInterface */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        /** @var ArticleRepository */
        $articleRepository = $entityManager->getRepository(Article::class);

        /** @var Article */
        $article = $articleRepository->findOneBy([]);

        $client->request(
            Request::METHOD_GET,
            $urlGeneratorInterface->generate('article_detail', ['id' => $article->getId()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('h1', ucfirst($article->getTitle()));
    }
}
