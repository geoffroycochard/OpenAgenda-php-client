<?php
/**
 * Created by PhpStorm.
 * User: geoffroycochard
 * Date: 14/11/2016
 * Time: 15:33
 */

namespace OpenAgenda\Pagination;


class Item
{

    private $type;

    private $isCurrent = false;

    private $number;

    private $isclickable = true;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $label
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsCurrent()
    {
        return $this->isCurrent;
    }

    /**
     * @param mixed $isCurrent
     */
    public function setIsCurrent($isCurrent)
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsclickable()
    {
        return $this->isclickable;
    }

    /**
     * @param boolean $isclickable
     */
    public function setIsclickable($isclickable)
    {
        $this->isclickable = $isclickable;
    }


}