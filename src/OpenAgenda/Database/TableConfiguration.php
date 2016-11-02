<?php
/**
 * Created by PhpStorm.
 * User: geoffroycochard
 * Date: 26/10/2016
 * Time: 14:05
 */

namespace OpenAgenda\Database;


class TableConfiguration
{

    private $tableConfigPath;

    public function __construct($tableConfigPath)
    {
        $this->tableConfigPath = $tableConfigPath;
    }

    public function getTables() {
        $tables = json_decode(file_get_contents($this->tableConfigPath));
        return $tables;
    }

    public function getTable($name)
    {
        $tables = $this->getTables();

        if (!array_key_exists($name, $tables)) {
            throw new DatabaseException(sprintf('% does not exist in configuration file', $name));
        }

        return $tables->{$name};
    }

    public function getFieldsConfiguration($name)
    {
        $conf = [];
        foreach ($this->getTable($name)->fields as $field) {
            $conf[$field->key_lo] = $field->type;
        }
        return $conf;
    }

    public function getTranslatedFields($name)
    {
        $conf = [];
        foreach ($this->getTable($name)->fields as $field) {
            $translate = property_exists($field,'translate') ? $field->translate : false;
            $conf[$field->key_lo] = [
                'key' => $field->key_oa,
                'translated' => $translate
            ];
        }
        return $conf;
    }

}