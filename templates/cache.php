<?php

/**
 *
 */
abstract class cache
{

    private $cache;

    private function __construct($argument)
    {

    }

    /***
     * Кеш в массиве нужен для хранения данных внутри скрипта стобы не засирать общий пулл мем кеша
     * */
    private function get($value = '')
    {
        return @self::$cache[$value];
    }

    private function set($value = '', $row)
    {
        @self::$cache[$value] = $row;
    }
}
