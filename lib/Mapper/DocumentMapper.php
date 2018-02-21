<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Mapper;

use RethinkDB\ODM\Document;
use RethinkDB\ODM\Metadata\ClassMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class DocumentMapper.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DocumentMapper
{
    /**
     * Convert a Document to a database representation.
     *
     * @param Document $document
     *
     * @return array
     */
    public static function parse(Document $document, ClassMetadata $metadata)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $data = [];
        foreach ($metadata->getFieldsMetadata() as $fieldMetadata) {
            $data[$fieldMetadata->getFieldName()] = $accessor->getValue($document, $fieldMetadata->getPropertyName());
        }

        return $data;
    }

    /**
     * Convert a database representation to a Document.
     *
     * @param array $data
     * @param $class
     *
     * @return Document Instance of $class
     */
    public static function unparse($data, ClassMetadata $metadata)
    {
        $class = $metadata->getClass();
        $accessor = PropertyAccess::createPropertyAccessor();

        // Create document
        $document = new $class();
        foreach ($metadata->getFieldsMetadata() as $fieldMetadata) {
            $value = $data[$fieldMetadata->getFieldName()];
            if ('id' === $fieldMetadata->getPropertyName()) {
                $reflectionClass = new \ReflectionClass($class);
                while (!$reflectionClass->hasProperty('id')) {
                    $reflectionClass = $reflectionClass->getParentClass();
                }
                $propertyReflection = $reflectionClass->getProperty('id');
                $propertyReflection->setAccessible(true);
                $propertyReflection->setValue($document, $value);
            } else {
                $accessor->setValue($document, $fieldMetadata->getPropertyName(), $value);
            }
        }

        return $document;
    }
}
