/**
 * Nuevo Fichero Funciones Javascript basado jQuery
 */
/**
 * Muestra u oculta los avisos
 */
function avisos(){
	$('#avisos').toggle();
}
/**
 * Muestra el menu dependiendo de la seccion que queramos ver
 * 
 * @param string pagina
 */
function menu( pagina ) {
	$.post("inc/generator.php",{opcion:"0",codigo:pagina},function(data){
		$('#principal').html(data);
	});
}
/**
 * Busca el texto y muestra los resultados
 */
function busca()
{
	$("#texto").autocomplete({
		source: function( request, response ) {
			$.getJSON("inc/buscador.php",{
				tabla: $("#tabla").val(),
				max: 10,
				texto: request.term	
			}, response );},	
		minLength: 2,
		maxRows: 10,
		select:function( event, ui ) {
			$.post('inc/formulario.php',{
				registro:ui.item.id,
				tabla:$("#tabla").val()
			},
			function( data ){
				$("#formulario").html(data);
			}
			);
		}
	});	
}
/**
 * Limpia el formulario de busqueda
 */
function limpiarBusqueda()
{
	$("#texto").val("");
	$("#nuevo").val("");
	$("#texto").focus();
}

/*Opciones de Gestion*/
function gestion(opcion){
	alert(opcion);
}
	
