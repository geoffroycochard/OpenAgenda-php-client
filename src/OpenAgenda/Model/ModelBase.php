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


abstract class ModelBase implements ModelInterface
{

    /**
     * @return string
     */
    public function getShortName()
    {
        $reflect = new \ReflectionClass($this);
        return $reflect->getShortName();
    }

    public function getMeta($type)
    {
        if (array_key_exists($type, $this->getMetas())) {
            return self::$metas[$type];
        }
        return false;
    }

    public function getMetasToValidate()
    {
        $a = [];
        foreach ($this->getMetas() as $key => $value) {
            if ($value['type'] == 'oneToMany') {
                $getter = 'get'.ucfirst($key);
                $datas = call_user_func([$this, $getter]);
                foreach ($datas as $data) {
                    $data->validate();
                }
            }

            # TODO : Add default Validator from Type
            if (!empty($value['validator'])) {
                $a[$key] = $value['validator'];
            }
        }
        return $a;
    }

    public function validate()
    {
        $metas = $this->getMetasToValidate();

        if (empty($metas)) return true;

        foreach ($metas as $key => $validators) {
            foreach ($validators as $type => $param) {
                $validator = 'OpenAgenda\\Validator\\'.ucfirst($type.'Validator');
                $v = new $validator;
                $method = new \ReflectionMethod($validator, 'validate');
                $method->invoke($v,$this, $key, $param);
            }
        }
        return true;
    }

    public function getMetas()
    {
        return static::$metas;
    }

}