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

use OpenAgenda\Exception\EndPointNotFoundException;

class EndPointProvider
{

    private $url = 'https://api.openagenda.com/v1';

    private $location = [
        'create' => 'locations|POST',
        'update' => 'locations/%id%|POST',
        'delete' => 'locations'
    ];

    private $accessToken = [
        'request' => 'requestAccessToken'
    ];

    private $event = 'events';

    private $associateToAgenda = '/agendas/%s/events';

    /**
     * @param $format
     * @return mixed
     */
    public function get($format)
    {
        list($controller,$action) = explode('.',$format);

        if (!property_exists($this, $controller)) {
            throw new EndPointNotFoundException($format.' to find Controller');
        }

        $actions = $this->$controller;
        if (!array_key_exists($action, $actions)) {
            throw new EndPointNotFoundException($format.' to find Action');
        }

        $ep = $actions[$action];
        if (empty($ep)) {
            throw new EndPointNotFoundException($format.' is empty');
        }

        $ep = $this->getUrl().'/'.$ep;

        return $ep;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return EndPointProvider
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }



}