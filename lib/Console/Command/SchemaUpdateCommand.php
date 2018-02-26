<?php

/*
 * This file is part of the rethinkdb-odm package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Console\Command;

use RethinkDB\ODM\Schema\Diff;
use RethinkDB\ODM\Schema\Updater;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class TablesCreateCommand
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class SchemaUpdateCommand extends AbstractCommand
{
    public function __construct(\RethinkDB\ODM\Manager $manager)
    {
        parent::__construct('schema:update', $manager);
    }

    protected function configure()
    {
        $this->addOption('force', 'f', InputOption::VALUE_NONE);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $diff = new Diff($this->manager);
        $updater = new Updater($this->manager);

        $tables = $diff->getTablesDiff();

        if (0 === count($tables)) {
            $io->success('The schema is in sync with mapping.');
        } else {
            if ($input->getOption('force')) {
                $created = $updater->createTables();
                $io->success(sprintf('%d table(s) created.', count($created)));
            } else {
                $io->success(sprintf('%d table(s) to create. Use --force|-f to execute.', count($tables)));
            }
        }
    }
}
