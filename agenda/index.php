<?php
/*
 * FIXME: Si agregamos una repeticion a martes y miercoles, sale el domingo y tambien el sabado
 * FIXME: Segun como agregamos cosas a la hora de borrar no se borran del todo.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Ocupación de Despachos</title>
	<link href="estilo/agenda.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src='../js/prototype.js'></script>
	<script type="text/javascript" src="../js/calendar.js"></script>
	<script type="text/javascript" src="../js/lang/calendar-es.js"></script>
	<script type="text/javascript" src="../js/calendar-setup.js"></script>
	<script type="text/javascript" src="js/agenda.js"></script>
	<script type="text/javascript" src="../FCKeditor/fckeditor.js"></script>
</head>
<body>
	<div id='mensajes_estado'></div>
Agenda Despachos
Vista:<select id='tipo_vista' onchange='cambia_vista()'>
		<option selected value=''>--Opcion--</option>
		<option value='0'>Despachos</option>
		<option value='1'>Semana</option>
		<option value='2'>Interna</option>
		<option value='3'>Tareas Pendientes</option>
		<option value='4'>Notas</option>
	</select>
<p>&nbsp;</p>
<div id='vista'></div>
<div id='informacion_despacho'></div>
<div id='formulario_agenda'></div>
</body>
</html>
