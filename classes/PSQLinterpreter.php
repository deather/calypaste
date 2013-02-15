<?php

class PSQLinterpreter{
	protected $_db;
	
	function __construct(){
		global $config;

		$this->_db = pg_connect("dbname=".$config['sql_db']." host=".$config['sql_host']." user=".$config['sql_user']." password=".$config['password']);
	}

	function __destruct(){
		pg_close($this->_db);
	}

	function getObject($object, $array){
		$array_sql = $this->array_to_where_sql($array);
		return pg_fetch_array($this->query("SELECT * FROM ".pg_escape_identifier($object)." where ".$array_sql[0], $array_sql[1]), NULL, PGSQL_ASSOC);
	}
	
	function saveObject($table, $array){
		
		if(!isset($array['id']) || !$this->objectExists($table, array('id'=>$array['id']))){
			$array_sql = $this->array_to_insert_sql($array);
			$this->query("INSERT INTO ".pg_escape_identifier($table)." ".$array_sql[0], $array_sql[1]);
		}
		else{
			$array_sql = $this->array_to_update_sql($array);
			array_push($array_sql[1], $array['id']);
			$this->query("UPDATE ".pg_escape_identifier($table).$array_sql[0]." WHERE id=\$".count($array_sql[1]), $array_sql[1]);
		}

		return $this->getObject($table, $array)['id'];
	}

	function objectExists($table, $array){
		$tmp = $this->getObject($table, $array);
		return !empty($tmp);
	}

	function query($query, $params){
		return pg_query_params($this->_db, $query, $params);
	}

	function escape($str){
		return pg_escape_string($this->_db, $str);
	}

	private function array_to_where_sql($array){
		if( !is_array($array) || empty($array) ){
			return "";
		}

		$tmp = array();
		$tmp_params = array();
		$i=1;
		foreach($array as $key => $value){
			if(!empty($value) && isset($value)){
				$tmp[$key] = " ".pg_escape_identifier($this->_db, $key)."=\$$i";
				array_push($tmp_params, $value);
				$i++;
			}
		}

		return array(implode(" and ", $tmp), $tmp_params);
	}

	private function array_to_insert_sql($array){
		$request_fields = array();
		$request_values = array(); 
		$params_array = array();
		$i=1;

		foreach($array as $key => $value){
			if($key != 'id'){
				array_push($request_fields, "\"$key\"");
				array_push($request_values, "\$$i");
				array_push($params_array, $value);
				$i++;
			}
		}

		return array(
			"(".implode(",", $request_fields).") VALUES (".implode(",", $request_values).")",
			$params_array
		);
	}

	private function array_to_update_sql($array){
		$i=1;
		$str = array();
		$tmp_params = array();

		foreach($array as $key => $value){
			if($key != 'id'){
				array_push($str, pg_escape_identifier($this->_db, $key)."=\$$i");
				array_push($tmp_params, $value);
				$i++;
			}
		}

		return array(
			" SET ".implode(",", $str),
			$tmp_params
		);
	}
}

?>
