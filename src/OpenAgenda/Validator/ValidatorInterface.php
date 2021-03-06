<?php
namespace OpenAgenda\Validator;

use OpenAgenda\Model\ModelBase;

/**
 * This file is part of the OpenAgenda library client.
 *
 * Copyright (c) 2016. Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

interface ValidatorInterface
{
    public function validate(ModelBase $model, $property, $param);
}