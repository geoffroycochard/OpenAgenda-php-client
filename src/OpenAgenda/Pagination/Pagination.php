<?php

/**
 * This file is part of the OpenAgenda library client.
 *
 * Copyright (c) 2016. Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenAgenda\Pagination;

class Pagination
{

    private $totalItems;
    private $totalPages;
    private $limit;
    private $page;
    private $range;

    private $items = [];

    public function __construct($totalItems, $limit, $page, $range)
    {
        $this->totalItems = $totalItems;
        $this->limit = $limit;
        $this->page = $page;

        $this->totalPages = ceil($totalItems / $limit);
        $this->range = is_numeric($range) ? $range : 5;
    }
    
    public function parse()
    {
        //previous link params
        $item = new Item();
        $item->setType('previous')
            ->setNumber($this->page-1);
        $this->setItem($item);
        if ($this->page == 1) $item->setIsclickable(false);

        //do ranged pagination only when total pages is greater than the range
        if ($this->totalPages > $this->range){
            $start = ($this->page <= $this->range)?1:($this->page - $this->range);
            $end   = ($this->totalPages - $this->page >= $this->range)?($this->page+$this->range): $this->totalPages;
        } else {
            $start = 1;
            $end   = $this->totalPages;
        }


        //loop through page numbers
        for($i = $start; $i <= $end; $i++){
            $item = new Item();
            $item->setType('page')
                ->setNumber($i);
            if ($i == $this->page) {
                $item
                    ->setIsCurrent(true)
                    ->setIsclickable(false);
            } 
            $this->setItem($item);
        }

        //next link button
        $item = new Item();
        $item->setType('next')
            ->setNumber($this->page+1);
        $this->setItem($item);
        if ($this->page == $this->totalPages) $item->setIsclickable(false);
        

        return $this->getItems();
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    public function setItem($item)
    {
        $this->items[] = $item;
    }


}