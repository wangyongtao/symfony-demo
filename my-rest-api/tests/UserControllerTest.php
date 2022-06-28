<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testGetUserList(): void
    {
        $client = static::createClient();

        $query = http_build_query([
            'is_member' => 1,
            'is_active' => 1,
            'user_type' => 1,
            'login_start_time' => strtotime('2009-01-20 23:22:33'),
            'login_ent_time' => strtotime('2010-04-23 17:18:40'),
        ]);
        $client->request('GET', '/users?' . $query);
        $this->assertResponseIsSuccessful();

//        var_dump($client->getResponse()->getStatusCode());
//        var_dump($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'status code not 200');

        $content = $client->getResponse()->getContent();
        $this->assertNotEmpty($content);

    }

    public function testGetUserOne(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users/222');
        $this->assertResponseIsSuccessful();

//        var_dump($client->getResponse()->getStatusCode());
//        var_dump($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'status code not 200');

        $content = $client->getResponse()->getContent();
        $this->assertNotEmpty($content);

        $json = json_decode($content, true);
        $this->assertIsArray($json);
        $this->assertArrayHasKey('username', $json);
    }


}
