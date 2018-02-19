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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DatabaseDropCommand.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DatabaseDropCommand extends AbstractCommand
{
    public function __construct(\RethinkDB\ODM\Manager $manager)
    {
        parent::__construct('database:drop', $manager);
    }

    protected function configure()
    {
        $this->addOption('force', 'f', InputOption::VALUE_NONE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $dbName = $this->manager->getConnection()->defaultDbName;

        if ($input->getOption('force')) {
            \r\dbDrop($dbName)->run($this->manager->getConnection());
            $io->success(sprintf('Drop database "%s"', $dbName));
        } else {
            $io->warning(sprintf('You are about to drop the database "%s". Use --force to execute drop.', $dbName));
        }
    }
}
