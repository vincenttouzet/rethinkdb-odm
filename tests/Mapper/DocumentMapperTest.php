<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Tests\Mapper;

use PHPUnit\Framework\TestCase;
use RethinkDB\ODM\Mapper\DocumentMapper;
use RethinkDB\ODM\Metadata\ClassMetadata;
use RethinkDB\ODM\Metadata\FieldMetadata;
use RethinkDB\ODM\Tests\Document\Person;

class DocumentMapperTest extends TestCase
{
    /** @var \RethinkDB\ODM\Metadata\ClassMetadata */
    protected $classMetadata;

    protected function setUp()
    {
        $fields = [
            (new FieldMetadata())
                ->setFieldName('id')
                ->setPropertyName('id'),
            (new FieldMetadata())
                ->setFieldName('firstname')
                ->setPropertyName('firstName'),
            (new FieldMetadata())
                ->setFieldName('lastname')
                ->setPropertyName('lastName'),
        ];
        $this->classMetadata = new ClassMetadata(Person::class, 'person', $fields);
    }

    public function testParse()
    {
        $document = new Person();
        $document->firstName = 'Vincent';
        $document->lastName = 'Touzet';

        $data = DocumentMapper::parse($document, $this->classMetadata);

        $this->assertEquals('Vincent', $data['firstname']);
        $this->assertEquals('Touzet', $data['lastname']);
    }

    public function testUnparse()
    {
        $data = [
            'firstname' => 'Vincent',
            'lastname' => 'Touzet',
        ];

        $document = DocumentMapper::unparse($data, $this->classMetadata);

        $this->assertEquals(Person::class, get_class($document));
        $this->assertEquals('Vincent', $document->firstName);
        $this->assertEquals('Touzet', $document->lastName);
    }
}
