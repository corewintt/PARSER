<?php

/**
 *
 */
class site
{

    protected static $_instance;

    private function __construct()
    {
    }

    public static function get()
    {
        if(self::$_instance === NULL)
        {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /* End */
    /***
     * get by domain name
     * */
    public function id($id)
    {

        $cache = memo::get();
        if(strlen((int)$id) == strlen((string)$id) && (int)$id > 0)
        {
            $val = $cache->g('site', '', $id);
            if($val)
            {
                return $val;
            }
            $db = new db(array('main' => 1, 'debug' => 0));
            $sql = 'SELECT * FROM `domains` WHERE `id`="' . $db->slash($id) . '"';
            $row = $db->qRow($sql);
            if($row)
            {
                $val = $cache->set('site', '', $id, $row['domain']);

                return $row['domain'];
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Get By ID
     *
     */
    public function site($domain)
    {
        $cache = memo::get();
        if(connect::check_domain($domain))
        {
            $val = $cache->g('site', '', $domain);
            if($val)
            {
                $domain = $cache->g('site', '', $val);

                return array('id' => $val, 'domain' => $domain);
            }
            else
            {
                $db = new db(array('main' => 1, 'debug' => 0));
                $sql = 'SELECT * FROM `domains` WHERE `domain`="' . $db->slash($domain) . '"';
                $row = $db->qRow($sql);
                if(empty($row))
                {
                    $sql = 'INSERT INTO `domains` (`domain`) VALUES ("' . $db->slash($domain) . '")';
                    $row = $db->q($sql);
                    $sql = 'SELECT * FROM `domains` WHERE `id`="' . $row . '"';
                    $row = $db->qRow($sql);
                }
            }
            $val = $cache->set('site', '', $domain, $row['id']);
            $val = $cache->set('site', '', $row['id'], $domain);
        }
        else
        {
            return FALSE;
        }

        return $row;
    }

}
