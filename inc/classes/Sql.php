<?php

class Sql {
	private $_host = "localhost";
	private $_user = "cni";
	private $_password =  "inc"; 
	private $_dbname = "centro";
	private $_conexion = null;
	private $_result = null;
	
	
	function __construct() {
		$this->_conexion = mysql_connect($this->_host,$this->_user,$this->_password);
		if(!$this->_conexion){
			die("Database connection failed: ". mysql_error());
		}
		if(!mysql_select_db($this->_dbname,$this->_conexion))
			die("Database selection failed: ".mysql_error());
	}
	
	function consulta($sql){
		$this->_result = mysql_query($sql,$this->_conexion);
	}
	
	function datos(){
		$rows="";
		while( $row = mysql_fetch_array( $this->_result, MYSQL_BOTH ) ) {
			$rows[] = $row;
		}
		return $rows;
	}
	function dato() {
	    return mysql_fetch_row( $this->_result, MYSQL_BOTH );
	}
	
	function totalDatos(){
		return mysql_affected_rows();
	}
	
	function close(){
		mysql_close($this->_conexion);
	}
	
}