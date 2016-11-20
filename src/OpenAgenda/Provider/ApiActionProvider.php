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

use OpenAgenda\Exception\ApiActionNotFoundException;

class ApiActionProvider
{

    private $endPoint = 'https://api.openagenda.com/v1';

    private static $actions = [
        'location'  => [
            'create' => 'locations|POST',
            'update' => 'locations/%id%|POST',
            'delete' => 'locations'
        ],
        'accessToken' => [
            'request' => 'requestAccessToken'
        ],
        'event' => [
            'create' => 'events|POST',
            'update' => 'events/%id%|POST',
            'delete' => 'events'
        ],
        'associate' => [
            'toAgenda' => '/agendas/%id%/events'
        ]
    ];

    /**
     * @param $format
     * @return mixed
     */
    public function get($format)
    {
        list($controller,$action) = explode('.',$format);

        if (!array_key_exists($controller, self::$actions)) {
            throw new ApiActionNotFoundException($format.' to find Controller');
        }

        $actions = self::$actions[$controller];
        if (!array_key_exists($action, $actions)) {
            throw new ApiActionNotFoundException($format.' to find Action');
        }

        $ep = $actions[$action];
        if (empty($ep)) {
            throw new ApiActionNotFoundException($format.' is empty');
        }

        $ep = $this->getEndPoint().'/'.$ep;
        
        return $ep;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * @param string $endPoint
     * @return ApiActionProvider
     */
    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
        return $this;
    }





}