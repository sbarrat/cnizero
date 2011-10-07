<?php
/**
 * Index File Doc Comment
 * 
 * Fichero principal de la aplicacion
 * 
 * PHP Version 5.1.4
 * 
 * @category Index
 * @package  cni
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cnizero
 */
session_start(); 
$html="";
if ( isset( $_SESSION['usuario'] ) ) {
    include_once "inc/classes/Document.php";
    include_once "inc/classes/Avisos.php";
    $document = New Document();
    $avisos = New Avisos();
    $html = "<div id='menu_general'>";
    $html .= $document->menu();
    $html .= "</div>";
    $html .= $avisos->ver_avisos();
    $html .= "<div id='resultados'></div>"; //linea de los resultados de busqueda
    $html .= "<div id='formulario'></div>";//linea del formulario
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="estilo/cni.css" rel="stylesheet" type="text/css"></link>
<link href="estilo/custom-theme/jquery-ui-1.8.8.custom.css"
	rel="stylesheet" type="text/css"></link>

<script type="text/javascript" src='js/jquery-1.4.4.min.js'></script>
<script type="text/javascript" src="js/jquery-ui-1.8.8.custom.min.js"></script>
<script type="text/javascript" src="js/cni.js"></script>
<script type="text/javascript" src="js/independencia.js"></script>
<title>Aplicación Gestión Independencia Centro Negocios 2.0d</title>
</head>
<body>
<div id='cuerpo'>
<?php
if ( $html!="" ) {
    echo $html;
} else  {
    ?>
    <div id='registro'><img src='imagenes/logotipo2.png' width='538px'
    alt='The Perfect Place' /> <br />
	<?php
    if ( isset( $_GET["exit"] ) ) {
        echo "<span class='ok'>Sesion Cerrada</span>";
    }
    if ( isset( $_GET["error"] ) ) {
        echo "<span class='ko'>Usuario o Contraseña Incorrecta</span>";
    }
    ?>
    <form id='login_usuario' name='login_usuario'
    action='inc/validacion.php' method='post'>
        <table width='50%' class="login">
            <tr>
                <th colspan='2'>Acceso Usuarios</th>
            </tr>
	       <tr>
		        <td align='right'>Usuario:</td>
		        <td>
                    <input type='text' id="usuario" name="usuario" accesskey="u"
			         tabindex="1" />
                </td>
	       </tr>
	       <tr>
		      <td align='right'>Contraseña:</td>
		      <td>
                <input type='password' id="password" name="password" accesskey="c"
			     tabindex="2" />
              </td>
	       </tr>
	       <tr>
		      <td align='center' colspan="2"><input type='submit' class='boton'
			     accesskey="e" tabindex="3" value='[->]Entrar' /></td>
	       </tr>
	       <tr>
		      <td colspan='2'>Cambiar la contraseña de Acceso</td>
	       </tr>
        </table>
    </form>
    <br />
        <div id='footer'>
            <span class="etiqueta">Desarrollado por:</span>
            <br />
            <a href='http://www.ensenalia.com'><img src='imagenes/ensenalia.jpg'
	           width='128' />
            </a>
            <br />
            <a rel="license"
	           href="http://creativecommons.org/licenses/by-nc-nd/3.0/">
               <img alt="Licencia Creative Commons" style="border-width: 0"
	               src="http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png" />
            </a>
            <br />
            <span xmlns:dct="http://purl.org/dc/terms/"
	           href="http://purl.org/dc/dcmitype/Text" property="dct:title"
	           rel="dct:type"> Perfect Place 2.0e 
            </span> 
            por 
            <a xmlns:cc="http://creativecommons.org/ns#"
	           href="http://sbarrat.wordpress.com" property="cc:attributionName"
	           rel="cc:attributionURL">
               &copy;Rubén Lacasa::<?php echo date( 'Y' ); ?>
	        </a>
        </div>
    </div>
    <?php 
    } 
?>
</div>
<div id='datos_interesantes'></div>
<div id='debug'></div>
</body>
</html>