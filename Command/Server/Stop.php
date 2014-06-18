<?php 
/**
 * 
 */
class Command_Server_Stop extends Command {
	
	function execute($arg = null) {

		$d = new Daemon_Service_Server;
		$d->stopDaemon();
		
	}
}