<?php

/*
 * This file is part of the Sauron package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Repository;

use RethinkDB\ODM\Metadata\ClassMetadataRegistry;

/**
 * Class DocumentRepositoryRegistry
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DocumentRepositoryRegistry
{
    /** @var \RethinkDB\ODM\Repository\DocumentRepository[] */
    protected $repositories = [];

    /** @var \RethinkDB\ODM\Metadata\ClassMetadataRegistry */
    protected $classMetadataRegistry;

    /** @var \RethinkDB\ODM\Manager */
    protected $manager;

    public function __construct(ClassMetadataRegistry $registry)
    {
        $this->classMetadataRegistry = $registry;
    }

    /**
     * @param \RethinkDB\ODM\Manager $manager
     *
     * @return self
     */
    public function setManager(\RethinkDB\ODM\Manager $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return \RethinkDB\ODM\Manager
     */
    public function getManager(): \RethinkDB\ODM\Manager
    {
        return $this->manager;
    }

    public function addRepository(DocumentRepository $repository)
    {
        $this->repositories[$repository->getClassMetadata()->getClass()] = $repository;
    }

    /**
     * @param $class
     *
     * @return \RethinkDB\ODM\Repository\DocumentRepository
     */
    public function getRepository($class)
    {
        if (!isset($this->repositories[$class])) {
            // build instance
            $classMetadata = $this->classMetadataRegistry->getClassMetadata($class);
            $repositoryClass = $classMetadata->getRepositoryClass();
            $reflection = new \ReflectionClass($repositoryClass);
            $documentRepository = $reflection->newInstance($this, $classMetadata);
            $this->repositories[$class] = $documentRepository;
        }
        return $this->repositories[$class];
    }
}
