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

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use RethinkDB\ODM\Manager;
use RethinkDB\ODM\Metadata\ClassMetadataRegistry;
use RethinkDB\ODM\Metadata\Loader\ArrayLoader;
use RethinkDB\ODM\Repository\DocumentRepositoryRegistry;
use RethinkDB\ODM\Tests\Document\Person;

class ManagerTest extends TestCase
{
    /** @var \r\Connection */
    protected $connection;

    /** @var \RethinkDB\ODM\Manager */
    protected $manager;

    protected function setUp()
    {
        $this->connection = new \r\Connection([
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

        $this->manager = new Manager($this->connection, $metadataRegistry, $repositoryRegistry);
        \r\tableCreate('person')->run($this->connection);
    }

    protected function tearDown()
    {
        \r\tableDrop('person')->run($this->connection);
    }

    public function testPersist()
    {
        $person = new Person();
        $person->firstName = 'Vincent';
        $person->lastName = 'Touzet';

        $this->manager->persist($person);

        // reload
        $result = \r\table('person')->get($person->getId())->run($this->connection);
        $this->assertEquals(3, $result->count());
        $this->assertEquals('Vincent', $result->offsetGet('firstName'));
        $this->assertEquals('Touzet', $result->offsetGet('lastName'));

        // update person
        $person->firstName = 'John';
        $person->lastName = 'Doe';

        $this->manager->persist($person);

        // reload
        $result = \r\table('person')->get($person->getId())->run($this->connection);
        $this->assertEquals(3, $result->count());
        $this->assertEquals('John', $result->offsetGet('firstName'));
        $this->assertEquals('Doe', $result->offsetGet('lastName'));
    }

    public function testRemove()
    {
        $document = [
            'id'       => Uuid::uuid5(Uuid::NAMESPACE_DNS, Person::class)->toString(),
            'fistName' => 'Vincent',
            'lastName' => 'Touzet',
        ];
        \r\table('person')->insert($document)->run($this->connection);

        $count = \r\table('person')->count()->run($this->connection);
        $this->assertEquals(1, $count);

        // reload from repository
        $person = $this->manager->getRepository(Person::class)->find($document['id']);

        $this->manager->remove($person);

        $count = \r\table('person')->count()->run($this->connection);
        $this->assertEquals(0, $count);
    }
}
