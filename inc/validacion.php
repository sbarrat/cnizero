<? 
session_start();
//validacion.php solo se encarga de validar el usuario y iniciar la session
//parte principal de mis funciones
//require_once("variables.php");
//public include("variables.php");
switch ($_POST['usuario'])
{
	case 0: valida($_POST);break;
}
//echo $respuesta;
//fin de la parte principal
//aqui las funciones
function valida($vars)
{
	include("variables.php");
	$contra = sha1($vars['password']); 
	$sql = "Select nick,contra from usuarios where nick like '".$vars['usuario']."' and contra like '$contra'";
	$consulta = mysql_db_query($dbname,$sql,$con);
	if (mysql_numrows($consulta) != 1)
		{
			header("Location:../index.php?error=1");
			
		}
	else
		{
			$resultado = mysql_fetch_array($consulta);
			$usuariod = $resultado[0];
			$passwdd = $resultado[1];//encriptada
			if(($vars['usuario'] == $usuariod) && ($contra == $passwdd))
			{
				
				$_SESSION['usuario'] = $vars['usuario'];
				header("Location:../index.php");
				
			}
			else
				header("Location:../index.php?error=1");
				
				
		}
	
}
//generamos el menu de usuarios
function menu()
{
	include("variables.php");
	$sql = "Select * from menus";
	$consulta = mysql_db_query($dbname,$sql,$con);
	$tabla = "<table width='100%'><tr>";
	while($resultado = mysql_fetch_array($consulta))
	{
		switch ($resultado[0])
		{
		case 7:	$tabla .="<th><a href='javascript:datos(1)'>
							<img src='".$resultado[3]."' alt='".$resultado[1]."' width='32'/>
							<p />".$resultado[1]."</a></th>";break;
		case 8: $tabla .="<th><a href='javascript:datos(2)'>
							<img src='".$resultado[3]."' alt='".$resultado[1]."' width='32'/>
							<p />".$resultado[1]."</a></th>";break;
		case 9: $tabla .="<th><a href='javascript:datos(3)'>
							<img src='".$resultado[3]."' alt='".$resultado[1]."' width='32' />
							<p />".$resultado[1]."</a></th>";break;
		default:	$tabla .= "<th><a href='javascript:menu(".$resultado[0].")'>
							<img src='".$resultado[3]."' alt='".$resultado[1]."' width='32'/>
							<p/>".$resultado[1]."</a></th>";break;
		}	
	}
	$tabla .="<th><a href='inc/logout.php'><img src='imagenes/salir.png' width='32' alt='Salir'><p/>Salir<a></th>";
	$tabla .= "</tr></table><div id='principal'></div>";
	return $tabla;
	
}

?>