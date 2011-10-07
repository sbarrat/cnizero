<?php
include_once "inc/classes/Document.php";
class Avisos extends Document{
	private $_cumples;
	private $_contratos;
	
	function __construct() {
		parent::__construct();
		
		for($i=1;$i<=12;$i++){
			for($j=1;$j<=31;$j++)
			$this->_cumples[$i][$j]="";
		}
		$this->_contratos = $this->_cumples;
		$this->llenadoCumples();
	
	}
	function llenadoCumples(){
		$sql["persona_central"] = "SELECT idemp, persona_central as nombre,month(cumple) as mes, day(cumple) as dia  FROM `centro`.`pcentral`
		where cumple not like '0000-00-00' order by month(cumple) asc, day(cumple) asc";
		$sql["persona_empresa"] = "SELECT idemp, nombre, apellidos, month(cumple) as mes, day(cumple) as dia  FROM `centro`.`pempresa`
		where cumple not like '0000-00-00' order by month(cumple) asc, day(cumple) asc";
		$sql["empleados"] = "Select Nombre, Apell1, month(FechNac) as mes, day(FechNac) as dia From `centro`.`empleados`
		where FechNac not like '0000-00-00' order by month(FechNac) asc, day(FechNac) asc";
		foreach($sql as $key=>$query){
			parent::consulta($query);
			$datos = parent::datos();
			foreach($datos as $dato){
				switch($key){
					case "persona_central":$this->_cumples[$dato['mes']][$dato['dia']][]=array("idemp"=>$dato['idemp'],"nombre"=>$dato['nombre']);break;
					case "persona_empresa":$this->_cumples[$dato['mes']][$dato['dia']][]=array("idemp"=>$dato['idemp'],"nombre"=>$dato['nombre']." ".$dato['apellidos']);break;
					case "empleados":$this->_cumples[$dato['mes']][$dato['dia']][]=array("idemp"=>0,"nombre"=>$dato['Nombre']." ".$dato['Apell1']);break;
				}
			}
		}
	}
	function avisos_dia($dia,$mes,$titulo){
		
		$html="<div class='ui-widget-header'>Cumplen Años ".$titulo."</div>";
		$html.= "<div class='ui-widget-content'>";
		if(is_array($this->_cumples[$mes][$dia])){
			foreach($this->_cumples[$mes][$dia] as $personas){
				$html.= utf8_encode(ucwords(strtolower($personas['nombre'])))." de ".parent::nombre_cliente($personas['idemp'])."<br/>";
			}
		}
		else{
			$html.="Nadie cumple años ".$titulo;
		}
		$html.= "</div>";
		return $html;
	}
	function avisos_mes($mes,$titulo){
		$html="<div class='ui-widget-header'>Cumplen Años ".$titulo."</div>";
		$html.= "<div class='ui-widget-content'>";
		if(is_array($this->_cumples[$mes])){
			foreach($this->_cumples[$mes] as $key=>$dia){
				if(is_array($dia)){
				$html.="<div class='ui-widget-header'>".$key."-".parent::mes($mes)."</div>";	
				foreach($dia as $personas){
				if($key==date('j') && $mes == date('n'))
					$html.="<b>".utf8_encode(ucwords(strtolower($personas['nombre'])))." de ".parent::nombre_cliente($personas['idemp'])."</b><br/>";
				else
					$html.=utf8_encode(ucwords(strtolower($personas['nombre'])))." de ".parent::nombre_cliente($personas['idemp'])."<br/>";
				
				}
				}
			}
		}
		else{
			$html.="Nadie cumple años ".$titulo."";
		}
		$html.= "</div>";
		return $html;
	}
	
	function ver_avisos(){
		$html="
		<input type='button' id='btn_avisos' class='boton' value='[>]Avisos' onclick='avisos()'/>
		<div id='avisos'>";
		$html.=self::avisos_dia(date('j'),date('n'),"Hoy"); //avisos hoy
		$tomorrow = mktime(0,0,0,date("n"),date("j")+1,date("y")); //Calculamos el dia de mañana
		$html.=self::avisos_dia(date('j',$tomorrow),date('n',$tomorrow),"Mañana");//avisos mañana
		$html.=self::avisos_mes(date('n'),"este Mes");//avisos este mes
		$nextmonth = mktime(0,0,0,date("n")+1,date("j"),date("y")); //Calculamos el mes que viene
		$html.=self::avisos_mes(date('n',$nextmonth),"el Mes que Viene");//avisos el mes que viene
		$html.="</div>";
		return $html;
	}
	
}