<?php
namespace OpenAgenda;

use OpenAgenda\Database\Database as DB;
use OpenAgenda\Database\Helpers\Validate;
use OpenAgenda\Database\DatabaseException;
use OpenAgenda\Database\TableConfiguration;
use OpenAgenda\RestClient\RestClient;

/**
 * Created by PhpStorm.
 * User: geoffroycochard
 * Date: 26/10/2016
 * Time: 11:04
 */
class Initiator
{

    private $agendaId;
    private $pathDatabase;
    private $tableConfiguration;
    private $limit;
    private $isNew = false;

    public function __construct(
        $agendaId,
        $pathDatabase = null,
        $limit = 10
    )
    {
        $this->agendaId = $agendaId;
        $this->pathDatabase = $pathDatabase;

        if (!$this->pathDatabase) {
            define('DATABASE_DATA_PATH', realpath(dirname(__FILE__)).'/data/'.$agendaId.'/'); //Path to folder with tables
        }

        if (!is_dir(DATABASE_DATA_PATH)) {
            if (!mkdir(DATABASE_DATA_PATH, 0777, true)) {
                throw new DatabaseException('Echec lors de la création des répertoires...');
            }
        }

        $this->tableConfiguration =  new TableConfiguration(realpath(dirname(__FILE__)).'/data/tables.json');
        $this->limit = $limit;

        $this->checkTablesExists();

        $this->setData();
    }

    private function checkTablesExists()
    {
        $tables = $this->tableConfiguration->getTables();
        foreach ($tables as $table => $fields) {

            try{
                Validate::table($table)->exists();
            } catch(DatabaseException $e){
                DB::create($table, $this->tableConfiguration->getFieldsConfiguration($table));
                $this->isNew = true;
            }

        }


    }

    private function setData($page=1)
    {

        $limit = $this->limit;
        $offset = $limit*($page-1);

        $api = new RestClient([
            'base_url' => sprintf('https://openagenda.com/agendas/%s', $this->agendaId),
            'format' => "json",
            'parameters' => [
                'limit' => $limit,
                'page'  => $page,
                'offset' => $offset
            ]
            //'headers' => ['Authorization' => 'Bearer '.OAUTH_BEARER],
        ]);

        $result = $api->get('events', []);
        //echo '<pre>'; var_export($result);  echo '</pre>'; die();
        if($result->info->http_code != 200) {
            throw new \Exception(sprintf('Error to get export json : %s', $result->error));
        }

        $response = $result->decode_response();
        debug($response,1);
        foreach ($response->events as $event) {
            $table = DB::table('event');
            $row = $table->where('uid', '=', $event->uid)->find();

            $fields = $this->tableConfiguration->getTranslatedFields('event');
            //unset($fields[0]);
            foreach ($fields as $key_lo => $v) {
                $key_oa = $v['key'];

                // Check if key OA is set in events.json export from OA
                if (!property_exists($event,$key_oa)) {
                    continue;
                }

                // Manage Tanslation
                # TODO : Echancement multilanguage
                if ($v['translated']) {
                    $data = is_object($event->{$key_oa}) ? $event->{$key_oa}->fr : $event->{$key_oa};
                } else {
                    $data = $event->{$key_oa};
                }

                # TODO check type
                if (!$data) $data = '';

                $row->{$key_lo} = $data;
            }
            $row->save();
            unset($row);
        }

        // if not all loaded
        if ($response->total > ($limit * $page)) {
            unset($response);
            $this->setData($page + 1);
        }


    }

    public function getQuery($table)
    {
        return DB::table($table);
    }


}