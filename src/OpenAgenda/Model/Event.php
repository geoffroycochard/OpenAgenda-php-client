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

class Event extends ModelBase
{
    protected static $metas = [
        'title' => [
            'type' => 'string',
            'validator' => [
                'required' => true,
                'minLength' => 5,
                'maxLength' => 255
            ]
        ],
        'description'  => [
            'type' => 'string',
            'validator' => [
            ]
        ],
        'html'  => [
            'type' => 'string',
            'validator' => [
                'required' => true
            ]
        ],
        'tags' => [
            'type' => 'array',
            'validator' => [
                'required' => true
            ]
        ],
        'location' => [
            'type' => 'integer',
            'validator' => [
                'required' => true
            ]
        ],
        'dates' => [
            'type' => 'oneToMany',
            'model' => 'Date'
        ],
        'image' => [
            'type' => 'string',
            'validator' => [
            ]
        ]
    ];

    private $title;

    private $description;

    private $html;

    private $tags;

    private $location;

    private $dates;

    private $image;

    /**
     * Location constructor.
     */
//    public function __construct()
//    {
//        parent::__construct(self::$metas);
//    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param mixed $html
     * @return Event
     */
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     * @return Event
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }
    /**
     * @param mixed $tag
     * @return Event
     */
    public function setTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDates()
    {
        return $this->dates;
    }

    /**
     * @param mixed $dates
     * @return Event
     */
    public function setDates($dates)
    {
        $this->dates = $dates;
        return $this;
    }

    public function setDate($date)
    {
        $this->dates[] = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     * @return Event
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return Event
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function toArrayToOA()
    {
        $dates = [];
        foreach ($this->getDates() as $date) {
            $dates[] = $date->toArrayToOA();
        }

        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'html' => $this->getHtml(),
            'tags' => implode(',', $this->getTags()),
            'locations' => [[
                'uid' => $this->getLocation(),
                'dates' => $dates
            ]]
        ];
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}