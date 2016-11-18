<?php
namespace OpenAgenda\Exception;

/**
 * This file is part of the OpenAgenda library client.
 *
 * Copyright (c) 2016. Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ModelValidatorException extends \RuntimeException
{


    /**
     * ModelValidatorException constructor.
     * @param string $object
     * @param int $property
     * @param string $violation
     */
    public function __construct($object, $property, $violation)
    {
        parent::__construct(sprintf(
            'Model %s error violation for %s while persisting : %s',
            $object,
            $property,
            $violation
        ));
    }
}