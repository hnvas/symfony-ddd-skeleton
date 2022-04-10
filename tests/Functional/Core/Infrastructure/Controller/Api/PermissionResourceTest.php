<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Controller\Api;

class PermissionResourceTest extends BaseResourceTest
{
    protected const FIXTURES = [
        'App\Core\Infrastructure\DataFixtures\UserFixtures',
        'App\Core\Infrastructure\DataFixtures\PermissionFixtures'
    ];

    public function validCreateData(): array
    {
        return [
            [
                'uri'     => '/api/permission/',
                'payload' => [
                    'role'      => 'ROLE_TEST',
                    'module'    => ['id' => 1],
                    'resource'  => '/api/permission/',
                    'canCreate' => 'true',
                    'canRead'   => 'true',
                    'canUpdate' => 'true',
                    'canDelete' => 'true',
                    'canIndex'  => 'true',
                ]
            ]
        ];
    }

    public function invalidCreateData(): array
    {
        return [
            [
                'uri'     => '/api/permission/',
                'payload' => [
                    'role'      => 'ROLE_TEST',
                    'resource'  => '/api/permission/',
                    'canCreate' => 'true'
                ]
            ]
        ];
    }

    public function deleteData(): array
    {
        return [
            [
                'uri' => '/api/permission/',
                'id'  => 2
            ]
        ];
    }

    public function indexData(): array
    {
        return [
            ['uri' => '/api/permission/']
        ];
    }

    public function readData(): array
    {
        return [
            [
                'uri' => '/api/permission/',
                'id'  => 1
            ]
        ];
    }

    public function updateData(): array
    {
        return [
            [
                'uri'     => '/api/permission/',
                'id'      => 1,
                'payload' => [
                    'role'      => 'ROLE_TEST',
                    'module'    => ['id' => 1],
                    'resource'  => '/api/permission/',
                    'canCreate' => 'false',
                    'canRead'   => 'true',
                    'canUpdate' => 'false',
                    'canDelete' => 'true',
                    'canIndex'  => 'true'
                ]
            ]
        ];
    }
}
