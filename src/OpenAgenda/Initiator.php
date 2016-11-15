<?php
namespace OpenAgenda;

use OpenAgenda\Pagination\Pagination;
use OpenAgenda\Provider\ParameterProvider;
use OpenAgenda\RestClient\RestClient;

/**
 * Created by PhpStorm.
 * User: geoffroycochard
 * Date: 26/10/2016
 * Time: 11:04
 */
class Initiator
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