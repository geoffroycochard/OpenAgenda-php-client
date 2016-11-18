<?php

/**
 * This file is part of the OpenAgenda library client.
 *
 * Copyright (c) 2016. Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenAgenda\Provider;

use OpenAgenda\Model\ModelInterface;

class ModelPersisterProvider
{

    public function persist(ModelInterface $model)
    {
        $model->validate();
    }

}