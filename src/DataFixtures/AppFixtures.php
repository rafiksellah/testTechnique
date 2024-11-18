<?php

// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Domain\Entity\User;
use App\Domain\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail("user$i@example.com");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);


            for ($j = 1; $j <= 3; $j++) {
                $article = new Article();
                $article->setTitle("Article $j de l'utilisateur $i");
                $article->setContent("Contenu de l'article $j de l'utilisateur $i");
                $article->setPublishedAt(new \DateTimeImmutable());
                $article->setAuthor($user);
                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
