<?php

class Sql {
	
	private $_conexion = null;
	private $_result = null;
	
	
	function __construct() {
	    $this->_host = "127.0.0.1:3306";
	    $this->_user = "cni";
	    $this->_password = "inc";
	    $this->_dbname = "centro";
		$this->_conexion = mysql_pconnect( $this->_host, $this->_user, $this->_password );
		if(!$this->_conexion){
			die("Database connection failed: ". mysql_error());
		}
		if(!mysql_select_db($this->_dbname,$this->_conexion))
			die("Database selection failed: ".mysql_error());
	}
	
	function consulta($sql){
		$this->_result = mysql_query($sql,$this->_conexion);
	}
	
	function datos( $tipo = MYSQL_BOTH ){
		$rows="";
		while( $row = mysql_fetch_array( $this->_result, $tipo ) ) {
			$rows[] = $row;
		}
		return $rows;
	}
	function dato( $tipo = MYSQL_BOTH ) {
	    return mysql_fetch_row( $this->_result, $tipo );
	}
	
	function totalDatos(){
		return mysql_affected_rows();
	}
	
	function close(){
		mysql_close($this->_conexion);
	}
	function escape( $var )
	{
	    return mysql_real_escape_string( $var, $this->_conexion );
	}
	
}