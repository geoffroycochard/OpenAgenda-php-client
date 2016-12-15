<?php
/**
 * This file is part of the OpenAgenda library client.
 *
 * Copyright (c) 2016. Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: geoffroycochard
 * Date: 21/11/2016
 * Time: 11:44
 */

namespace OpenAgenda\Model;


class Date extends ModelBase
{
    protected static $metas = [
        'date' => [
            'type' => 'date',
            'validator' => [
                'required' => true
            ]
        ],
        'timeStart'   => [
            'type' => 'time',
            'validator' => [
                'required' => true
            ]
        ],
        'timeEnd'  => [
            'type' => 'time',
            'validator' => [
                'required' => true
            ]
        ]
    ];

    private $date;

    private $timeStart;

    private $timeEnd;
    
    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return Date
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * @param mixed $timeStart
     * @return Date
     */
    public function setTimeStart($timeStart)
    {
        $this->timeStart = $timeStart;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimeEnd()
    {
        return $this->timeEnd;
    }

    /**
     * @param mixed $timeEnd
     * @return Date
     */
    public function setTimeEnd($timeEnd)
    {
        $this->timeEnd = $timeEnd;
        return $this;
    }



    public function getId()
    {
        return false;
    }

    public function toArrayToOA()
    {
        return [
            'date'  => $this->getDate(),
            'timeStart' => $this->getTimeStart(),
            'timeEnd'   => $this->getTimeEnd()
        ];
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}