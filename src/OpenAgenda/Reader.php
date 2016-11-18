<?php
/*
 * This file is part of the OpenAgenda library client.
 *
 * (c) Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenAgenda;

use OpenAgenda\Pagination\Pagination;
use OpenAgenda\Provider\ParameterProvider;
use OpenAgenda\RestClient\RestClient;


/**
 * The Initiatior library class.
 *
 * @author Geoffroy Cochard <geoffroy.cochard@orleans-agglo.fr>
 */
class Reader
{

    /**
     * @var
     */
    private $agendaId;

    /**
     * @var
     */
    private $query;

    /**
     * @var
     */
    private $total;

    private $parameterProvider;

    public function __construct($agendaId)
    {
        $this->agendaId = $agendaId;
    }

    /**
     * @param array $query
     * @return mixed
     * @throws \Exception
     */
    public function getEvents($query = array())
    {

        $this->query = $query;

        $this->parameterProvider = new ParameterProvider($query);

        $limit = $this->parameterProvider->getLimit();
        $offset = $limit*($this->parameterProvider->getPage()-1);


        $parameters = array_merge([
            'limit' => $limit,
            'page'  => $this->parameterProvider->getPage(),
            'offset' => $offset
        ],$this->parameterProvider->getExtraQueries());


        $api = new RestClient([
            'base_url' => sprintf('https://openagenda.com/agendas/%s', $this->agendaId),
            'format' => 'json',
            'parameters' => $parameters
        ]);

        $result = $api->get('events', []);

        if($result->info->http_code != 200) {
            throw new \Exception(sprintf('Error to get export json : %s', $result->error));
        }

        $response = $result->decode_response();

        if (isset($response->total)) {
            $pagination = new Pagination(
                $response->total,
                $this->parameterProvider->getLimit(),
                $this->parameterProvider->getPage(),
                $this->parameterProvider->getPaginationRange()
            );

            $response->pagination = $pagination->parse();
        }


        return $response;
    }

    /**
     * To match one event
     * @param $id
     * @return bool
     * @throws \Exception
     * @todo : handling errors
     */
    public function getEvent($id)
    {
        $response = $this->getEvents([
            'oaq' => [
                'uids' => [$id]
            ]
        ]);

        if (
            !isset($response->total) ||
            $response->total == 0 ||
            $response->total > 1
        ) {
            return false;
        }

        return $response->events[0];
    }



}