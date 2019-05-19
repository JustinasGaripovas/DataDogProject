<?php
// tests/Controller/PostControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormTest extends WebTestCase
{

    public function testLoginForm()
    {
        $client = static::createClient();
        $crawler =  $client->request('GET', '/login');

        $form = $crawler->selectButton('submit')->form();

        $form['username'] = 'admin';
        $form['password'] = 'admin';


        $crawler = $client->submit($form);


    }



}
