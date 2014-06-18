<?php
/**
 * 
 */
class Command_Server_Status extends Command {
	
	function execute($arg = null) {

		$d = new Daemon_Service_Server;
		
		echo $d->getStatus();
		
	}
}