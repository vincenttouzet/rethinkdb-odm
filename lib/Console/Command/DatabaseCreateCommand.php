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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DatabaseCreateCommand.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DatabaseCreateCommand extends AbstractCommand
{
    public function __construct(\RethinkDB\ODM\Manager $manager)
    {
        parent::__construct('database:create', $manager);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $dbName = $this->manager->getConnection()->defaultDbName;

        \r\dbCreate($dbName)->run($this->manager->getConnection());
        $io->success(sprintf('Create database "%s"', $dbName));
    }
}
