<?php

/**
 *
 */
class ClassName extends AnotherClass {

	function __construct($argument) {

	}

	//// -----------------------------------
	private $tables = array(
	'path' => 'CREATE TABLE IF NOT EXISTS `z_{id}__url` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) COLLATE cp1251_bin NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bin;', 

'page' => 'CREATE TABLE IF NOT EXISTS `z_{id}__doc` (
  `url_id` int(10) unsigned NOT NULL,
  `state` tinyint(3) unsigned NOT NULL,
  `http` int(3) unsigned NOT NULL,
  PRIMARY KEY (`url_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bin;');

	private $dtables = array('path' => 'DROP TABLE IF EXISTS `z_{id}__url` ;', 'page' => 'DROP TABLE IF EXISTS `z_{id}__doc` ;');

	/***
	 * Функция тестирует есть ли в базе таблицы
	 * сканирования и их кол-во совпадает
	 *  */
	function test_tables($id) {
		$db = new db($opt);
		$sql = 'SHOW TABLES LIKE "z_' . $id . '_%" ';
		$cntables = $db -> qList($sql);
		if (count($cntables) == count($this -> tables)) {
			return true;
		} else {
			return false;
		}
	}

	/***
	 * Используя шаблон
	 */
	private function _get_tables($id) {
		if ($id > 0) {
			$tables = array();
			foreach ($this->tables as $key => $value) {
				$tables[] = str_replace('{id}', $id, $value);
			}
			return $tables;
		}
		return false;
	}

	private function _get_tables_drop($id) {
		if ($id > 0) {
			$tables = array();
			foreach ($this->dtables as $key => $value) {
				$tables[] = str_replace('{id}', $id, $value);
			}
			return $tables;
		}
		return false;
	}

	public function drop_tables($value = '') {
		$this -> db = new db();
		$mngr = manager::get();
		foreach ($this->_get_tables_drop($mngr->task['domain']) as $key => $value) {
			$this -> db -> q($value);
		}
	}

}
