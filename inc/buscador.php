<?php
/**
 * Buscador File Doc Comment
 * 
 * Devuelve los resultados de buscador generico
 * 
 * PHP Version 5.1.4
 * 
 * @category Buscador
 * @package  cni/inc
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cnizero
 */
session_start();
require_once 'variables.php';
if ( isset ( $_SESSION['usuario'] ) ) {
    $conexion = new Sql();
    $texto = $conexion->escape( $_REQUEST['texto'] );
    $tabla = $conexion->escape( $_REQUEST['tabla'] );
    $max = $conexion->escape( $_REQUEST['max'] );
    $sql = "SELECT id, Nombre FROM `" . $tabla . "`
    WHERE Nombre LIKE '%" . $texto . "%'
    LIMIT " . $max;
    $conexion->consulta( $sql );
    foreach ( $conexion->datos( MYSQL_ASSOC ) as $dato ) {
        $datos[] = array('id'=>$dato['id'],'label'=>$dato['Nombre']);
    }
    echo json_encode( $datos );
}

