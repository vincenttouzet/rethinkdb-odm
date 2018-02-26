<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use RethinkDB\ODM\Tests\Document\Person;

class ManagerTest extends TestCase
{
    use ManagerAwareTestCase;

    protected function setUp()
    {
        \r\tableCreate('person')->run($this->getManager()->getConnection());
    }

    protected function tearDown()
    {
        \r\tableDrop('person')->run($this->getManager()->getConnection());
    }

    public function testPersist()
    {
        $person = new Person();
        $person->firstName = 'Vincent';
        $person->lastName = 'Touzet';

        $this->manager->persist($person);

        // reload
        $result = \r\table('person')->get($person->getId())->run($this->getManager()->getConnection());
        $this->assertEquals(3, $result->count());
        $this->assertEquals('Vincent', $result->offsetGet('firstName'));
        $this->assertEquals('Touzet', $result->offsetGet('lastName'));

        // update person
        $person->firstName = 'John';
        $person->lastName = 'Doe';

        $this->manager->persist($person);

        // reload
        $result = \r\table('person')->get($person->getId())->run($this->getManager()->getConnection());
        $this->assertEquals(3, $result->count());
        $this->assertEquals('John', $result->offsetGet('firstName'));
        $this->assertEquals('Doe', $result->offsetGet('lastName'));
    }

    public function testRemove()
    {
        $document = [
            'id' => Uuid::uuid5(Uuid::NAMESPACE_DNS, Person::class)->toString(),
            'fistName' => 'Vincent',
            'lastName' => 'Touzet',
        ];
        \r\table('person')->insert($document)->run($this->getManager()->getConnection());

        $count = \r\table('person')->count()->run($this->getManager()->getConnection());
        $this->assertEquals(1, $count);

        // reload from repository
        $person = $this->manager->getRepository(Person::class)->find($document['id']);

        $this->manager->remove($person);

        $count = \r\table('person')->count()->run($this->getManager()->getConnection());
        $this->assertEquals(0, $count);
    }
}
