<?php 
/**
 * Variables File Doc Comment
 * 
 * Fichero que estable el path donde estan las clases y autocarga las clases
 * 
 * PHP Version 5.1.4
 * 
 * @category Variables
 * @package  cni/inc
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cnizero
 */

/**
 * Establecemos el path donde estan las clases
 */
set_include_path( get_include_path() . PATH_SEPARATOR . __DIR__.'/classes' );
/**
 * Carga Automatica de Clases
 * 
 * @param string $className
 */
function __autoload( $className ) 
{ 
    include_once  $className . '.php';
}
?>
