<?php
/**
 * This file is part of the OpenAgenda library client.
 *
 * Copyright (c) 2016. Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace OpenAgenda\Validator;


use OpenAgenda\Exception\ModelValidatorException;
use OpenAgenda\Model\ModelBase;

class LatitudeValidator implements ValidatorInterface
{
    public function validate(ModelBase $model, $property, $param)
    {
        $getter = 'get'.ucfirst($property);
        $valueToValidate = call_user_func([$model, $getter]);
        
        if (!preg_match('/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/', $valueToValidate)) {
            throw new ModelValidatorException(get_class($model), $property, 'is not a latitude format');
        }
    }
}