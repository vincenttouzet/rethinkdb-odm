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
use RethinkDB\ODM\Metadata\ClassMetadata;

class ClassMetadataTest extends TestCase
{
    /**
     * @expectedException  \RethinkDB\ODM\Exception\FieldMetadataNotFoundException
     * @expectedExceptionMessage There is no field metadata named someField for the class SomeClass.
     */
    public function testFieldMetadataNotFoundException()
    {
        $classMetadata = new ClassMetadata('SomeClass', 'some_table', []);
        $classMetadata->getFieldMetadata('someField');
    }
}
