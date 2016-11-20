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

use OpenAgenda\Exception\ModelValidatorException;

class Location extends ModelBase
{
    private $id;

    private $placename;

    private $address;

    private $latitude;
    
    private $longitude;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Location
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlacename()
    {
        return $this->placename;
    }

    /**
     * @param mixed $placename
     * @return Location
     */
    public function setPlacename($placename)
    {
        $this->placename = $placename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Location
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     * @return Location
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     * @return Location
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function validate()
    {
        // All are required
        foreach (get_object_vars($this) as $property => $value) {
            if ($property === 'id') continue;
            if (empty($value)) {
                throw new ModelValidatorException(get_class($this), $property, 'required');
            }
        }

        if (!preg_match('/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/', $this->getLatitude())) {
            throw new ModelValidatorException(get_class($this), 'latitude', 'is not a latitude format');
        }

        if (!preg_match('/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,6}$/', $this->getLongitude())) {
            throw new ModelValidatorException(get_class($this), 'longitude', 'is not a longitude format');
        }
    }

    public function toArray()
    {
        return [
            'placename' => $this->getPlacename(),
            'address'   => $this->getAddress(),
            'latitude'  => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        ];
    }
}