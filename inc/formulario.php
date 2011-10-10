<?php
/**
 * Formulario File Doc Comment
 * 
 * Muestra el formulario segun la opcion que hemos marcado
 * 
 * PHP Version 5.1.4
 * 
 * @category Formulario
 * @package  cni/inc
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cnizero
 */
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
        <th height='24px' bgcolor='<?php echo $formulario->colorCabezera(); ?>' 
         align='left' width='100px'>
            <div id='edicion_actividad'>
            </div>
        <?php echo $formulario->desvioActivo(); ?>
        </th>
        <th height='24px' align='left' 
            bgcolor='<?php echo $formulario->colorCabezera(); ?>' colspan='2'>
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
        <th align='right' bgcolor='<?php echo $formulario->colorCabezera(); ?>'>
            <input class='boton' 
            onclick='cierra_el_formulario()' value='[X] Cerrar' >
        </th>
    </tr>
    <tr>
        <th colspan='4' width='100%'height='26px'>
        <table>
            <tr>
    <?php 
    foreach ( $formulario->submenus() as $submenu ) {
        if($submenu['nombre'] == "Principal") {
            $opcion = "muestra(" . $formulario->getCodigo() . ")";  
        } else {
            $opcion = "submenu(" . $submenu['id'] . ")"; 
        }
        ?>
		      <th>
		          <span class='boton' 
		    		onclick='<?php echo $opcion; ?>' > 
		            <?php echo Aux::traduce( $submenu['nombre'] ); ?>
		          </span>
		      </th>
	<?php } ?>
	       </tr>
       </table>
       </th>
    </tr>
    <tr>
    <?php
    $j = 0; 
    foreach ( $formulario->verCampos() as $campo ) {
        echo ( $j%2 == 0) ? "</tr><tr>":"";
        $j++;
         ?>   
        <th align='left' valign='top' class='nombre_campo'>
            <?php echo Aux::traduce( $campo['campof'] ); ?>
        </th>
        <td align='left' valign='top' class='valor_campo'>
            <?php echo $formulario->tipoCampo( $campo ); ?>
        </td>
    <?php 
    }
    ?>
    </tr>
    <?php 
    if ( $formulario->getRegistro() ) {
        ?>
        <tr>
    	   <th colspan='4' align='center'>
    	       <input class='boton' type='submit'  value='[+] Agregar' />
    		   <input class='boton' type='reset'  value='[L] Limpiar formulario' />
    	   </th>
        </tr>
        <?php
    } else {
        ?>
        <tr>
            <th colspan='4' align='center'>
            <p/>
                <input class='boton' type='submit' value='[*]Actualizar Datos' />
                <input type='button' class='boton' 
            	   onclick='borrar_registro(<?php echo $formulario->getRegistro(); ?>)' 
                   value='[X]Borrar Datos' />
            </th>
        </tr>
    <?php 
    }
    ?>
    </table>
    </form>
    <script>
    $('.fecha').datepicker({
    	firstDay: 1,
    	changeYear: true,
        changeMonth: true,
        dateFormat: 'dd-mm-yy',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
    });
    </script>
    <?php
    //var_dump( $formulario->verDatos() );
    //var_dump( $formulario->verCampos() );
} 
