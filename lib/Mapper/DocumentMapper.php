<?php

/*
 * This file is part of the Sauron package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Mapper;

use RethinkDB\ODM\Document;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class DocumentMapper
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DocumentMapper
{
    /**
     * Convert a Document to a database representation
     *
     * @param Document $document
     *
     * @return array
     */
    public static function parse(Document $document)
    {
        $reflection = new \ReflectionClass(get_class($document));
        $accessor = PropertyAccess::createPropertyAccessor();

        $data = [];
        do
        {
            foreach ($reflection->getProperties() as $property) {
                $data[$property->getName()] = $accessor->getValue($document, $property->getName());
            }
            $reflection = $reflection->getParentClass();
        } while ($reflection);

        return $data;
    }

    /**
     * Convert a database representation to a Document
     *
     * @param array $data
     * @param $class
     *
     * @return Document Instance of $class
     */
    public static function unparse($data, $class)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $document = new $class();
        foreach ($data as $key => $value) {
            if ('id' === $key) {
                $reflectionClass = new \ReflectionClass($class);
                while (!$reflectionClass->hasProperty('id')) {
                    $reflectionClass = $reflectionClass->getParentClass();
                }
                $propertyReflection = $reflectionClass->getProperty('id');
                $propertyReflection->setAccessible(true);
                $propertyReflection->setValue($document, $value);
            } else {
                $accessor->setValue($document, $key, $value);
            }
        }

        return $document;
    }
}
