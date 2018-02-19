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
 * Class MissingMappingInfoException.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class MissingMappingInfoException extends \Exception
{
    public function __construct($missingData)
    {
        parent::__construct(sprintf('You need to specify %s metadata.', $missingData));
    }
}
