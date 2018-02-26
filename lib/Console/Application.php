<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Console;

use RethinkDB\ODM\Console\Command\DatabaseCreateCommand;
use RethinkDB\ODM\Console\Command\DatabaseDropCommand;
use RethinkDB\ODM\Console\Command\SchemaUpdateCommand;
use RethinkDB\ODM\Manager;

/**
 * Class Application.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class Application extends \Symfony\Component\Console\Application
{
    public function __construct(Manager $manager)
    {
        parent::__construct('PHP RethinkDB ODM', '0.0.0');
        $this->manager = $manager;
        $this->addCommands([
            new DatabaseCreateCommand($manager),
            new DatabaseDropCommand($manager),
            new SchemaUpdateCommand($manager),
        ]);
    }
}
