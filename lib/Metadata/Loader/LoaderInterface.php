<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Metadata\Loader;

/**
 * Interface LoaderInterface.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
interface LoaderInterface
{
    /**
     * Load class metadatas.
     *
     * @return \RethinkDB\ODM\Metadata\ClassMetadata[]
     */
    public function load();
}
