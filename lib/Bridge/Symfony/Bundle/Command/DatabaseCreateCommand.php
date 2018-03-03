<?php

/*
 * This file is part of the Sauron package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Bridge\Symfony\Bundle\Command;

/**
 * Class DatabaseCreateCommand
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DatabaseCreateCommand extends \RethinkDB\ODM\Console\Command\DatabaseCreateCommand
{
    public function __construct(\RethinkDB\ODM\Manager $manager)
    {
        parent::__construct($manager);
        $this->setName('rethinkdb:' . $this->getName());
    }
}
