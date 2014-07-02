<?php

/**
 *
 */
class url extends AnotherClass
{

    protected $_url = '';

    function __construct($argument)
    {
        $this->_url = $argument;
        $this->_parsed_url = parse_url($argument);
    }

    public function getPath()
    {
        $base = $this->_parsed_url;
        unset($base['scheme']);
        unset($base['host']);
        unset($base['port']);
        unset($base['user']);
        unset($base['pass']);
        unset($base['fragment']);

        return $this->unparse_url($base);
    }

    private function _unparse_url($parsed_url)
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * Коректирует .. в путях
     * */
    static function pathCorrector($value = '')
    {
        if($value == '')
        {
            return '/';
        }
        if(substr($value, -1, 1) == '/')
        {
            $end_slash = '/';
        }
        else
        {
            $end_slash = '';
        }
        $apath = explode('/', $value);
        $skip = FALSE;
        $path = '';
        foreach($apath as $key => $value)
        {
            if($value != '')
            {

                if($value == '..')
                {
                    $skip = TRUE;
                }
                elseif($value == '.')
                {
                    // skip  true;
                }
                else
                {
                    if(!$skip)
                    {
                        if(preg_match('#[А-Я]#i', $value))
                        {
                            $path .= '/' . urlencode($value);
                        }
                        else
                        {
                            $path .= '/' . ($value);
                        }
                    }
                    else
                    {
                        $skip = FALSE;
                    }
                }
            }
        }
        $retrrn = $path . $end_slash;

        return $retrrn;
    }
}
