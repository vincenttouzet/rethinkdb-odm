<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Tests\Metadata;

use PHPUnit\Framework\TestCase;
use RethinkDB\ODM\Metadata\ClassMetadataRegistry;
use RethinkDB\ODM\Metadata\Loader\ArrayLoader;

class ClassMetadataRegistryTest extends TestCase
{
    /** @var ClassMetadataRegistry */
    protected $registry;

    protected function setUp()
    {
        $loader = new ArrayLoader([[
            'class'  => 'MyClass',
            'table'  => 'my_table',
            'fields' => ['id', 'name'],
        ]]);
        $this->registry = new ClassMetadataRegistry($loader);
    }

    public function testRetrieveMetadataFromClass()
    {
        $metadata = $this->registry->getClassMetadata('MyClass');
        $this->assertEquals('MyClass', $metadata->getClass());
        $this->assertEquals('my_table', $metadata->getTable());
    }

    /**
     * @expectedException \RethinkDB\ODM\Exception\ClassMetadataNotFoundException
     */
    public function testClassMetadataNotFound()
    {
        $this->registry->getClassMetadata('ClassNotFound');
    }
}
