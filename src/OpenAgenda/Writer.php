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

        $this->setAccessToken();

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

    public function setAccessToken()
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
        return $data['access_token'];
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

    public function persist(ModelBase $model)
    {
        $this->getModelPersisterProvider()->persist($model);

        // And go to api
        // find end point
        $action = empty($model->getId()) ? 'create' : 'update';

        $ep = $this->getApiActionProvider()->get(strtolower($model->getShortName()).'.'.$action);

        list($url, $method) = explode('|', $ep);

        $url = str_replace('%id%', $model->getId(), $url);

        $result = $this->getRestClient()->post($url, [
            'access_token' => $this->token,
            'nonce' => rand(0, 100000),
            'data' => json_encode($model->toArray())
        ]);

        debug($result,1);

        if($result->info->http_code != 200) {
            throw new HttpException($result);
        }

    }



}