<?php

/*
 * This file is part of the rethinkdb-odm package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Tests;

use RethinkDB\ODM\Manager;
use RethinkDB\ODM\Metadata\ClassMetadataRegistry;
use RethinkDB\ODM\Metadata\Loader\ArrayLoader;
use RethinkDB\ODM\Repository\DocumentRepositoryRegistry;
use RethinkDB\ODM\Tests\Document\Person;

/**
 * Class ManagerAwareTestCase
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
trait ManagerAwareTestCase
{
    /** @var \RethinkDB\ODM\Manager */
    private $manager;

    /**
     * @return \RethinkDB\ODM\Manager
     */
    public function getManager(): \RethinkDB\ODM\Manager
    {
        if (!$this->manager) {
            $connection = new \r\Connection([
                'host'     => RETHINKDB_HOST,
                'port'     => RETHINKDB_PORT,
                'user'     => RETHINKDB_USER,
                'password' => RETHINKDB_PASSWORD,
                'db'       => RETHINKDB_DB,
            ]);

            $loader = new ArrayLoader([[
                'class'  => Person::class,
                'table'  => 'person',
                'fields' => ['id', 'firstName', 'lastName'],
            ]]);

            $metadataRegistry = new ClassMetadataRegistry($loader);
            $repositoryRegistry = new DocumentRepositoryRegistry($metadataRegistry);

            $this->manager = new Manager($connection, $metadataRegistry, $repositoryRegistry);
        }

        return $this->manager;
    }
}
