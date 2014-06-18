<?php

/**
 * class dowload http doc
 */
class connect {

	const TIMEOUT = 25;

	static function get($domain, $path, $proxy = false, $post_data = false, $ccie = false) {

		if ($post_data) {
			$opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded', 'content' => $post_data));
		} else {
			$opts = array('http' => array('method' => 'GET'));
		}
		$opts['http']['max_redirects'] = 0;
		$opts['http']['ignore_errors'] = 1;
		if (is_array($proxy)) {
			$opts['http'] = array('proxy' => 'tcp://' . $proxy['url'], 'request_fulluri' => true);
			if ($proxy['user']) {
				$auth = base64_encode($proxy['user'] . ':' . $proxy['pass']);
				$opts['http']['header'] = "Proxy-Authorization: Basic $auth";
			}

		}
		//timeout
		$opts['http']['timeout'] = self::TIMEOUT;

		$url = 'http://' . $domain . $path;
		
		$context = stream_context_create($opts);
		$stream = fopen($url, 'r', false, $context);
		// header information as well as meta data
		// about the stream
		$data = stream_get_meta_data($stream);
		
		$headers = array();
		foreach ($data['wrapper_data'] as $val) {
			$poz = strpos($val, ':');
			if ($poz !== false) {
				$name = substr($val, 0, $poz);
				$val = substr($val, $poz + 1, strlen($val));
			} else {
				$name = 0;
			}
			$headers[strtolower($name)] = trim($val);
		}

		// actual data at $url
		$data['headers'] = $headers;
		$site = string::clean_txt(stream_get_contents($stream));
		$site = string::autoencode($site);
		//var_dump($site);
		fclose($stream);
		return array('data' => $data, 'site' => $site);
	}

}