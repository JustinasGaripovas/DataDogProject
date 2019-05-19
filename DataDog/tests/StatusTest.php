<?php
// tests/Controller/PostControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatusTest extends WebTestCase
{
    /**
     * @dataProvider provideBaseUrls
     */
    public function testBasePages($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideBaseUrls()
    {
        return [
            ['/login'],
            ['/about'],
            ['/contact'],
            ['/users'],
            ['/resetPassword/RandomString123'],
        ];
    }

    /**
     * @dataProvider provideRestrictedUrls
     */
    public function testAdminPages($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);


        $this->assertFalse($client->getResponse()->isSuccessful());

    }

    public function provideRestrictedUrls()
    {
        return [
            ['/users'],
            ['/newevent'],
            ['/event/0/delete'],
            ['/event/0/edit'],
        ];
    }


}
