<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Controller\Api;

use App\Core\Infrastructure\DataFixtures\UserFixtures;

/**
 * Class UserResourceTest
 * @package App\Tests\Functional\Core\Infrastructure\Controller\Api
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class UserResourceTest extends BaseResourceTest
{
    protected const FIXTURES = [UserFixtures::class];

    public function validCreateData(): array
    {
        return [
            [
                'uri'     => '/api/user/',
                'payload' => [
                    'email'    => 'user10@admin.com',
                    'name'     => 'admin10',
                    'password' => '12345678910'
                ]
            ]
        ];
    }

    public function invalidCreateData(): array
    {
        return [
            [
                'uri'     => '/api/user/',
                'payload' => [
                    'email'    => 'user@admin.com',
                    'name'     => 'admin',
                    'password' => '12345678910'
                ]
            ]
        ];
    }

    public function deleteData(): array
    {
        return [
            [
                'uri' => '/api/user/',
                'id'  => 2
            ]
        ];
    }

    public function indexData(): array
    {
        return [
            ['uri' => '/api/user/']
        ];
    }

    public function readData(): array
    {
        return [
            [
                'uri' => '/api/user/',
                'id'  => 3
            ]
        ];
    }

    public function updateData(): array
    {
        return [
            [
                'uri'     => '/api/user/',
                'id'      => 1,
                'payload' => [
                    'email'    => 'user@admin.com',
                    'name'     => 'admin',
                    'password' => '12345678910'
                ]
            ]
        ];
    }
}
