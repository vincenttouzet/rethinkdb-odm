<?php

/*
 * This file is part of the Sauron package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Metadata\Loader;

/**
 * Interface LoaderInterface
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
Interface LoaderInterface
{
    /**
     * Load class metadatas.
     *
     * @return \RethinkDB\ODM\Metadata\ClassMetadata[]
     */
    public function load();
}
