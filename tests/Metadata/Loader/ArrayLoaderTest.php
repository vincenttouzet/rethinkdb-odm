<?php

/*
 * This file is part of the rethinkdb-odm package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Tests\Metadata\Loader;

use PHPUnit\Framework\TestCase;
use RethinkDB\ODM\Metadata\ClassMetadata;
use RethinkDB\ODM\Metadata\Loader\ArrayLoader;
use RethinkDB\ODM\Repository\DocumentRepository;

class ArrayLoaderTest extends TestCase
{
    public function testLoad()
    {
        $loader = new ArrayLoader([
            [
                'class'           => 'SomeClass',
                'table'           => 'some_class',
                'repositoryClass' => 'MyRepository',
            ],
            [
                'class' => 'AnotherClass',
                'table' => 'another_table',
            ],
        ]);

        $metadata = $loader->load();

        $this->assertEquals(2, count($metadata), 'The loader must return 2 ClassMetadata');
        $this->assertEquals(
            ClassMetadata::class,
            get_class($metadata[0]),
            'The loader must return ClassMetadata instances'
        );
        $this->assertEquals('SomeClass', $metadata[0]->getClass());
        $this->assertEquals('some_class', $metadata[0]->getTable());
        $this->assertEquals('MyRepository', $metadata[0]->getRepositoryClass());
    }

    /**
     * @expectedException \RethinkDB\ODM\Exception\MissingMappingInfoException
     * @expectedExceptionMessage You need to specify class metadata
     */
    public function testMissingClassException()
    {
        $loader = new ArrayLoader([[]]);
        $loader->load();
    }

    /**
     * @expectedException \RethinkDB\ODM\Exception\MissingMappingInfoException
     * @expectedExceptionMessage You need to specify class metadata
     */
    public function testEmptyClassException()
    {
        $loader = new ArrayLoader([['class' => '']]);
        $loader->load();
    }

    /**
     * @expectedException \RethinkDB\ODM\Exception\MissingMappingInfoException
     * @expectedExceptionMessage You need to specify table metadata
     */
    public function testMissingTableException()
    {
        $loader = new ArrayLoader([['class' => 'SomeClass']]);
        $loader->load();
    }

    /**
     * @expectedException \RethinkDB\ODM\Exception\MissingMappingInfoException
     * @expectedExceptionMessage You need to specify table metadata
     */
    public function testEmptyTableException()
    {
        $loader = new ArrayLoader([['class' => 'SomeClass', 'table' => '']]);
        $loader->load();
    }
}
