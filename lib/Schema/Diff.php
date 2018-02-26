<?php

/*
 * This file is part of the rethinkdb-odm package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Schema;

use RethinkDB\ODM\Manager;

/**
 * Class Diff
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class Diff
{
    /** @var \RethinkDB\ODM\Manager */
    protected $manager;

    /**
     * Diff constructor.
     *
     * @param \RethinkDB\ODM\Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get the list of non existent tables
     *
     * @return array
     */
    public function getTablesDiff()
    {
        $diff = [];
        $existing = \r\tableList()->run($this->manager->getConnection());

        foreach ($this->manager->getClassMetadataRegistry()->all() as $classMetadata) {
            if (!in_array($classMetadata->getTable(), $existing)) {
                $diff[] = $classMetadata->getTable();
            }
        }

        return $diff;
    }
}
