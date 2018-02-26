<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Tests\Schema;

use PHPUnit\Framework\TestCase;
use RethinkDB\ODM\Schema\Diff;
use RethinkDB\ODM\Tests\ManagerAwareTestCase;

class DiffTest extends TestCase
{
    use ManagerAwareTestCase;

    public function testGetTablesDiff()
    {
        $diff = new Diff($this->getManager());

        $tables = $diff->getTablesDiff();
        $this->assertEquals(1, count($tables));

        \r\tableCreate('person')->run($this->getManager()->getConnection());

        $tables = $diff->getTablesDiff();
        $this->assertEquals(0, count($tables));

        // clean
        \r\tableDrop('person')->run($this->getManager()->getConnection());
    }
}
