<?php

abstract class model
{

    abstract protected $tbl;
    abstract protected $pk = 'id';

    //list all collums
    abstract protected $coll = array();
	abstract protected $coll_format = array();
	
	private $_values = array();
    
    function __construct($opt)
    {
        if ($opt['pk']) {
            $this->findByPk($opt['pk']);
        }

    }

    function findByPk($id)
    {
        $sql = 'SELECT * FROM ' . $this->tbl . ' WHERE `' . $this->pk . '`=' . $id;
        $val = db::App()->qRow($sql);
        foreach ($val AS $key => $val) {
            if ($this->tbl_map($key))
                $this->{$key} = $val;
        }
    }

    function save()
    {
        $set = $this->__bild_set();
        if (!$set) {
            return false;
        }
        $sql = 'INSERT INTO ' . $this->tbl . ' SET ' . $set;
        $id = db::App()->q($sql);
        if ($id) {
            $this->findByPk($id);
            return $id;
        } else {

        }
    }

    function update()
    {
        $set = $this->__bild_set();
        if (!$set) {
            return false;
        }
        $sql = 'UPDATE ' . $this->tbl . ' SET ' . $set . ' WHERE `' . $this->pk . '`=' . $this->id;
        db::App()->q($sql);
    }

    function delete()
    {
        $sql = 'DELETE FROM ' . $this->tbl . ' WHERE `' . $this->pk . '`=' . $this->id;
        $val = db::App()->q($sql);
    }

    function __bild_set()
    {

        $array = array();
        foreach ($this->coll AS $col) {
            if ($col != $this->pk) {
                $array[] = '`' . $col . '`="' . db::App()->slash($this->{$col}) . '"';
            }
        }

        return implode(',', $array);
    }

    function tbl_map($key)
    {
        return in_array($key, $this->coll);
    }

	public function __get($name='')
	{
		 if ($this->tbl_map($name)){
		 	return $this->_values[$name];
		 }
	}
	public function __set($name ,$value='')
	{
		 if ($this->tbl_map($name)){
		 	if(method_exists('setField_'.$name)){
		 		$this->_values[$name] = $this->{'setField_'.$name}($value);
		 		return $this->_values[$name];
		 	}
		 	return $this->_values[$name] = $value;
		 }
	}
	
	/**
	 * Универсальный метод получения ИД из id=>val таблиц 
	 */
	private function _getID($table, $collum, $idcollum, $value, $strlen = 0) {
		if (strlen($value) > $strlen) {
			return false;
		}

		$value_orig = $value;
		if ($strlen > 0) {
			$value = substr($value, 0, $strlen);

		}

		/* пре исключения для таблиц */
		if (method_exists($this, '_' . $table . '_prefix')) {
			$merthod = '_' . $table . '_prefix';
			$this -> {$merthod}(&$value);
		}

		$cache = $this -> get_cache_getID($table, $value);
		if (!empty($cache)) {
			return $cache;
		}

		$sql = 'SELECT `' . $idcollum . '` AS id FROM `' . $table . '` WHERE  `' . $collum . '` = \'' . $this -> db -> slash($value) . '\' LIMIT 1';
		$row = $this -> db -> qRow($sql);
		if (!empty($row)) {

		} else {
			$sql = 'INSERT INTO `' . $table . '` (`' . $collum . '`) VALUES ("' . $this -> db -> slash($value) . '")';
			$row = $this -> db -> q($sql);
			$row = array('id' => $row);
		}

		$this -> set_cache_getID($table, $value, $row);

		/* исключения для таблиц */
		if (method_exists($this, '_' . $table . '_fix')) {
			$merthod = '_' . $table . '_fix';
			$this -> {$merthod}($value_orig, $row);
		}

		return $row;
	}



}

