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

use RethinkDB\ODM\Metadata\ClassMetadata;

/**
 * Class ArrayLoader
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class ArrayLoader implements LoaderInterface
{
    /** @var array */
    protected $mappings = [];

    public function __construct($mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * @inheritdoc
     */
    public function load()
    {
        $metadatas = [];

        foreach ($this->mappings as $mapping) {
            $classMetadata = new ClassMetadata($mapping['class'], $mapping['table']);
            $metadatas[] = $classMetadata;
        }

        return $metadatas;
    }
}
