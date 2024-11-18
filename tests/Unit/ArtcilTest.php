<?php

namespace App\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Domain\Entity\Article; // Assurez-vous que le namespace est correct

class ArticleTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testValidArticle(): void
    {
        $article = new Article();
        $article->setTitle('Valid Title');
        $article->setContent('This is valid content for an article.');

        $errors = $this->validator->validate($article);

        $this->assertCount(0, $errors, 'No validation errors should be found for a valid article.');
    }
}
