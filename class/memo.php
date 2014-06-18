<?php

/**
 *
 */
class memo extends config {

	protected static $_instance;
	protected static $cache;

	private function __construct() {
		$this -> cache = new Memcache();
		$this -> cache -> connect($this -> memo[0]['h'], $this -> memo[0]['p'], 30);
	}

	public static function get() {
		if (self::$_instance === null) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	private function __clone() {
	}

	private function __wakeup() {
	}

	public function g($type, $prefix = '', $ind) {
		$val = $this -> cache -> get($type . ':' . (($prefix) ? $prefix . ':' : '') . $ind);
		if (!$val) {
			return false;
		} else {
			return $val;
		}
	}

	public function set($type, $prefix = '', $ind, $value) {
		$this -> cache -> set($type . ':' . (($prefix) ? $prefix . ':' : '') . $ind, $value, MEMCACHE_COMPRESSED, 500);
	}

}
