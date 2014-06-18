<?php

/**
 *
 */
class Daemon_Service_Server extends daemon {

	protected function getPidFileName() {
		return 'server_listner';
	}

	protected function execute() {

		$socket = stream_socket_server("udp://0.0.0.0:1113", $errno, $errstr, STREAM_SERVER_BIND);
		if (!$socket) {
			die("$errstr ($errno)");
		}
		$i = 0;
		do {
			$pkt = stream_socket_recvfrom($socket, 10000, 0, $peer);
			$i++;
			if ($peer) {
				$hash = md5(rand(0, time())) . '==' . time();
				$pid = pcntl_fork();
				if ($pid == -1)
					die("could not fork");
				if ($pid) {
					// это родительский процесс
					echo date('r') . 'signal from:' . $peer . ' message:' . $pkt . ' -=' . $hash . PHP_EOL;
				} else {
					$className = 'Command_Procedure_' . $pkt;
					echo date('r') . ' new : ' . $className . ' -=' . $hash . PHP_EOL;
					$proccess = new $className($hash);
					$proccess -> execute();
					echo date('r') . ' die : ' . $className . ' -=' . $hash . PHP_EOL;
					die();
				}
			}
		} while ($pkt !== false);
	}

}
