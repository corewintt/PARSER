<?php

date_default_timezone_set('Europe/Moscow');

class setup
{

    static $APP_DIR = '';
    static $LOG_DIR = '';
    static $CMD_DIR = '';

    /**
     * соединения с управляющей и главной базой
     *
     * @var mixed
     */

    public $connect = array(
        'user'    => 'cluster', //database user
        'pass'    => 'r5uxUSDRzmhRVrPG', //database pass
        'host'    => '127.0.0.1', //database host
        'db'      => 'cluster', //databnase table
        'port'    => 3306, //databnase port
        'charset' => 'utf8' //char set
    );

    public $db_main = array(
        'user'    => 'manager', //database user
        'pass'    => 'K5Lr4SasPDY4DLZC', //database pass
        'host'    => '127.0.0.1', //database host
        'db'      => 'manager', //databnase table
        'port'    => 3306, //databnase port
        'charset' => 'utf8' //char set
    );

    /*memo*/


    public $memo = array(
        '0' => array('h' => 'localhost', 'p' => 11211) //free fo all
    );

    static function autoload($className)
    {
        $path = str_replace('_', '/', $className);
        if(is_file(self::$APP_DIR . '/' . $path . '.php'))
        {
            include_once self::$APP_DIR . '/' . $path . '.php';
        }
        else
        {
            if(is_file(self::$APP_DIR . '/abstract/' . $className . '.php'))
            {
                include_once self::$APP_DIR . '/abstract/' . $className . '.php';
            }
            else if(is_file(self::$APP_DIR . '/static/' . $className . '.php'))
            {
                include_once self::$APP_DIR . '/static/' . $className . '.php';
            }
            else if(is_file(self::$APP_DIR . '/class/' . $className . '.php'))
            {
                include_once self::$APP_DIR . '/class/' . $className . '.php';
            }
        }
    }

    static function init()
    {
        self::$APP_DIR = __DIR__ . '/';
        self::$LOG_DIR = self::$APP_DIR . 'logs/';
        self::$CMD_DIR = self::$APP_DIR . 'Command/';
        spl_autoload_register('setup::autoload');
    }

}

setup::init();


