<?php

/**
 *
 */
class Command_Send extends Command
{

    function execute($arg = NULL)
    {


        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);

        $broadcast_string = "iwannaknow";
        // Identifies the source of the message
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, TRUE);
        socket_sendto($sock, $broadcast_string, strlen($broadcast_string), MSG_EOF,
                      '192.168.10.255', 1113);

        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);

        echo ("[$errorcode] $errormsg") . PHP_EOL . PHP_EOL;

    }
}