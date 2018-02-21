<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Tests\Document;

use RethinkDB\ODM\Document;

/**
 * Class Person.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class Person extends Document
{
    public $firstName;
    public $lastName;
}
