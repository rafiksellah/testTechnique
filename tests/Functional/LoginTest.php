<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testLoginWorks(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_login')
        );

        $this->assertSelectorExists('form');

        $form = $crawler->filter('form')->form([
            'email' => 'user@exemple.com',
            'password' => 'password'
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertRouteSame('app_login');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_login')
        );

        $this->assertSelectorExists('form');

        $form = $crawler->filter('form')->form([
            'email' => 'user@exemple.com',
            'password' => 'password'
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertRouteSame('app_login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-danger'); // Vérifie l'erreur affichée
        $this->assertSelectorTextContains('.alert.alert-danger', 'Invalid credentials.');
    }
}
