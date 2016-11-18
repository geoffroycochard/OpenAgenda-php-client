<?php
/**
 * This file is part of the OpenAgenda library client.
 *
 * Copyright (c) 2016. Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenAgenda\Exception;

class EndPointNotFoundException  extends \RuntimeException
{
    /**
     * EndPointNotFoundException constructor.
     * @param string $key
     */
    public function __construct($key)
    {
        parent::__construct(sprintf('EndPoint for action %s is not found', $key));
    }
}