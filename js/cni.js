/**
 * Nuevo Fichero Funciones Javascript basado jQuery
 */
function avisos(){
	$('#avisos').toggle();
}
function menu(pagina){
	$.post("inc/generator.php",{opcion:"0",codigo:pagina},function(data){
		$('#principal').html(data);
	});
}/*Opciones de Gestion*/
function gestion(opcion){
	alert(opcion);
}
	
