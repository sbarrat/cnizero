<?php
session_start();
require_once 'variables.php';
if ( isset( $_SESSION['usuario'] ) ) {
    $formulario = new Formulario($_POST['tabla'], $_POST['registro']);
    ?>
    <form id='formularioDatos' action='' method='post'>
    <input type='hidden' id='opcion' value='0' />
    <input type='hidden' id='idemp' 
        value='<?php echo $formulario->getRegistro(); ?>' />
    <table cellpadding='0px' cellspacing='1px' class='formulario'>
    <tr>
        <th height='24px' bgcolor='<?php echo $formulario->colorCabezera; ?>' 
         align='left' width='100px'>
            <div id='edicion_actividad'>
            </div>
        <?php echo $formulario->desvioActivo(); ?>
        </th>
        <th height='24px' align='left' 
            bgcolor='<?php echo $formulario->colorCabezera; ?>' colspan='2'>
            <font size='4'>
            <?php echo $formulario->getNombre() . 
                " " . $formulario->getCodigo(); 
            ?>
            </font>
            <input type='hidden' name='nombre_tabla' id='nombre_tabla' 
            value='<?php echo $formulario->getTabla(); ?>' />
            <input type='hidden' name='numero_registro' id='numero_registro' 
            value='<?php echo $formulario->getCodigo(); ?>' />
        </th>
        <th align='right' bgcolor='<?php echo $colorCabezera ?>'>
            <input class='boton' 
            onclick='cierra_el_formulario()' value='[X] Cerrar' >
        </th>
    </tr>
    </table>
    </form>
    <?php
    var_dump( $formulario->verDatos() );
    var_dump( $formulario->verCampos() );
} 
    //cabezera nombre de empresa, desvio y activo y menu
    /*$desvio = "";
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
		
	<?php
    // Generamos el submenu
/*    echo submenus( $tabla, $registro );
    // Generamos el formulario
    $sql = "SELECT * from alias where tabla like '" . $tabla ."'";
    $conexion->consulta( $sql );
    $campos = $conexion->datos();
    var_dump( $campos );
    $j = 0;
    $cadena = "";
    foreach ( $campos as $campo ) {
        echo ( $j%2 == 0) ? "</tr><tr>":"";
        echo "
	    <th align='left' valign='top' class='nombre_campo'>" .
            Aux::traduce( $campo['campof'] ) . "
	    </th>
	    <td align='left' valign='top' class='valor_campo'> " .
            tipoCampo( $campo ) . "
	    </td>";   
    }
    echo "</tr>";
    if ( isset( $vars['principal'] ) )  {
        echo "
			<tr>
				<th colspan='4' align='center'>
				<input class='boton' type='submit'  value='[+] Agregar' />
		    	<input class='boton' type='reset'  value='[L] Limpiar formulario' />
		    	</th>
		    </tr>";
    } else {
        echo "
        	<tr>
        		<th colspan='4' align='center'>
        		<p/>
        		<input class='boton' type='submit' value='[*]Actualizar Datos' />
        		<input type='button' class='boton' 
        			onclick='borrar_registro(" . $resultado[0] . ")' value='[X]Borrar Datos' />
        		</th>
        		</tr>";
    }
    ?>
    </table>
    </form>
    <?php 
}
?>
<?php
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
/*function colorCabezera($tabla,$vars)
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
}*/
/**
 * Devuelve el codigo de negocio
 * 
 * @param integer $idemp
 */
