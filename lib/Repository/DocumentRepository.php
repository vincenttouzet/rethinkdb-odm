<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Repository;

use RethinkDB\ODM\Mapper\DocumentMapper;
use RethinkDB\ODM\Metadata\ClassMetadata;

/**
 * Class DocumentRepository.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DocumentRepository
{
    /** @var \RethinkDB\ODM\Repository\DocumentRepositoryRegistry */
    protected $registry;

    /** @var \RethinkDB\ODM\Metadata\ClassMetadata */
    protected $classMetadata;

    public function __construct(DocumentRepositoryRegistry $registry, ClassMetadata $classMetadata)
    {
        $this->registry = $registry;
        $this->classMetadata = $classMetadata;
        $this->registry->addRepository($this);
    }

    /**
     * @param $id
     *
     * @return \RethinkDB\ODM\Document|null
     */
    public function find($id)
    {
        $connection = $this->getManager()->getConnection();

        $result = \r\table($this->getClassMetadata()->getTable())->get($id)->run($connection);

        if ($result) {
            // unparse
            $result = DocumentMapper::unparse($result, $this->getClassMetadata()->getClass());
        }

        return $result;
    }

    /**
     * @return \RethinkDB\ODM\Repository\DocumentRepositoryRegistry
     */
    public function getRegistry(): \RethinkDB\ODM\Repository\DocumentRepositoryRegistry
    {
        return $this->registry;
    }

    /**
     * @return \RethinkDB\ODM\Metadata\ClassMetadata
     */
    public function getClassMetadata(): \RethinkDB\ODM\Metadata\ClassMetadata
    {
        return $this->classMetadata;
    }

    /**
     * @return \RethinkDB\ODM\Manager
     */
    public function getManager()
    {
        return $this->registry->getManager();
    }
}
