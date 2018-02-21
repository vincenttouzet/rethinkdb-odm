<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Metadata\Loader;

use RethinkDB\ODM\Exception\MissingFieldMappingInfoException;
use RethinkDB\ODM\Exception\MissingMappingInfoException;
use RethinkDB\ODM\Metadata\ClassMetadata;
use RethinkDB\ODM\Metadata\FieldMetadata;

/**
 * Class ArrayLoader.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class ArrayLoader implements LoaderInterface
{
    /** @var array */
    protected $mappings = [];

    public function __construct(array $mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $metadatas = [];

        foreach ($this->mappings as $mapping) {
            if (!isset($mapping['class']) || empty($mapping['class'])) {
                throw new MissingMappingInfoException('class');
            }
            if (!isset($mapping['table']) || empty($mapping['table'])) {
                throw new MissingMappingInfoException('table');
            }
            // fields
            if (!isset($mapping['fields'])) {
                throw new MissingMappingInfoException('fields');
            }
            $fields = [];
            foreach ($mapping['fields'] as $name => $fieldMapping) {
                $fieldMetadata = new FieldMetadata();
                if (is_array($fieldMapping)) {
                    // full declaration
                    if (!isset($fieldMapping['fieldName'])) {
                        throw new MissingFieldMappingInfoException($mapping['class'], 'fieldName');
                    }
                    $fieldMetadata->setFieldName($fieldMapping['fieldName']);
                    if (isset($fieldMapping['propertyName'])) {
                        $fieldMetadata->setPropertyName($fieldMapping['propertyName']);
                    } else {
                        if (is_string($name)) {
                            $fieldMetadata->setPropertyName($name);
                        } else {
                            throw new MissingFieldMappingInfoException($mapping['class'], 'propertyName');
                        }
                    }
                } else {
                    // not a string index ?
                    if (!is_string($name)) {
                        $fieldMetadata->setPropertyName($fieldMapping);
                        $fieldMetadata->setFieldName($fieldMapping);
                    } else {
                        $fieldMetadata->setPropertyName($name);
                        $fieldMetadata->setFieldName($fieldMapping);
                    }
                }
                $fields[] = $fieldMetadata;
            }
            $classMetadata = new ClassMetadata($mapping['class'], $mapping['table'], $fields);
            if (isset($mapping['repositoryClass'])) {
                $classMetadata->setRepositoryClass($mapping['repositoryClass']);
            }

            $metadatas[] = $classMetadata;
        }

        return $metadatas;
    }
}
