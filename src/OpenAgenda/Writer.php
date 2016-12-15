<?php

/**
 * This file is part of the OpenAgenda library client.
 *
 * Copyright (c) 2016. Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenAgenda;

use OpenAgenda\Exception\HttpException;
use OpenAgenda\Model\ModelBase;
use OpenAgenda\Provider\ApiActionProvider;
use OpenAgenda\Provider\ModelPersisterProvider;
use OpenAgenda\RestClient\RestClient;

class Writer
{

    private $secret;

    private $token;

    private $restClient;

    private $modelPersisterProvider;

    private $apiActionProvider;

    /**
     * Writer constructor.
     * @param $secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;

        $this->restClient = new RestClient();
        $this->modelPersisterProvider = new ModelPersisterProvider();
        $this->apiActionProvider = new ApiActionProvider();

        $this->generateAccessToken();

    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     * @return Writer
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return RestClient
     */
    public function getRestClient()
    {
        return $this->restClient;
    }

    /**
     * @param RestClient $restClient
     */
    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }
    
    /**
     * @return ModelPersisterProvider
     */
    public function getModelPersisterProvider()
    {
        return $this->modelPersisterProvider;
    }

    /**
     * @param ModelPersisterProvider $modelPersisterProvider
     * @return Writer
     */
    public function setModelPersisterProvider($modelPersisterProvider)
    {
        $this->modelPersisterProvider = $modelPersisterProvider;
        return $this;
    }

    /**
     * @return ApiActionProvider
     */
    public function getApiActionProvider()
    {
        return $this->apiActionProvider;
    }

    /**
     * @param ApiActionProvider $apiActionProvider
     * @return Writer
     */
    public function setApiActionProvider($apiActionProvider)
    {
        $this->apiActionProvider = $apiActionProvider;
        return $this;
    }


    public function generateAccessToken()
    {
        $url = $this->getApiActionProvider()->get('accessToken.request');

        $result = $this->getRestClient()->post($url, [
            'grant_type' => 'authorization_code',
            'code' => $this->getSecret()
        ]);

        if($result->info->http_code != 200) {
            throw new HttpException($result);
        }

        $data = json_decode($result->response, true);
        $this->token = $data['access_token'];
    }

    public function persist(ModelBase $model)
    {
        $this->getModelPersisterProvider()->persist($model);

        // And go to api
        $action = empty($model->getId()) ? 'create' : 'update';

        $ep = $this->getApiActionProvider()->get(strtolower($model->getShortName()).'.'.$action);

        list($url, $method) = explode('|', $ep);

        $url = str_replace('%id%', $model->getId(), $url);
        
        $data = [
            'access_token' => $this->getToken(),
            'nonce' => rand(0, 100000),
            'lang'=> 'fr',
            'data' => json_encode($model->toArrayToOA()),
            'publish' => false
        ];

        if (get_class($model) == 'OpenAgenda\Model\Event' && $model->getImage()) {
            $tempfile = tempnam('.', 'FOO').'.jpg';
            if (@copy($model->getImage(), $tempfile)) {
                $data['image'] = new \CURLFile($tempfile);
            }
        }
//        debug($data,1);
        $result = $this->getRestClient()->post($url, $data);

        if($result->info->http_code != 200) {
            throw new HttpException($result);
        }

        $response = $result->response;
        $json = json_decode($response);
        return $json->uid;

    }

    public function associate($event, $agenda)
    {
        $url = $this->getApiActionProvider()->get('associate.toAgenda');
        $url = str_replace('%id%', $agenda, $url);

        $result = $this->getRestClient()->post($url, [
            'access_token' => $this->getToken(),
            'nonce' => rand(0, 100000),
            'lang'=> 'fr',
            'data' => json_encode([
                'event_uid' => $event
            ])
        ]);

        if($result->info->http_code != 200) {
            throw new HttpException($result);
        }

        $response = $result->response;
        $json = json_decode($response);
        return $json->uid;

    }


}