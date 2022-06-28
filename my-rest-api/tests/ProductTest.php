<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = self::createClient();
        $client->request('GET', '/product/3');

//        print_r($client->getResponse()->getContent());
        $this->assertResponseIsSuccessful();
    }
}
