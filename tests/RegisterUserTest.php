<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');
        $client->submitForm('Valider', [
            'register_user[email]' => 'test@test.com',
            'register_user[plainPassword][first]' => '123456', 
            'register_user[plainPassword][second]' => '123456',
            'register_user[firstname]' => 'test',
            'register_user[lastname]' => 'test'
        ]);

        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertSelectorExists('div:contains("Votre compte a été correctement créé")');

    }
}