function codigoNegocio( $idemp ) 
{
	global $conexion;
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
/**
 * Genera los submenus
 * 
 * @param array $vars
 */
 function submenus( $tabla, $registro )
{
	global $conexion;
	$sql = "Select s.* FROM submenus as s
	INNER JOIN menus as m
	ON m.id = s.menu
	WHERE m.pagina like '". $tabla . "'";
	$conexion->consulta($sql);
	$cadena = "<tr><th colspan='4' width='100%'height='26px'><table><tr>";
	foreach ( $conexion->datos() as $resultado ) {
		if($resultado[2] == "Principal") {
		    $opcion = "muestra(" . $registro . ")";  
		} else {
		    $opcion = "submenu(" . $resultado['0'] . ")"; 
		}
		$cadena .= "
				<th>
		    		<span class='boton' 
		    		onclick='". $opcion ."' > " .
		            Aux::traduce( $resultado['2'] ) . "
		            </span>
		        </th>";
	}
	$cadena .= "</tr></table></th></tr>";
	return $cadena;
}
/**
 * Devuelve el nombre del campo
 */
 function nombreCampo($campo,$tabla)
{
	global $conexion;
	$sql = "Select campof from alias 
	WHERE tabla like '" . $tabla . "' 
	AND `campoo` like '" . $campo . "'";
	$conexion->consulta($sql);
	return $conexion->dato();
}
/**
 * Devuelve el tipo de campo
 */
 function tipoCampo($resultado)
{
	global $conexion;
	//$sql = "Select * from alias where tabla like '$tabla' and `campoo` like '$campo'";
	//$consulta = mysql_db_query($dbname,$sql,$con);
	//$resultado = mysql_fetch_array($consulta);
	switch($resultado[tipo])
	{
		case "text": //caso rarito de z_sercont valor
		 			if (($tabla =='z_sercont') && ($resultado[campoo]=='valor'))
					$cadena ="<div id='tipo_teleco'><input type='text' size='".$resultado[size]."' id='".$resultado[variable]."' name='".$resultado[campoo]."' value='".traduce($valor)."' tabindex='".$i."' onkeyup='chequea_valor()'/></div>";
					else
					$cadena = "<input type='text' size='".$resultado[size]."' id='".$resultado[variable]."' name='".$resultado[campoo]."' value='".traduce($valor)."' tabindex='".$i."'/>";break;
		case "textarea":$cadena = "<textarea id='".$resultado[variable]."' name='".$resultado[campoo]."' rows='".$resultado[size]."' cols='46' tabindex='".$i."'>".traduce($valor)."</textarea>";break;
		case "checkbox": 	{
							if ($valor!= 0)
								$chequeado = 'checked';
							else
								$chequeado = ''; 
							$cadena = "<input  type='checkbox' id='".$resultado[variable]."' ".$chequeado." name='".$resultado[campoo]."' tabindex='".$i."'/>";
							}
							break;
		case "date": 	{
						$cadena = "<input type='text' id='".$resultado[variable]."' name='".$resultado[campoo]."' size = '".$resultado[size]."'  value='".cambiaf($valor)."' tabindex='".$i."'/>";
						$cadena .= "&nbsp;&nbsp;<button TYPE='button' class='calendario' id='f_trigger_".$resultado[variable]."' tabindex='".$i."'></button>";
						}
						break;
		case "select": 
							{//hay que hacer una consulta a la tabla dependiente de los valores
							$sql = "Select * from `$resultado[depende]` order by 2";
							$consulta = mysql_db_query($dbname,$sql,$con);
							if ($tabla =='z_sercont') //caso del z_sercont
								$cadena ="<select id='".$resultado[variable]."' name='".$resultado[campoo]."' tabindex='".$i."' onchange='muestra_campo()'>";
							else
								$cadena ="<select id='".$resultado[variable]."' name='".$resultado[campoo]."' tabindex='".$i."'>";
								$cadena .="<option value='0'>-::".traduce($resultado[campoo]).":-</option>";
							while ($resultado = mysql_fetch_array($consulta))
								{
								if (traduce($resultado[1]) == traduce($valor))
									$marcado = 'selected';
								else 
									$marcado = "";
								$cadena .= "<option ".$marcado." value='".traduce($resultado[1])."'>".traduce($resultado[1])."</option>";
								}
							$cadena .= "</select> ".traduce($valor);
							}break;					
		default: $cadena = $valor;break;
		
	}
	switch($resultado[enlace])//generamos el enlace de conexion o bien a web o envio de correo
		{
			case "web":$cadena .="<a href='http://".$valor."' target='_blank'><img src='iconos/package_network.png' width='14' alt='Abrir Web'/></a>";break;
			case "mail":$cadena .="<a href='mailto:".$valor."'><img src='iconos/mail_generic.png' width='14' alt='Enviar Correo'/></a>";break;
		}
	return $cadena;
}