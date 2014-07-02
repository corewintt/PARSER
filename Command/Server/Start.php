<?php

/**
 *
 */
class Command_Server_Start extends Command
{

    function execute($arg = NULL)
    {

        $d = new Daemon_Service_Server;
        $d->startDaemon();

    }
}