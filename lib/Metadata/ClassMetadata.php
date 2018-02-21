<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Metadata;

use RethinkDB\ODM\Exception\FieldMetadataNotFoundException;
use RethinkDB\ODM\Repository\DocumentRepository;

/**
 * Class ClassMetadata.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class ClassMetadata
{
    /** @var string */
    protected $class;

    /** @var string */
    protected $table;

    /** @var string */
    protected $repositoryClass;

    /** @var \RethinkDB\ODM\Metadata\FieldMetadata[] */
    protected $fieldsMetadata = [];

    /**
     * ClassMetadata constructor.
     *
     * @param string                                  $class
     * @param string                                  $table
     * @param \RethinkDB\ODM\Metadata\FieldMetadata[] $fieldsMetadata
     * @param string                                  $repositoryClass
     */
    public function __construct($class, $table, $fieldsMetadata, $repositoryClass = DocumentRepository::class)
    {
        $this->setClass($class);
        $this->setTable($table);
        $this->setFieldsMetadata($fieldsMetadata);
        $this->setRepositoryClass($repositoryClass);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return self
     */
    public function setClass(string $class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $table
     *
     * @return self
     */
    public function setTable(string $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepositoryClass(): string
    {
        return $this->repositoryClass;
    }

    /**
     * @param string $repositoryClass
     *
     * @return self
     */
    public function setRepositoryClass(string $repositoryClass)
    {
        $this->repositoryClass = $repositoryClass;

        return $this;
    }

    public function getFieldMetadata($propertyName)
    {
        foreach ($this->getFieldsMetadata() as $fieldMetadata) {
            if ($propertyName === $fieldMetadata->getPropertyName()) {
                return $fieldMetadata;
            }
        }

        throw new FieldMetadataNotFoundException($this->getClass(), $propertyName);
    }

    /**
     * @return \RethinkDB\ODM\Metadata\FieldMetadata[]
     */
    public function getFieldsMetadata(): array
    {
        return $this->fieldsMetadata;
    }

    /**
     * @param \RethinkDB\ODM\Metadata\FieldMetadata[] $fieldsMetadata
     *
     * @return self
     */
    public function setFieldsMetadata(array $fieldsMetadata)
    {
        $this->fieldsMetadata = $fieldsMetadata;

        return $this;
    }
}
