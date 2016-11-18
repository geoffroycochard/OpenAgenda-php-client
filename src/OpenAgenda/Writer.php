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
use OpenAgenda\Model\ModelInterface;
use OpenAgenda\Provider\EndPointProvider;
use OpenAgenda\Provider\ModelPersisterProvider;
use OpenAgenda\RestClient\RestClient;

class Writer
{

    private $apiKey;

    private $secret;

    private $token;

    private $modelPersisterProvider;

    private $endPointProvider;

    /**
     * Writer constructor.
     */
    public function __construct($apiKey, $secret)
    {
        $this->apiKey = $apiKey;
        $this->secret = $secret;

        $this->modelPersisterProvider = new ModelPersisterProvider();
        $this->endPointProvider = new EndPointProvider();

        $this->setAccessToken();

    }

    public function setAccessToken()
    {
        $url = $this->getEndPointProvider()->get('accessToken.request');
//        $this->token = 'po87gjjGR4567';

        $client = new RestClient();
        $result = $client->post($url,[
            'grant_type' => 'authorization_code',
            'code' => $this->apiKey
        ]);

        if($result->info->http_code != 200) {
            throw new HttpException($result);
        }
        debug('stop',1);
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
     * @return EndPointProvider
     */
    public function getEndPointProvider()
    {
        return $this->endPointProvider;
    }

    /**
     * @param EndPointProvider $endPointProvider
     * @return Writer
     */
    public function setEndPointProvider($endPointProvider)
    {
        $this->endPointProvider = $endPointProvider;
        return $this;
    }

    public function persist(ModelInterface $model)
    {
        $this->getModelPersisterProvider()->persist($model);

        // And go to api
        // find end point
        $action = empty($model->getId()) ? 'create' : 'update';

        $reflect = new \ReflectionClass($model);
        $ep = $this->getEndPointProvider()->get(strtolower($reflect->getShortName()).'.'.$action);

        list($url, $method) = explode('|', $ep);

        $url = str_replace('%id%', $model->getId(), $url);


        $client = new RestClient();

        $result = $client->post($url, [
            'access_token' => $this->token,
            'nonce' => rand(0, 100000),
            'data' => json_encode($model->toArray())
        ]);

        if($result->info->http_code != 200) {
            throw new HttpException($result);
        }

    }



}