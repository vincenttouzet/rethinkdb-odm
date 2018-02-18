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

use RethinkDB\ODM\Exception\MissingMappingInfoException;
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

    public function __construct(array $mappings)
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
            if (!isset($mapping['class']) || empty($mapping['class'])) {
                throw new MissingMappingInfoException('class');
            }
            if (!isset($mapping['table']) || empty($mapping['table'])) {
                throw new MissingMappingInfoException('table');
            }
            $classMetadata = new ClassMetadata($mapping['class'], $mapping['table']);
            if (isset($mapping['repositoryClass'])) {
                $classMetadata->setRepositoryClass($mapping['repositoryClass']);
            }
            $metadatas[] = $classMetadata;
        }

        return $metadatas;
    }
}
