<?php

/*
 * This file is part of the Sauron package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Metadata;

use RethinkDB\ODM\Repository\DocumentRepository;

/**
 * Class ClassMetadata
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class ClassMetadata
{
    /** @var  string */
    protected $class;

    /** @var  string */
    protected $table;

    protected $repositoryClass;

    public function __construct($class, $table, $repositoryClass = DocumentRepository::class)
    {
        $this->setClass($class);
        $this->setTable($table);
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
}
