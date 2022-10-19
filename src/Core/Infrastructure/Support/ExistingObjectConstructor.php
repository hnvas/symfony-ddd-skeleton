<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Support;

use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;

/**
 * Class ExistingObjectConstructor
 * @package App\Core\Infrastructure\Support
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ExistingObjectConstructor implements ObjectConstructorInterface
{
    public const ATTRIBUTE = 'target';

    private ObjectConstructorInterface $fallbackConstructor;

    public function __construct(ObjectConstructorInterface $fallbackConstructor)
    {
        $this->fallbackConstructor = $fallbackConstructor;
    }

    public function construct(
        DeserializationVisitorInterface $visitor,
        ClassMetadata                   $metadata,
                                        $data,
        array                           $type,
        DeserializationContext          $context
    ): ?object {
        if ($context->hasAttribute(self::ATTRIBUTE)) {
            return $context->getAttribute(self::ATTRIBUTE);
        }

        return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
    }
}
