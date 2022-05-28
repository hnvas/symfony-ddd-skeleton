<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Support;

use App\Core\Infrastructure\Http\Request\QueryParams;
use App\Core\Infrastructure\Support\PaginatorAssembler;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Adapter\NullAdapter;
use PHPUnit\Framework\TestCase;

class PaginatorAssemblerTest extends TestCase
{

    public function testAssemble()
    {
        $adapter         = new NullAdapter(10);
        $queryParamsMock = self::createMock(QueryParams::class);
        $queryParamsMock->expects(self::once())
                        ->method('route')
                        ->willReturn('/user');
        $queryParamsMock->expects(self::once())
                        ->method('all')
                        ->willReturn([]);
        $queryParamsMock->expects(self::once())
                        ->method('limit')
                        ->willReturn(10);
        $queryParamsMock->expects(self::once())
                        ->method('page')
                        ->willReturn(1);

        $instance = new PaginatorAssembler($adapter, $queryParamsMock);
        $result = $instance->assemble();

        self::assertInstanceOf(PaginatedRepresentation::class, $result);
    }

}
