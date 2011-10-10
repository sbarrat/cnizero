<?php
class Formulario extends Sql
{
    private $_tabla = false;
    private $_registro = false;
    private $_conexion = null;
    private $_formulario = null;
    private $_datos = null;
    private $_campos = null;
    /**
     * Constructor del formulario
     * 
     * @param string $tabla
     * @param integer $registro
     */
    function __construct ( $tabla, $registro )
    {
        parent::__construct();
        $this->_tabla = parent::escape( $tabla );
        $this->_registro = parent::escape( $registro );
        $this->_cargaDatos();
        $this->_cargaCampos();
        
    }
    /**
     * Carga los datos en la variable datos
     */
    private function _cargaDatos()
    {
        if ( $this->_registro ) {
            $sql = "SELECT * FROM `" . $this->_tabla . "` 
            WHERE id like " . $this->_registro;
            parent::consulta( $sql );
            $this->_datos = parent::dato();
        }
    }
    /**
     * Carga los campos del formulario
     */
    private function _cargaCampos()
    {
        if ( $this->_tabla ) {
            $sql = "SELECT * FROM alias
            WHERE tabla LIKE '" . $this->_tabla . "'
            AND mostrar LIKE 'si'
            order by 'orden'";
            parent::consulta( $sql );
            $this->_campos = parent::datos();            
        }
    }
    /**
     * Muestra los datos
     */
    public function verDatos() 
    {
        
        return $this->_datos;
    }
    /**
     * Muestra los campos
     */
    public function verCampos()
    {
        
        return $this->_campos;
    }
    /**
     * Devuelve el nombre de encabezado de la tabla
     */
    public function getNombre()
    {
        return Aux::traduce( $this->_datos['Nombre'] );
    }
    /**
     * Devuelve el codigo de encabezado de la tabla
     */
    public function getCodigo()
    {
        return $this->_datos['Id'];
    }
    /**
     * Devuelve el nombre de la tabla
     */
    public function getTabla()
    {
        return $this->_tabla;
    }
    /**
     * Devuelve el numero de registro
     */
    public function getRegistro()
    {
        return $this->_registro;
    }
    /**
     * Establece el color de la cabezera del formulario
     */
    public function colorCabezera()
    {
        $color = "#7d0063";
        if ( $this->_tabla == 'clientes' ){
            if ( preg_match( "/despacho/", $this->_datos['Categoria'] ) ) {
                $color = "#6699CC";
            }
            if ( preg_match( "/domicili/", $this->_datos['Categoria'] ) ) {
                $color = "#FF9900";
            }
        }
        return $color;
    }
    /**
     * Mostramos si hay o no desvio activo
     */
    public function desvioActivo()
    {
        $activo = array(
            '0' => array( 'imagen'=>'noactivo.gif', 'texto'=>'Cliente Inactivo'),
            '-1' => array( 'imagen'=>'activo.gif', 'texto'=>'Cliente Activo')
            );
        $desvio = array(
           '0' => array( 'imagen' => 'desvioi.gif', 'texto'=>'Desvio Inactivo'),
           '-1' => array( 'imagen' => 'nudesvioa.gif', 'texto'=>'Desvio Activo')
        );
        $extranet = array(
           '0' => array( 'imagen' => 'extraneti.gif', 'texto'=>'Extranet Inactivo'),
           '-1' => array( 'imagen' => 'extraneta.gif', 'texto'=>'Extranet Activa')
        );     
        $cadena = "";
        if ( $this->_tabla == 'clientes' ) {
            $cadena 
                = "<img src='imagenes/".$activo[$this->_datos['Estado_de_cliente']]['imagen']."'
            	alt='".$activo[$this->_datos['Estado_de_cliente']]['texto'] ."' width='24px' />
            	<img src='imagenes/".$desvio[$this->_datos['desvio']]['imagen'] ."'
				alt='".$desvio[$this->_datos['desvio']]['texto']."' width='24px' />
				<img src='imagenes/".$extranet[$this->_datos['extranet']]['imagen']."'
				alt='".$desvio[$this->_datos['extranet']]['texto']."' width='24px' />";   	
        }
        return $cadena;	
    }
    /**
     * Devuelve los submenus de la seccion
     */
    public function submenus()
    {
        $sql = "Select s.* FROM submenus as s
		INNER JOIN menus as m
		ON m.id = s.menu
		WHERE m.pagina like '". $this->_tabla . "'";
        parent::consulta( $sql );
        return parent::datos();
    }
    /**
   	 * Genera el tipo de campo
   	 * 
   	 * @param array $campo
     */
    public function tipoCampo( $campo )
    {
        $valor = Aux::traduce( $this->_datos[$campo['campoo']] );
        switch($campo['tipo'])
        {
            case "text": //caso rarito de z_sercont valor
               
                if ( ($this->_tabla =='z_sercont') 
                    && ( $campo['campoo']=='valor' ) ) {
                    $cadena ="
					<div id='tipo_teleco'>
					<input type='text' 
						size='".$campo['size']."' 
						id='".$campo['variable']."' 
						name='".$campo['campoo']."' 
						value='". $valor ."'  
						onkeyup='chequea_valor()'/>
					</div>";
                } else {
                    $cadena = "
					<input type='text' 
						size='".$campo['size']."' 
						id='".$campo['variable']."' 
						name='".$campo['campoo']."'
						value='" .$valor ."' />";
                }
            break;
            case "textarea":
                $cadena = "<textarea id='".$campo['variable']."' 
                	name='".$campo['campoo']."' 
                	rows='".$campo['size']."' cols='46'>" . 
                    $valor . "
                    </textarea>";
            break;
            case "checkbox":
                $chequeado = ($valor != 0) ? "checked":"";
                $cadena = "
					<input  type='checkbox' 
					id='".$campo['variable']."' " . $chequeado . " 
					name='".$campo['campoo']."' />";
            break;
            case "date":
                $valor = Aux::fechaNormal( $this->_datos[$campo['campoo']] );
                $cadena = "<input type='text' class='fecha' 
					id='" . $campo['variable'] . "' 
					name='" . $campo['campoo'] . "' 
					size = '" . $campo['size'] . "'  
					value='" . $valor . "'/>";
            break;
            case "select": 
                $sql = "Select * from `" . $campo['depende'] . "` order by 2";
                parent::consulta( $sql );
                $accion = ($this->_tabla =='z_sercont') ? "onchange='muestraCampo()'":"";
                $cadena ="<select 
					id='".$campo['variable']."' 
				    name='".$campo['campoo']."'  
				    ". $accion .">";
                $cadena .="
				<option value='0'>-::" . Aux::traduce( $campo['campoo'] ) . ":-
				</option>";
                foreach( parent::datos() as $resultado ) {
                    $marcado = ( Aux::traduce( $resultado[1] ) == $valor ) ? "selected":"";
                    $cadena .= "<option ".$marcado." 
                    	value='".Aux::traduce( $resultado[1] )."'>" . 
                            Aux::traduce( $resultado[1] )."
                        </option>";
                }
                $cadena .= "</select> ". $valor;
            break;
            default: $cadena = $valor;
            break;
    }
        switch( $campo['enlace'] )
        {
            case "web":
                $cadena .= "<a href='http://".$valor."' target='_blank'>
                <img src='iconos/package_network.png' width='14' alt='Abrir Web'/>
    			</a>";
            break;
            case "mail":
                $cadena .= "<a href='mailto:".$valor."'>
                <img src='iconos/mail_generic.png' width='14' alt='Enviar Correo'/>
                </a>";
            break;
        }
        return $cadena;
    }
    /**
     * Mostramos el formulario
     */
    function verFormulario()
    {
        
    }
}
?>