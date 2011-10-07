<?php
/*
 * Clase que se encargara de gestionar las acciones del apartado de gestion
 * Cosicas que tiene que hacer: 
 * TODO: Gestion de Base de datos: Hacer, Listar y Borrar Copias
 * TODO: Datos Categorias: Listado Categorias Servicios, Categorias Clientes 
 */
include_once $_SERVER['DOCUMENT_ROOT']."/cni/inc/classes/Sql.php";

class Gestion extends Sql{
	
	function __construct() {
		parent::__construct();
	}
	function verMenu(){
		$html = "<div class='gestion_app'>";
		$html.= "<div class='ui-widget-header'>Menu de Gesti&oacute;n</div>";
		$html .="<span class='boton gestion' id='basedatos'>Base de Datos</span>";
		$html .="<span class='boton gestion' id='categorias'>Categorias</span>";
		$html .="<span class='boton gestion' id='telefonos'>Telefonos</span>";
		$html .="<span class='boton gestion' id='listados'>Listados</span>";
		$html .="<span class='boton gestion' id='usuarios'>Usuarios</span>";
		$html .="</div>";
		$html .="<div class='gestion_op'></div>";
		$html .="<div class='gestion_results'></div>";
		$html .="<script>";
		$html .="$('.gestion').click(function(){gestion(this.id)});";
		return $html;
	}
}
/*
 * $tabla .= "
		<div class='gestion_app'>
		Gesti&oacute;n de Base de Datos:
		<span class='boton' onclick='hacer_backup()'>&nbsp;&nbsp;[H]Hacer copia&nbsp;&nbsp;</span>
		<span class='boton' onclick='lista_backup()'>&nbsp;&nbsp;[L]Listado de Copias realizadas&nbsp;&nbsp;</span>";
		/*<span class='boton' onclick='revisar_tablas()'>&nbsp;&nbsp;[V]Revisar Tablas&nbsp;&nbsp;</span>
		<span class='boton' onclick='reparar_tablas()'>&nbsp;&nbsp;[R]Reparar Tablas&nbsp;&nbsp;</span>
		<span class='boton' onclick='optimizar_tablas()'>&nbsp;&nbsp;[O]Optimizar Tablas&nbsp;&nbsp;</span>*/
/*		$tabla.="</div>
		<div class='gestion_app'>
		Datos Categorias:
		<span class='boton' onclick='categorias(1)'>Categorias Servicios</span>
		<span class='boton' onclick='categorias(2)'>Categorias Clientes</span>
		</div>
		
		<div class='gestion_app'>
		Telefonos Centro: 
		<span class='boton' onclick='formulario_telefonos()'>&nbsp;&nbsp;Gestion Telefonos Centro &nbsp;&nbsp;</span>
		</div>
		<div class='gestion_app'>
		Listado Despachos y Domiciliados:
		<input type='button' class='boton' onclick='consulta_especial()' value='Ver Listado Completo' />
		<p><label>Ver listado filtrado de:</label>
		".listado_categorias()."
		</p>
		</div>
		<div id='listado_copias'></div>
		<div id='estado_copia'></div>
		<div id='status_tablas'></div>
		</center>";
 */
?>