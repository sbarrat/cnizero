<?php
include_once "inc/classes/Sql.php";
class Document extends Sql{
	private $_mes; 
	function __construct() {
		parent::__construct();
		$this->_mes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	}
	
	function menu(){
		
		parent::consulta("SELECT * FROM menus");
		$html = "<table width='100%'><tr>";
		foreach(parent::datos() as $datos){
			switch($datos['id']){
				case 7:	$html .="<th><a href='javascript:datos(1)'>
							<img src='".$datos['imagen']."' alt='".$datos['nombre']."' width='32'/>
							<p />".$datos['nombre']."</a></th>";break;
				case 8: $html .="<th><a href='javascript:datos(2)'>
							<img src='".$datos['imagen']."' alt='".$datos['nombre']."' width='32'/>
							<p />".$datos['nombre']."</a></th>";break;
				case 9: $html .="<th><a href='javascript:datos(3)'>
							<img src='".$datos['imagen']."' alt='".$datos['nombre']."' width='32' />
							<p />".$datos['imagen']."</a></th>";break;
				default:$html .= "<th><a href='javascript:menu(".$datos['id'].")'>
							<img src='".$datos['imagen']."' alt='".$datos['nombre']."' width='32'/>
							<p/>".$datos['nombre']."</a></th>";break;
			}
		}
		
	$html .="<th><a href='inc/logout.php'><img src='imagenes/salir.png' width='32' alt='Salir'><p/>Salir<a></th>";
	$html .= "</tr></table><div id='principal'></div>";
	return $html;
	}
	
	function mes($mes){
		return $this->_mes[$mes];
	}
	function nombre_cliente($id){
		if($id==0)
		$nombre = "CENTRO";
		else
		{
			$sql = sprintf("Select Nombre from clientes where id like %s",$id);
			parent::consulta($sql);
			$datos = parent::datos();
			if(is_array($datos) && count($datos)==1)
				$nombre = strtoupper(utf8_encode($datos[0]['Nombre']));
			else
				$nombre = "";
		}
		return $nombre;
	}
	
	
}