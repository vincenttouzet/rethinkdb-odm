<?php

/*
 * This file is part of the Sauron package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM;

/**
 * Class Document
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class Document
{
    /** @var string */
    private $id;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
