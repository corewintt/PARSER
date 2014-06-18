<?php 
/**
 * 
 */
class Command_Server_Start extends Command {
	
	function execute($arg = null) {

		$d = new Daemon_Service_Server;
		$d->startDaemon();
		
	}
}