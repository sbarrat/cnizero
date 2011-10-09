<?php
require_once 'Sql.php';
require_once 'Aux.php';
class Document extends Sql
{
 
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Muestra el menu
	 */
	function menu()
	{
		parent::consulta( "SELECT * FROM menus" );
		$html = "<table width='100%'><tr>";
		$opciones = array('7'=>1, '8'=>2, '9'=>3);
		
		foreach ( parent::datos() as $datos ) {
            if ( array_key_exists( $datos['id'], $opciones ) ) {
                $accion = "datos(" . $opciones[$datos['id']] . ")";
            } else {
                $accion = "menu(" . $datos['id'] . ")";
            }
            $html .= "
            <th>
            	<a href='javascript:" . $accion . "'>
            		<img src='".$datos['imagen']."' alt='".$datos['nombre']."' width='32'/>
				<p />".$datos['nombre']."
				</a>
			</th>";
		}
	    $html .="
	    	<th>
	    		<a href='inc/logout.php'>
	    		<img src='imagenes/salir.png' width='32' alt='Salir'>
	    		<p/>Salir<a>
	    	</th>
			</tr>
			</table>
			<div id='principal'></div>";
	    return $html;
	}
	
	function mes( $mes ) 
	{
		return Aux::$meses[$mes];
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