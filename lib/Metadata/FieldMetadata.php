<?php

/*
 * This file is part of the RethinkDB ODM project.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Metadata;

/**
 * Class FieldMetadata.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class FieldMetadata
{
    /**
     * @var string Name of the property in object
     */
    private $propertyName;

    /**
     * @var string Name of the field
     */
    private $fieldName;

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName
     *
     * @return self
     */
    public function setPropertyName(string $propertyName)
    {
        $this->propertyName = $propertyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     *
     * @return self
     */
    public function setFieldName(string $fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }
}
