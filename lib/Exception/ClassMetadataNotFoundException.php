<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Exception;

/**
 * Class ClassMetadataNotFoundException.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class ClassMetadataNotFoundException extends \Exception
{
    public function __construct($class)
    {
        parent::__construct(sprintf('Class metadata for class "%s" not found.', $class));
    }
}
