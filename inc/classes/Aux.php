<?php
class Aux
{
    /**
     * Traduce el texto a caracteres imprimibles
     * 
     * @param string $string
     */
    static function traduce( $string ) 
    {
        return utf8_encode( $string );
    }
    /**
     * Codifica el texto para su almacenamiento correcto
     * 
     * @param string $string
     */
    static function codifica( $string ) 
    {
        return utf8_decode( $string );    
    }
    /**
     * Cambia la fecha de formato MySQL a Normal
     * 
     * @param string $fechaMySQL
     */
    static function fechaNormal ( $fechaMySQL )
    {
        $fecha = sscanf( $fechaMySQL, "%4d-%2d-%2d" );
        return $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];
        
    }
    /**
     * Cambia la fecha de formato Normal a MySQL
     * 
     * @param string $fechaNormal
     */
    static function fechaMySQL ( $fechaNormal )
    {
        $fecha = sscanf( $fechaNormal, "%2d-%2d-%4d" );
        return $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];
    }

}
?>