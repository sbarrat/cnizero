<?php
session_start();
require_once 'variables.php';
if ( isset( $_SESSION['usuario'] ) ) {
    $conexion = new Sql();
	$tabla = $conexion->escape( $_POST['tabla'] );
	$registro = $conexion->escape( $_POST['registro'] );
	$sql = "Select * from `" . $tabla . "` where id like " . $registro;
	$conexion->consulta( $sql );
	$resultado = $conexion->dato();
	print_r( $resultado );
	?>
	<form id='formulario_actualizacion' action='#' method='post' 
	onSubmit='actualiza_registro(); return false'>
	<input type='hidden' id='opcion' value='0' />
	<input type='hidden' id='idemp' value='<?php echo $resultado[0] ?>' />
	<table cellpadding='0px' cellspacing='1px' class='formulario'>
	<?php 
	//cabezera nombre de empresa, desvio y activo y menu
	$desvio = "";
	if ( $tabla == "clientes" ) {
	    $desvio = desvioActivo(
		        $resultado['desvio'],
		        $resultado['Estado_de_cliente'],
		        $resultado['extranet'],
		        $registro
		    );
	}
	$colorCabezera = colorCabezera( $tabla, $resultado );
	?>
		<tr>
		<th height='24px' bgcolor='<?php echo $colorCabezera; ?>' 
		color='#fff' align='left' width='100px'>
			<div id='edicion_actividad'>
			</div>
		<?php echo $desvio ?>
		</th>
		<th height='24px' align='left' bgcolor='<?php echo $colorCabezera; ?>' 
		colspan='2'>
			<font size='4'>
			<?php echo Aux::traduce( $resultado['Nombre'] )." " . 
			    codigoNegocio( $resultado['Id'] ); 
			?>
			</font>
			<input type='hidden' name='nombre_tabla' id='nombre_tabla' 
			value='<?php echo $tabla ?>' />
			<input type='hidden' name='numero_registro' id='numero_registro' 
			value='<?php echo $resultado[0] ?>' />
		</th>
		<th align='right' bgcolor='<?php echo $colorCabezera ?>'>
			<input class='boton' onclick='cierra_el_formulario()' value='[X] Cerrar' >
		</th>
	</tr>
	<?php
	//submenus
	$cadena .= submenus($vars);
	//Fin de los submenus
	//campo oculto con nombre de tabla
	for($i=1;$i<=$numero_campos-1;$i++)//si empiezo desde 1 me salto el id, pero no el idepm
	{
		if($j%2==0)
		$cadena .= "</tr><tr>";
		$j++;
		$cadena .= "<th align='left' valign='top' class='nombre_campo'>".traduce(nombre_campo(mysql_field_name($consulta,$i),$vars[tabla])) ."</th><td align='left' valign='top' class='valor_campo'>".tipo_campo(mysql_field_name($consulta,$i),$vars[tabla],$resultado[$i],'actualiza',$i) ."</td>";
	}
	$cadena .= "</tr>";
	//parte de la botoneria ya empezamos con los casos particulars
	//o actualizo o creo
	if(isset($vars[principal])) //de momento indicativo de subformulario
	{
		$cadena .= "<tr><th colspan='4' align='center'><input class='boton' type='submit'  value='[+] Agregar' />";
		$cadena .= "<input class='boton' type='reset'  value='[L] Limpiar formulario' /></th></tr>";
	}
	else
	{
		$cadena .= "<tr><th colspan='4' align='center'><p/><input class='boton' type='submit' value='[*]Actualizar Datos' tabindex='".$numero_campos."'/>";
		$cadena .= "<input type='button' class='boton' onclick='borrar_registro(".$resultado[0].")' value='[X]Borrar Datos' tabindex='".$numero_campos."'/></th></tr>";
	}
	$cadena .= "</table></form>";
	echo $cadena;
}

function desvioActivo($desvio,$estado,$extranet,$cliente)
{
	
	if($estado == 0) //Cliente activo o no
		$cadena = "<img src='imagenes/noactivo.gif' alt='Cliente Inactivo' width='24px'/>";
	else
		$cadena = "<img src='imagenes/activo.gif' alt='Cliente Activo' width='24px'/>";
		
	if($desvio == 0) //Desvio activo o no
		$cadena .= "<img src='imagenes/desvioi.gif' alt='Desvio Inactivo' width='24px'/>";
	else
		$cadena .= "<spam class='popup' onclick='ver_detalles(0,0,0,".$cliente.")'><img src='imagenes/nudesvioa.gif' alt='Desvio Activo' width='24px' /></spam>";
		
	if($extranet == 0)//Extranet activa o inactiva
		$cadena .= "<img src='imagenes/extraneti.gif' alt='Extranet Inactivo' width='24px'/>";
	else
		$cadena .= "<spam class='popup' onclick='ver_detalles(0,0,1,".$cliente.")'><img src='imagenes/extraneta.gif' alt='Extranet Activa' width='24px' /></spam>";
	return $cadena;	
}
function colorCabezera($tabla,$vars)
{
	switch($tabla)
	{
		case "clientes":switch(true)
							{
								case(ereg("despacho",$vars['Categoria'])):$color="#69C";break;
								case(ereg("domicili",$vars['Categoria'])):$color="#F90";break;
								default:$color="#7d0063";break;
							};break;
		default:$color="#7d0063";break;
	}
	return $color;
}
/**
 * Devuelve el codigo de negocio
 * 
 * @param integer $idemp
 */
function codigoNegocio( $idemp ) 
{
	$conexion = new Sql();
	if ( isset( $idemp ) && $idemp!= null ) {
        $sql = "Select * from z_sercont where idemp like $idemp and servicio like 'Codigo Negocio'";
	    $conexion->consulta( $sql );
	    if ( $conexion->totalDatos() >= 1 ) {
	        $resultado = $conexion->dato();
	        return "<font size='6'>".$resultado['valor']."</font>";
	    } else {
	        return "";
	    }
	}
}