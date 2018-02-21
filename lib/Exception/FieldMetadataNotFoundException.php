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
 * Class FieldMetadataNotFoundException.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class FieldMetadataNotFoundException extends \Exception
{
    public function __construct(string $class, string $propertyName)
    {
        parent::__construct(sprintf(
            'There is no field metadata named %s for the class %s.',
            $propertyName,
            $class
        ));
    }
}
