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

use RethinkDB\ODM\Exception\ClassMetadataNotFoundException;
use RethinkDB\ODM\Metadata\Loader\LoaderInterface;

/**
 * Class ClassMetadataRegistry.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class ClassMetadataRegistry
{
    /** @var \RethinkDB\ODM\Metadata\ClassMetadata[] */
    protected $classMetadata = [];

    /** @var \RethinkDB\ODM\Metadata\Loader\LoaderInterface */
    protected $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
        $this->loadMetadatas();
    }

    public function loadMetadatas()
    {
        $metadatas = $this->loader->load();
        foreach ($metadatas as $metadata) {
            $this->addClassMetadata($metadata);
        }
    }

    public function addClassMetadata(ClassMetadata $classMetadata)
    {
        $this->classMetadata[$classMetadata->getClass()] = $classMetadata;
    }

    /**
     * @param $class
     *
     * @return \RethinkDB\ODM\Metadata\ClassMetadata
     *
     * @throws \RethinkDB\ODM\Exception\ClassMetadataNotFoundException
     */
    public function getClassMetadata($class)
    {
        if (!isset($this->classMetadata[$class])) {
            throw new ClassMetadataNotFoundException($class);
        }

        return $this->classMetadata[$class];
    }

    /**
     * Retrieve all metadatas.
     *
     * @return \RethinkDB\ODM\Metadata\ClassMetadata[]
     */
    public function all()
    {
        return $this->classMetadata;
    }
}
