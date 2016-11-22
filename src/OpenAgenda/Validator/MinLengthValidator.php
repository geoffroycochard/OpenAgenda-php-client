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



class MinLengthValidator implements ValidatorInterface
{

    public function validate(ModelBase $model, $property, $param)
    {
        $getter = 'get'.ucfirst($property);
        $valueToValidate = call_user_func([$model, $getter]);

        if (strlen($valueToValidate) < $param) {
            throw new ModelValidatorException(get_class($model), $property, 'minLength '.$param);
        }
    }
}