<?php
/**
 * This file is part of the OpenAgenda library client.
 *
 * Copyright (c) 2016. Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenAgenda\Model;


class ModelBase implements ModelInterface
{

    /**
     * @return string
     */
    public function getShortName()
    {
        $reflect = new \ReflectionClass($this);
        return $reflect->getShortName();
    }

    public function getId()
    {
        return false;
    }

    public function validate()
    {
        return true;
    }

    public function toArray()
    {
        return [];
    }
}