<?php

/*
 * This file is part of the RethinkDB ODM project.
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

class ArrayLoaderTest extends TestCase
{
    public function testLoad()
    {
        $loader = new ArrayLoader([
            [
                'class' => 'SomeClass',
                'table' => 'some_class',
                'repositoryClass' => 'MyRepository',
                'fields' => [
                    // mapped as id in database
                    'id',
                    // customize fieldName
                    'name' => [
                        'fieldName' => 'nom',
                    ],
                    // full define
                    [
                        'fieldName' => 'prenom',
                        'propertyName' => 'firstname',
                    ],
                ],
            ],
            [
                'class' => 'AnotherClass',
                'table' => 'another_table',
                'fields' => ['id', 'name'],
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

        /** @var ClassMetadata $classMetadata */
        $classMetadata = $metadata[0];
        $this->assertEquals(3, count($classMetadata->getFieldsMetadata()), 'There must be 3 field metadata loaded.');
        $this->assertEquals('id', $classMetadata->getFieldMetadata('id')->getPropertyName());
        $this->assertEquals('id', $classMetadata->getFieldMetadata('id')->getFieldName());
        $this->assertEquals('name', $classMetadata->getFieldMetadata('name')->getPropertyName());
        $this->assertEquals('nom', $classMetadata->getFieldMetadata('name')->getFieldName());
        $this->assertEquals('firstname', $classMetadata->getFieldMetadata('firstname')->getPropertyName());
        $this->assertEquals('prenom', $classMetadata->getFieldMetadata('firstname')->getFieldName());
    }

    public function testFieldDeclaration1()
    {
        $loader = new ArrayLoader([[
            'class' => 'SomeClass',
            'table' => 'some_table',
            'fields' => ['name'],
        ]]);
        $metadata = $loader->load();
        /** @var ClassMetadata $classMetadata */
        $classMetadata = $metadata[0];
        $this->assertEquals('name', $classMetadata->getFieldMetadata('name')->getPropertyName());
        $this->assertEquals('name', $classMetadata->getFieldMetadata('name')->getFieldName());
    }

    public function testFieldDeclaration2()
    {
        $loader = new ArrayLoader([[
            'class' => 'SomeClass',
            'table' => 'some_table',
            'fields' => [
                'name' => 'field_name',
            ],
        ]]);
        $metadata = $loader->load();
        /** @var ClassMetadata $classMetadata */
        $classMetadata = $metadata[0];
        $this->assertEquals('name', $classMetadata->getFieldMetadata('name')->getPropertyName());
        $this->assertEquals('field_name', $classMetadata->getFieldMetadata('name')->getFieldName());
    }

    public function testFieldDeclaration3()
    {
        $loader = new ArrayLoader([[
            'class' => 'SomeClass',
            'table' => 'some_table',
            'fields' => [
                'name' => [
                    'fieldName' => 'field_name',
                ],
            ],
        ]]);
        $metadata = $loader->load();
        /** @var ClassMetadata $classMetadata */
        $classMetadata = $metadata[0];
        $this->assertEquals('name', $classMetadata->getFieldMetadata('name')->getPropertyName());
        $this->assertEquals('field_name', $classMetadata->getFieldMetadata('name')->getFieldName());
    }

    public function testFieldDeclaration4()
    {
        $loader = new ArrayLoader([[
            'class' => 'SomeClass',
            'table' => 'some_table',
            'fields' => [
                'name' => [
                    'propertyName' => 'property_name',
                    'fieldName' => 'field_name',
                ],
            ],
        ]]);
        $metadata = $loader->load();
        /** @var ClassMetadata $classMetadata */
        $classMetadata = $metadata[0];
        $this->assertEquals('property_name', $classMetadata->getFieldMetadata('property_name')->getPropertyName());
        $this->assertEquals('field_name', $classMetadata->getFieldMetadata('property_name')->getFieldName());
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

    /**
     * @expectedException \RethinkDB\ODM\Exception\MissingMappingInfoException
     * @expectedExceptionMessage You need to specify fields metadata
     */
    public function testMissingFieldsException()
    {
        $loader = new ArrayLoader([['class' => 'SomeClass', 'table' => 'some_table']]);
        $loader->load();
    }

    /**
     * @expectedException \RethinkDB\ODM\Exception\MissingFieldMappingInfoException
     * @expectedExceptionMessage You need to specify fieldName metadata for class SomeClass
     */
    public function testMissingFieldNameException()
    {
        $loader = new ArrayLoader([[
            'class' => 'SomeClass',
            'table' => 'some_table',
            'fields' => [
                [
                    'propertyName' => 'name',
                ],
            ],
        ]]);
        $loader->load();
    }
}
