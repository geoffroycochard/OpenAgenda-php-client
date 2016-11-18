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

class HttpException extends \RuntimeException
{

    /**
     * HttpException constructor.
     * @param string $result
     */
    public function __construct($result)
    {
        $error = json_decode($result->response);
        parent::__construct(
            sprintf('%s : %s.', $error->error, $error->error_description),
            400
        );
    }
}