<?php
/**
 * Validacion File Doc Comment
 * 
 * Fichero encargado de la validacion de usuarios
 * 
 * PHP Version 5.1.4
 * 
 * @category Validacion
 * @package  cni/inc
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cnizero
 */ 
session_start();
require_once 'variables.php';
if ( isset( $_POST['usuario'] ) && isset( $_POST['password'] ) ) {
    $conexion = new Sql();
	$contra = sha1( $conexion->escape( $_POST['password'] ) );
	$usuario = $conexion->escape( $_POST['usuario'] ); 
	$sql = "Select nick,contra from usuarios where nick 
	like '".$usuario."' and contra like '$contra'";
	$conexion->consulta( $sql );
	if ( $conexion->totalDatos() != 1 ) {
	    header( "Location:../index.php?error=1" );
	    exit;
	} else {
	    $_SESSION['usuario'] = $usuario;
	    header( "Location:../index.php" );
	    exit;
	}
} else {
    header( "Location:../index.php?error=1" );
    exit; 
}