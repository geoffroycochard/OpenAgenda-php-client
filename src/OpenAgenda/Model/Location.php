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

class Location extends ModelBase
{
    protected static $metas = [
        'placeName' => [
            'type' => 'string',
            'validator' => [
                'required' => true,
                'minLength' => 5,
                'maxLength' => 255
            ]
        ],
        'address'   => [
            'type' => 'string',
            'validator' => [
                'required' => true,
                'minLength' => 10,
                'maxLength' => 255
            ]
        ],
        'latitude'  => [
            'type' => 'float',
            'validator' => [
                'required' => true,
                'latitude' => true
            ]
        ],
        'longitude' => [
            'type' => 'float',
            'validator' => [
                'required' => true,
                'longitude' => true
            ]
        ]
    ];

    private $id;

    private $placeName;

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
    public function getPlaceName()
    {
        return $this->placeName;
    }

    /**
     * @param mixed $placeName
     * @return Location
     */
    public function setPlaceName($placeName)
    {
        $this->placeName = $placeName;
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


//    public function validate()
//    {
//        // All are required
//        foreach (get_object_vars($this) as $property => $value) {
//            if ($property === 'id') continue;
//            if (empty($value)) {
//                throw new ModelValidatorException(get_class($this), $property, 'required');
//            }
//        }
//
//        if (!preg_match('/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/', $this->getLatitude())) {
//            throw new ModelValidatorException(get_class($this), 'latitude', 'is not a latitude format');
//        }
//
//        if (!preg_match('/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,6}$/', $this->getLongitude())) {
//            throw new ModelValidatorException(get_class($this), 'longitude', 'is not a longitude format');
//        }
//    }
//
    public function toArrayToOA()
    {
        return [
            'placename' => $this->getPlaceName(),
            'address'   => $this->getAddress(),
            'latitude'  => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        ];
    }
//
//    public function getMetas()
//    {
//        return self::$metas;
//    }
}