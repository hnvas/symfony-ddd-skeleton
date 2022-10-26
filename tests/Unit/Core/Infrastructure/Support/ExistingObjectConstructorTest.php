<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Support;

use App\Core\Infrastructure\Support\ExistingObjectConstructor;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ExistingObjectConstructorTest
 * @package App\Tests\Unit\Core\Infrastructure\Support
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ExistingObjectConstructorTest extends TestCase
{

    private DeserializationVisitorInterface $visitorMock;
    private ClassMetadata                   $metadataMock;
    private DeserializationContext          $contextMock;
    private ObjectConstructorInterface      $fallbackConstructorMock;
    private ExistingObjectConstructor       $objectConstructor;

    protected function setUp(): void
    {
        $this->visitorMock = self::createMock(
            DeserializationVisitorInterface::class
        );

        $this->metadataMock = self::createMock(ClassMetadata::class);
        $this->contextMock  = self::createMock(DeserializationContext::class);

        $this->fallbackConstructorMock = self::createMock(
            ObjectConstructorInterface::class
        );

        $this->objectConstructor = new ExistingObjectConstructor(
            $this->fallbackConstructorMock
        );
    }

    public function testShouldConstructObjectUsingContext()
    {
        $this->contextMock->expects(self::once())
                          ->method('hasAttribute')
                          ->willReturn(true);

        $this->contextMock->expects(self::once())
                          ->method('getAttribute')
                          ->willReturn(new \stdClass());

        $this->objectConstructor->construct(
            $this->visitorMock,
            $this->metadataMock,
            'anyJson',
            [],
            $this->contextMock
        );
    }

    public function testShouldConstructObjectWithNoContext()
    {
        $this->contextMock->expects(self::once())
                          ->method('hasAttribute')
                          ->willReturn(false);

        $this->fallbackConstructorMock->expects(self::once())
                                      ->method('construct')
                                      ->with(
                                          $this->visitorMock,
                                          $this->metadataMock,
                                          'anyJson',
                                          [],
                                          $this->contextMock
                                      )->willReturn(new \stdClass());

        $this->objectConstructor->construct(
            $this->visitorMock,
            $this->metadataMock,
            'anyJson',
            [],
            $this->contextMock
        );
    }

}
