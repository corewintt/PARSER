<?php

/**
 *
 */
class valid
{

    public static function char($value = '')
    {
        $rez = @iconv('utf8', 'cp1251//IGNORE', $value);
        $rez = @iconv('cp1251', 'utf8', $rez);
        if(($rez) == ($value))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public static function domain($domain)
    {
        if(preg_match('#^[A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+$#i', $domain))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }

    }


}
