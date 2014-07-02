<?php

/**
 * Интерфейс для работы с БД
 */
class db extends config
{
    private $debug = 1;
    private $exxit = TRUE;

    private $db = NULL;

    public $error = NULL;

    function __construct($opt = array())
    {
        if(!empty($opt['debug']))
        {
            $this->debug($opt['debug']);
        }
        if(!empty($opt['exxit']))
        {
            $this->exxit($opt['exxit']);
        }

        if(@$opt['main'] == 1)
        {
            $this->connect = $this->db_main;
        }
        else
        {

            if($opt['user'] != '')
            {
                $this->connect['user'] = $opt['user'];
            }
            if($opt['pass'] != '')
            {
                $this->connect['pass'] = $opt['pass'];
            }
            if($opt['host'] != '')
            {
                $this->connect['host'] = $opt['host'];
            }
            if($opt['db'] != '')
            {
                $this->connect['db'] = $opt['db'];
            }
            if($opt['port'] != '')
            {
                $this->connect['port'] = $opt['port'];
            }
            if($opt['charset'] != '')
            {
                $this->connect['charset'] = $opt['charset'];
            }
        }

        if($this->debug)
        {
            var_dump($this->connect);
        }

        $this->connect();
    }

    function connect()
    {
        $this->error = NULL;
        $mysqli = new mysqli($this->connect['host'], $this->connect['user'], $this->connect['pass'], $this->connect['db'], $this->connect['port']);

        /* check connection */
        if(mysqli_connect_errno())
        {
            if($this->exxit)
            {
                var_dump("Connect failed: " . mysqli_connect_error());
                exit();
            }
            else
            {
                if($this->debug)
                {
                    var_dump("Connect failed: " . mysqli_connect_error());
                }
                $this->error = "Connect failed: " . mysqli_connect_error();

                return FALSE;
            }
        }

        /* change character set to  */
        if(!$mysqli->set_charset($this->connect['charset']))
        {
            if($this->exxit)
            {
                var_dump("Error loading character set : " . $mysqli->error);
                exit();
            }
            else
            {
                if($this->debug)
                {
                    var_dump("Error loading character set : " . $mysqli->error);
                }
                $this->error = "Error loading character set : " . $mysqli->error;

                return FALSE;
            }
        }
        else
        {
            if($this->debug)
            {
                var_dump("Current character set: " . $mysqli->character_set_name());
            }
        }

        $this->db = $mysqli;
    }

    public function __destructor()
    {
        $this->close();
    }

    public function close()
    {
        if($this->debug)
        {
            var_dump('Connect close');
        }
        mysqli_close($this->db);
    }


    public function debug($value = TRUE)
    {
        if($value == TRUE)
        {
            $this->debug = TRUE;
        }
        else
        {
            $this->debug = FALSE;
        }
    }

    public function exxit($value = TRUE)
    {
        if($value == TRUE)
        {
            $this->exxit = TRUE;
        }
        else
        {
            $this->exxit = FALSE;
        }
    }

    /****
     * Quotes a string so it can be safely used in a query. It will quote the text so it can safely be used within a query.
     * */
    public function slash($value = '')
    {
        return $this->db->real_escape_string($value);
    }

    /**
     * функция обработки запросов с ошибками
     */
    function qList($sql)
    {
        $this->error = NULL;
        if($this->debug)
        {
            var_dump($sql);
            $time = gettimeofday(TRUE);
        }

        /* Посылаем запрос серверу */
        if($result = $this->db->query($sql))
        {
            /* Выбираем результаты запроса: */
            while($row = $result->fetch_assoc())
            {
                $rows[] = @array_change_key_case($row);
            }
            /* Освобождаем память */
            $result->close();
        }
        else
        {
            if($this->exxit)
            {
                var_dump("Could not successfully run query ($sql): " . $this->db->error);
                exit();
            }
            else
            {
                $err = "Could not successfully run query ($sql): " . $this->db->error;
                if($this->debug)
                {
                    var_dump($err);
                }
                $this->error = $err;

                return FALSE;
            }
        }

        if($this->debug)
        {
            var_dump('qList comlite:' . round((gettimeofday(TRUE) - $time) * 1000, 3));
        }

        return @$rows;

    }

    public function qRow($sql = '')
    {
        $this->error = NULL;
        if($this->debug)
        {
            var_dump($sql);
            $time = gettimeofday(TRUE);
        }

        /* Посылаем запрос серверу */
        if($result = $this->db->query($sql))
        {
            /* Выбираем результаты запроса: */
            $row = $result->fetch_assoc();
            $rows = @array_change_key_case($row);

            /* Освобождаем память */
            // $result->close();

        }
        else
        {
            if($this->exxit)
            {
                var_dump("Could not successfully run query ($sql): " . $this->db->error);
                exit();
            }
            else
            {
                $err = "Could not successfully run query ($sql): " . $this->db->error;
                if($this->debug)
                {
                    var_dump($err);
                }
                $this->error = $err; //"Could not successfully run query: ". $this->db->error;
                return FALSE;
            }
        }

        if($this->debug)
        {
            var_dump('qRow comlite ' . round((gettimeofday(TRUE) - $time) * 1000, 3));
        }

        return @$rows;

    }

    /**
     * Function return last insert id
     *
     * @param mixed $sql
     */
    public function q($sql)
    {
        $this->error = NULL;
        if($this->debug)
        {
            var_dump($sql);
            $time = gettimeofday(TRUE);
        }

        /* Посылаем запрос серверу */
        if($result = $this->db->query($sql))
        {
            /* Выбираем результаты запроса: */
            $last = $this->db->insert_id;
            /* Освобождаем память */
        }
        else
        {
            if($this->exxit)
            {
                var_dump("Could not successfully run query ($sql): " . $this->db->error);
                exit();
            }
            else
            {
                $err = "Could not successfully run query ($sql): " . $this->db->error;
                if($this->debug)
                {
                    var_dump($err);
                }
                $this->error = $err;

                return FALSE;
            }
        }


        if($this->debug)
        {
            var_dump('query comlite ' . round((gettimeofday(TRUE) - $time) * 1000, 3));
        }


        if($last)
        {
            return $last;
        }
    }

    /**
     * function return affective rows
     *
     * @param mixed $sql
     */

    public function aq($sql)
    {
        $this->error = NULL;

        if($this->debug)
        {
            var_dump($sql);
            $time = gettimeofday(TRUE);
        }

        if($result = $this->db->query($sql))
        {
            /* Выбираем результаты запроса: */
            $arow = $this->db->affected_rows;
            /* Освобождаем память */
        }
        else
        {
            if($this->exxit)
            {
                var_dump("Could not successfully run query ($sql): " . $this->db->error);
                exit();
            }
            else
            {
                $err = "Could not successfully run query ($sql): " . $this->db->error;
                if($this->debug)
                {
                    var_dump($err);
                }
                $this->error = $err;

                return FALSE;
            }
        }
        if($this->debug)
        {
            var_dump('query comlite ' . round((gettimeofday(TRUE) - $time) * 1000, 3));
        }

        /***
         * Возврашает количество затронутых рядов
         */

        return $arow;
    }

}
