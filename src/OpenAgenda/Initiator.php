<?php
namespace OpenAgenda;

use OpenAgenda\Pagination\Pagination;
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

    public function __construct($agendaId)
    {
        $this->agendaId = $agendaId;
    }

    public function getEvents($query = array())
    {

        $this->query = $query;

        $limit = $this->getLimit();
        $offset = $limit*($this->getPage()-1);

        $api = new RestClient([
            'base_url' => sprintf('https://openagenda.com/agendas/%s', $this->agendaId),
            'format' => 'json',
            'parameters' => [
                'limit' => $limit,
                'page'  => $this->getPage(),
                'offset' => $offset
            ]
        ]);

        $result = $api->get('events', []);

        if($result->info->http_code != 200) {
            throw new \Exception(sprintf('Error to get export json : %s', $result->error));
        }

        $response = $result->decode_response();

        $pagination = new Pagination(
            $response->total,
            $this->getLimit(),
            $this->getPage()
        );

        $response->pagination = $pagination->parse();

        return $response;
    }

    private function getLimit()
    {
        return $this->getQueryVar('limit') ? $this->getQueryVar('limit') : 10;
    }

    private function getPage()
    {
        return $this->getQueryVar('page') ? $this->getQueryVar('page') : 1;
    }

    /**
     * @param $var
     * @return mixed
     * @todo : check type var
     */
    private function getQueryVar($var)
    {
        if (array_key_exists($var, $this->query)) {
            return $this->query[$var];
        } else {
            return false;
        }
    }


}