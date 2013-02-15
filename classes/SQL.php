<?php
include_once CLASSES."PSQLinterpreter.php";

class SQL{
	protected $_interpreter;
	
	function __construct(){
		global $config;
		switch($config['sql_type']){
			case "postgresql":
				$this->_interpreter = new PSQLinterpreter();
				break;

			default:
				break;
		}
	}

	function __destruct(){
		$this->_interpreter = NULL;
	}

	function query($query){
		return $this->_interpreter->query($query);
	}

	function getObject($table, $array){
		return $this->_interpreter->getObject($table, $array);
	}

	function saveObject($table, $object){
		return $this->_interpreter->saveObject($table, $object->toArray());
	}
}

?>
