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
            WHERE tabla like '" . $this->_tabla . "'";
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
    public function getTabla()
    {
        return $this->_tabla;
    }
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
                = "<img src='imagenes/".$activo[$this->_datos['estado']]['imagen']."'
            	alt='".$activo[$this->_datos['estado']]['texto'] ."' width='24px' />
            	<img src='imagenes/".$desvio[$this->_datos['desvio']]['imagen'] ."'
				alt='".$desvio[$this->_datos['desvio']]['texto']."' width='24px' />
				<img src='imagenes/".$extranet[$this->_datos['extranet']]['imagen']."'
				alt='".$desvio[$this->_datos['extranet']]['texto']."' width='24px' />";   	
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