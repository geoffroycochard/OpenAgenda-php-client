<?php
/*
 * This file is part of the OpenAgenda library client.
 *
 * (c) Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenAgenda\Provider;

class ParameterProvider
{

    private $query;

    public function __construct($query = [])
    {
        $this->query = $query;
    }

    public function getLimit()
    {
        return $this->getQueryVar('limit') ? $this->getQueryVar('limit') : 10;
    }

    public function getPage()
    {
        return $this->getQueryVar('page') ? $this->getQueryVar('page') : 1;
    }

    public function getPaginationRange()
    {
        return $this->getQueryVar('pagination.range');
    }

    /**
     * @param $var
     * @return mixed
     * @todo : check type var
     */
    private function getQueryVar($var)
    {
        if (strpos($var, '.')) {
            $a = explode('.', $var);
            if (isset($this->query[$a[0]][$a[1]])) {
                return $this->query[$a[0]][$a[1]];
            } else {
                return false;
            }
        } else {
            if (array_key_exists($var, $this->query)) {
                return $this->query[$var];
            } else {
                return false;
            }
        }
    }

    public function getExtraQueries()
    {
        if(!array_key_exists('oaq', $this->query)) return [];

        $query = [];
        foreach ($this->query['oaq'] as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $value) {
                    $query['oaq['.$k.'][]'] = $value;
                }
            } else {
                $query['oaq['.$k.']'] = $v;
            }
        }

        return $query;
    }

}