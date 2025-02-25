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
            'register_user[email]' => 'bob@exemple.fr',
            'register_user[plainPassword][first]' => '123456',
            'register_user[plainPassword][second]' => '123456',
            'register_user[firstname]' => 'Bob',
            'register_user[lastname]' => 'Doe'
        ]);

        // Il y a une redirection entre la validation du formulaire et le message testé,
        // il faut donc la suivre
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertSelectorExists('div:contains("Inscription validée")');

    }
}
