<?php

namespace OpenAgenda\Database\Helpers;

use OpenAgenda\Database\DatabaseException;

/**
 * File managing class
 *
 * @category Helpers
 * @author Grzegorz Kuźnik
 * @copyright (c) 2013, Grzegorz Kuźnik
 * @license http://opensource.org/licenses/MIT The MIT License
 * @link https://github.com/Greg0/Lazer-Database GitHub Repository
 */
class File implements FileInterface {

    /**
     * File name
     * @var string
     */
    protected $name;

    /**
     * File type (data|config)
     * @var string
     */
    protected $type;

    public static function table($name)
    {
        $file       = new File;
        $file->name = $name;

        return $file;
    }

    public final function setType($type)
    {
        $this->type = $type;
    }

    public final function getPath()
    {
        if (!defined('DATABASE_DATA_PATH'))
        {
            throw new DatabaseException('Please define constant DATABASE_DATA_PATH (check README.md)');
        }
        else if (!empty($this->type))
        {
            return DATABASE_DATA_PATH . $this->name . '.' . $this->type . '.json';
        }
        else
        {
            throw new DatabaseException('Please specify the type of file in class: ' . __CLASS__);
        }
    }

    public final function get($assoc = false)
    {
        return json_decode(file_get_contents($this->getPath()), $assoc);
    }

    public final function put($data)
    {
        return file_put_contents($this->getPath(), json_encode($data));
    }

    public final function exists()
    {
        return file_exists($this->getPath());
    }

    public final function remove()
    {
        $type = ucfirst($this->type);
        if ($this->exists())
        {
            if (unlink($this->getPath()))
                return TRUE;

            throw new DatabaseException($type . ': Deleting failed');
        }

        throw new DatabaseException($type . ': File does not exists');
    }

}
