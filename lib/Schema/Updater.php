<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Schema;

use RethinkDB\ODM\Manager;

/**
 * Class Updater.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class Updater
{
    /** @var \RethinkDB\ODM\Manager */
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Create tables.
     */
    public function createTables()
    {
        $tables = (new Diff($this->manager))->getTablesDiff();

        foreach ($tables as $table) {
            \r\tableCreate($table)->run($this->manager->getConnection());
        }

        return $tables;
    }
}
