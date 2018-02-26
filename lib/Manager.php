<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM;

use Ramsey\Uuid\Uuid;
use RethinkDB\ODM\Mapper\DocumentMapper;
use RethinkDB\ODM\Metadata\ClassMetadataRegistry;
use RethinkDB\ODM\Repository\DocumentRepositoryRegistry;

/**
 * Class Manager.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class Manager
{
    /** @var \r\Connection */
    protected $connection;

    /** @var ClassMetadataRegistry */
    protected $metadataRegistry;

    /** @var \RethinkDB\ODM\Repository\DocumentRepositoryRegistry */
    protected $drRegistry;

    /**
     * Manager constructor.
     *
     * @param \r\Connection                                        $connection
     * @param \RethinkDB\ODM\Metadata\ClassMetadataRegistry        $metadataRegistry
     * @param \RethinkDB\ODM\Repository\DocumentRepositoryRegistry $drRegistry
     */
    public function __construct(
        \r\Connection $connection,
        ClassMetadataRegistry $metadataRegistry,
        DocumentRepositoryRegistry $drRegistry
    ) {
        $this->connection = $connection;
        $this->metadataRegistry = $metadataRegistry;
        $this->drRegistry = $drRegistry;
        $drRegistry->setManager($this);
    }

    /**
     * Persist document.
     *
     * @param \RethinkDB\ODM\Document $document
     */
    public function persist(Document $document)
    {
        if ($document->getId()) {
            $this->update($document);
        } else {
            $this->insert($document);
        }
    }

    /**
     * Insert a new document.
     *
     * @param \RethinkDB\ODM\Document $document
     */
    protected function insert(Document $document)
    {
        // generate a new id
        $id = Uuid::uuid5(Uuid::NAMESPACE_DNS, get_class($document));
        // set into document
        $reflectionClass = new \ReflectionClass(get_class($document));
        while (!$reflectionClass->hasProperty('id')) {
            $reflectionClass = $reflectionClass->getParentClass();
        }
        $propertyReflection = $reflectionClass->getProperty('id');
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue($document, $id);

        $data = DocumentMapper::parse($document, $this->getClassMetadata(get_class($document)));

        $this->getTableForDocument($document)
            ->insert($data)
            ->run($this->connection);
    }

    /**
     * Update a document.
     *
     * @param \RethinkDB\ODM\Document $document
     */
    protected function update(Document $document)
    {
        $data = DocumentMapper::parse($document, $this->getClassMetadata(get_class($document)));
        unset($data['id']);

        $this->getTableForDocument($document)
            ->get($document->getId())
            ->update($data)
            ->run($this->connection);
    }

    /**
     * Remove a document.
     *
     * @param \RethinkDB\ODM\Document $document
     */
    public function remove(Document $document)
    {
        $this->getTableForDocument($document)
            ->get($document->getId())
            ->delete()
            ->run($this->connection);
    }

    /**
     * @return \r\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return \RethinkDB\ODM\Metadata\ClassMetadataRegistry
     */
    public function getClassMetadataRegistry(): \RethinkDB\ODM\Metadata\ClassMetadataRegistry
    {
        return $this->metadataRegistry;
    }

    /**
     * @return \RethinkDB\ODM\Repository\DocumentRepositoryRegistry
     */
    public function getDocumentRepositoryRegistry(): \RethinkDB\ODM\Repository\DocumentRepositoryRegistry
    {
        return $this->drRegistry;
    }

    /**
     * @param $class
     *
     * @return \RethinkDB\ODM\Repository\DocumentRepository
     */
    public function getRepository($class)
    {
        return $this->drRegistry->getRepository($class);
    }

    /**
     * @param $class
     *
     * @return \RethinkDB\ODM\Metadata\ClassMetadata
     */
    public function getClassMetadata($class)
    {
        return $this->metadataRegistry->getClassMetadata($class);
    }

    /**
     * @param \RethinkDB\ODM\Document $document
     *
     * @return \r\Queries\Tables\Table
     */
    private function getTableForDocument(Document $document)
    {
        return \r\table($this->getClassMetadata(get_class($document))->getTable());
    }
}
