<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Console\Command;

use RethinkDB\ODM\Manager;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractCommand.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
abstract class AbstractCommand extends Command
{
    /** @var \RethinkDB\ODM\Manager */
    protected $manager;

    public function __construct(string $name, Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct($name);
    }
}
