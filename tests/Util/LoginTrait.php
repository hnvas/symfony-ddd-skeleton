<?php
declare(strict_types = 1);

namespace App\Tests\Util;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait LoginTrait
{

    protected function login(KernelBrowser $client)
    {
        $client->request("POST", "/auth/login", [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'user@admin.com',
            'password' => '12345678910'
        ]));

        return json_decode($client->getResponse()->getContent())->access_token;
    }

}
