<?php

/**
 * 
 */
class String {
	
	static function clean_txt($body) {

		$body = str_replace(array("\r", "\n", "\t"), ' ', $body);
		$body = str_replace(">", '> ', $body);
		$body = str_replace("<", ' <', $body);

		while (stripos($body, '  ') !== FALSE) {
			$body = str_replace("  ", ' ', $body);
		}
		return $body;
	}

	static function clean($value) {
		$value_r = $value;
		//  $value_r = html_entity_decode($value_r);
		$value_r = preg_replace("/[^A-ZА-ЯЁ\[\]0-9]+/iuU", " ", (string)$value_r);
		return $value_r;
	}

	static function is_utf8($string) {
		for ($i = 0; $i < strlen($string); $i++) {
			if (ord($string[$i]) < 0x80)
				continue;
			elseif ((ord($string[$i]) & 0xE0) == 0xC0)
				$n = 1;
			elseif ((ord($string[$i]) & 0xF0) == 0xE0)
				$n = 2;
			elseif ((ord($string[$i]) & 0xF8) == 0xF0)
				$n = 3;
			elseif ((ord($string[$i]) & 0xFC) == 0xF8)
				$n = 4;
			elseif ((ord($string[$i]) & 0xFE) == 0xFC)
				$n = 5;
			else
				return false;
			for ($j = 0; $j < $n; $j++) {
				if ((++$i == strlen($string)) || ((ord($string[$i]) & 0xC0) != 0x80))
					return false;
			}
		}
		return true;
	}

	static function autoencode($string, $encoding = 'utf-8') {
		if (self::is_utf8($string))
			$detect = 'utf-8';
		else {
			$cp1251 = 0;
			$koi8u = 0;
			$strlen = strlen($string);
			for ($i = 0; $i < $strlen; $i++) {
				$code = ord($string[$i]);
				if (($code > 223 and $code < 256) or ($code == 179) or ($code == 180) or ($code == 186) or ($code == 191))
					$cp1251++;
				// а-я, і, ґ, є, Ї
				if (($code > 191 and $code < 224) or ($code == 164) or ($code == 166) or ($code == 167) or ($code == 173))
					$koi8u++;
				// а-я, є, і, ї, ґ
			}
			if ($cp1251 > $koi8u)
				$detect = 'windows-1251';
			else
				$detect = 'koi8-u';
		}
		if ($encoding == $detect)
			return $string;
		else
			return iconv($detect, $encoding, $string);
	}

}
