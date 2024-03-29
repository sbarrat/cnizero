<?php
//Fichero genfactura.php (Genera la factura dependiendo de lo que se pida). Realizado por Ruben Lacasa Mas ruben@ensenalia.com 2006-2007 
//error_reporting(E_ALL);//fichero genfactura.php le llegan el mes y el cliente y genera un word.
include("../inc/variables.php");
include("telecos.php");
setlocale(LC_ALL, 'es_ES');
//Funciones auxiliares***********************************************************************************************/
function traduce($texto)
{
//en algunos casos
	if(SISTEMA == "windows")
		$bien = utf8_encode($texto); //para windows
	else
		$bien = $texto;//para sistemas *nix
	return $bien;
}
/********************************************************************************************************************/
function codifica($texto)
{
//en algunos casos
	if(SISTEMA == "windows")
		$bien = utf8_decode($texto); //para windows
	else
		$bien = $texto;//para sistemas *nix
	return $bien;
}
/********************************************************************************************************************/
//calculo del total con iva
function iva($importe,$iva)
{
	$total = round($importe + ($importe * $iva)/100,2);
	return $total;
}
/*******************************************************************************************************************/
//observaciones especiales y ya de paso que consulte la forma de pago
function observaciones_especiales($cliente,$factura)
{
	include("../inc/variables.php");
	$sql = "Select obs_alt from regfacturas where codigo like $factura and obs_alt is not null";
	//$sql = "Select obs_alt, fpago, obs_fpago from regfacturas where codigo like $factura";
	$consulta = @mysql_db_query($dbname,$sql,$con);
	if (mysql_numrows($consulta)!=0)
	{
		$resultado = @mysql_fetch_array($consulta);
		$obser = $resultado[0];
	}
	else
		$obser = "";
	return $obser;
}
/*******************************************************************************************************************/
function dame_el_mes($mes)
{
	switch($mes)
	{
		case 1: $marcado = "Enero";breaK;
		case 2: $marcado = "Febrero";breaK;
		case 3: $marcado = "Marzo";breaK;
		case 4: $marcado = "Abril";breaK;
		case 5: $marcado = "Mayo";breaK;
		case 6: $marcado = "Junio";breaK;
		case 7: $marcado = "Julio";breaK;
		case 8: $marcado = "Agosto";breaK;
		case 9: $marcado = "Septiembre";breaK;
		case 10: $marcado = "Octubre";breaK;
		case 11: $marcado = "Noviembre";breaK;
		case 12: $marcado = "Diciembre";breaK;
	}
	return $marcado;
}
/*******************************************************************************************************************/
function cambiaf($stamp) //funcion del cambio de fecha
{
	//formato en el que llega aaaa-mm-dd o al reves
	$fdia = explode("-",$stamp);
	$fecha = $fdia[2]."-".$fdia[1]."-".$fdia[0];
	return $fecha;
}
/******************************************************************************************************************/
//Para distintas fechas de facturacion
function consulta_fecha($cliente,$mes,$inicial,$final) //consulta los rangos de la fecha
{
	$check1=$inicial{4};
	$check2=$final{4};
	if($check1!='-')
	$inicial=cambiaf($inicial);
	if($check2!='-')
	$final=cambiaf($final);
	if($inicial!='0000-00-00')
	{
		if(($final!="0000-00-00") && ($final!="--") && ($final!=""))
		{
			$cadena .= " and datediff(c.fecha,'$inicial') >= 0 and datediff(c.fecha,'$final') <=0 ";
		}
		else
			$cadena = " and c.fecha like '$inicial' ";
	}
	else
	{
	include("../inc/variables.php");
	$sql = "Select valor from agrupa_factura where idemp like $cliente and concepto like 'dia'";
	$consulta = mysql_db_query($dbname,$sql,$con);
		if(mysql_numrows($consulta)!=0)
		{
			$resultado = mysql_fetch_array($consulta);
			if($resultado[0]!="")
			{
				$mes_ant = $mes - 1;
				$fecha_inicial = date(Y)."-".$mes_ant."-".$resultado[0];
				$fecha_final = date(Y)."-".$mes."-".$resultado[0];
				$cadena =" and (c.fecha > '$fecha_inicial' and c.fecha <= '$fecha_final')";
			}
			else
				$cadena =" and (date_format(curdate(),'%Y') 
		like date_format(c.fecha,'%Y') and '$mes' like date_format(c.fecha,'%c')) ";
		}
		else
		$cadena=" and (date_format(curdate(),'%Y') 
	like date_format(c.fecha,'%Y') and '$mes' like date_format(c.fecha,'%c')) ";
	}
	
	//echo "Punto de control consulta_fecha valor cadena:".$cadena;
return $cadena;
}
/******************************************************************************************************************************/
//Generacion de los no agrupados
function consulta_no_agrupado($cliente)
{
		include("../inc/variables.php");
		$pila = array("Franqueo","Consumo Tel%fono","Material de oficina","Secretariado","Ajuste");
		$i=5;
		$sql = "Select s.Nombre,a.valor from agrupa_factura as a join servicios2 as s on a.valor = s.id where a.idemp like $cliente and a.concepto like 'servicio'";
		$consulta = mysql_db_query($dbname,$sql,$con);
		if(mysql_numrows($consulta)!=0)
			while($resultado = mysql_fetch_array($consulta))
			{
				$pila[]=$resultado[0];
				$i++;
			}
		$cadena = "and (";
		for($j=0;$j<=count($pila)-1;$j++)
		{
			$cadena .= " d.Servicio like '$pila[$j]' ";
			if ($j!=count($pila)-1)
				$cadena .= " or ";
		}
		$cadena .=") order by d.ImporteEuro desc , d.Servicio asc";
		return $cadena;
}
/****************************************************************************************************************************/
//Genaracion de consulta de los agrupamientos
function consulta_agrupado($cliente)
{
		include("../inc/variables.php");
		$pila = array("Franqueo","Consumo Tel%fono","Material de oficina","Secretariado","Ajuste");
		$i=5;
		$sql = "Select s.Nombre,a.valor from agrupa_factura as a join servicios2 as s on a.valor = s.id where a.idemp like $cliente and a.concepto like 'servicio'";
		$consulta = mysql_db_query($dbname,$sql,$con);
		if(mysql_numrows($consulta)!=0)
			while($resultado = mysql_fetch_array($consulta))
			{
				$pila[]=$resultado[0];
				$i++;
			}
			$cadena = "and (";
			for($j=0;$j<=count($pila)-1;$j++)
			{
				$cadena .= " d.Servicio not like '$pila[$j]' ";
				if ($j!=count($pila)-1)
					$cadena .= " and ";
			}
		$cadena .=") group by d.Servicio order by d.ImporteEuro desc , d.Servicio asc";
		return $cadena;
}
/**************************************************************************************************************************/
function cabezera_factura($nombre_fichero,$fecha_factura,$codigo,$cliente)
{
	include("../inc/variables.php");
	$fecha_factura = explode("-",$fecha_factura);
	$fecha_de_factura = $fecha_factura[0]." de ".dame_el_mes($fecha_factura[1])." de ".$fecha_factura[2];
	$sql = "Select * from clientes where id like $cliente";
	$consulta = mysql_db_query($dbname,$sql,$con);
	$resultado = mysql_fetch_array($consulta);
	$cabezera = "
	<br/><br/><br/><div class='titulo'>".strtoupper($nombre_fichero)."</div><br/>
	<div class='cabezera'>
	<table width='100%'>
	<tr>
		<td  align='left' class='celdilla_sec'>
		<br/>FECHA:".$fecha_de_factura."
		<br/>";
	if($nombre_fichero =='PROFORMA')
		$cabezera .= "<br/>".$nombre_fichero;	
	else
		$cabezera .= "<br/>N&deg;".$nombre_fichero.":".$codigo;
	$cabezera .= "</td>
		<td  class='celdilla_imp'>
				".strtoupper($resultado[1])."<br>
				".$resultado[6]."<br>
				".$resultado[8]."&nbsp;&nbsp;-&nbsp;&nbsp;".$resultado[7]."<br>
				NIF:".$resultado[5]."
		</td></tr></table></div><br/>";
		return $cabezera;
}
/******************PIE DE LA FACTURA ***********************************************************************************/
function pie_factura($cliente,$observaciones,$codigo)
{
		include("../inc/variables.php");
		/* 
		 * Comprobamos si esta metido dentro de regfacturas,
		 * si no lo consultamos, lo metemos y lo mostramos
		 */
		$sql="Select * from regfacturas where codigo like '$codigo'";
		$consulta = @mysql_db_query($dbname,$sql,$con);
		$resultado = @mysql_fetch_array($consulta);
		if($resultado[fpago]!="")
		{
			$pie_factura = "
			<br/>
			<div class='celdilla_sec'>
			Forma de pago: ".$resultado[fpago]."
			<br/><br/>
			".$resultado[obs_fpago]." ".$resultado[obs];
		}
		else
		{
		$sql = "SELECT * from facturacion where idemp like $cliente";
			if ($consulta = mysql_db_query($dbname,$sql,$con))
			{	
				$resultado = mysql_fetch_array($consulta);
				$pie_factura = "
				<br/>
				<div class='celdilla_sec'>
				Forma de pago: ".$resultado[fpago]."
				<br/><br/>";
				$fpago = $resultado[fpago];
				if(($resultado[fpago] != "Cheque") && ($resultado[fpago] != "Contado") && ($resultado[fpago] != "Tarjeta credito") && ($resultado[fpago] != utf8_decode("Liquidación"))) 
					{
						$pie_factura .= "Cuenta: ".$resultado[cc];
						$obs_fpago="Cuenta: ".$resultado[cc];
					}
				if(($resultado[fpago] == "Cheque")&& ($resultado[cc]!="")) //Caso de UAE
					{
						$pie_factura .= "Vencimiento: ".$resultado[cc];
						$obs_fpago= "Vencimiento: ".$resultado[cc];
					}
			/*Agregamos a regfacturas*/
			$sql = "Update regfacturas set fpago='$fpago', obs_fpago='$obs_fpago' where codigo like $codigo";
			$consulta = @mysql_db_query($dbname,$sql,$con);
			$pie_factura .= " ".observaciones_especiales($cliente,$codigo);
			$pie_factura .= "</div>";
			}
		}
	return $pie_factura; //$pie_factura;
}
//GENERA LA CONSULTA DEL ALMACENAJE DEPENDIENDO DE LOS PARAMETROS DE AGRUPA_FACTURA***********************************/
function consulta_almacenaje($cliente,$mes,$inicial,$final)
{
	include("../inc/variables.php");
	$check1=$inicial{4};
	$check2=$final{4};
	if($check1!='-')
        $inicial=cambiaf($inicial);
	if($check2!='-')
        $final=cambiaf($final);
	if(($inicial == '0000-00-00') && ($final == '0000-00-00'))
	{
		$sql = "Select * from agrupa_factura where concepto like 'dia' and idemp like $cliente and valor not like ''" ;
		$consulta = mysql_db_query($dbname,$sql,$con);
		if(mysql_numrows($consulta)!=0) //existe
		{
			$resultado = mysql_fetch_array($consulta);
			$sql .= "Select bultos, datediff(fin,inicio), inicio, fin  from z_almacen where cliente like $cliente and (month(inicio) like ($mes-1) and month(fin) like $mes and day(inicio) >= $resultado[valor]  and  day(fin) <= $resultado[valor] and year(inicio) like year(curdate()) and year(fin) like year(curdate()))";
		}	
		else
			$sql = "Select bultos, datediff(fin,inicio), inicio, fin  from z_almacen where cliente like $cliente and month(fin) like $mes and year(fin) like year(curdate())";
	}
	else
	{
		$check1=$inicial{4};
		$check2=$final{4};
		if($check1!='-')
			$inicial=cambiaf($inicial);
		if($check2!='-')
			$final=cambiaf($final);
	 	if(($inicial != "" ) && ($final != ""))
			$sql = "Select bultos, datediff(fin,inicio), inicio, fin from z_almacen where cliente like $cliente and month(fin) like month('$final') and year(fin) like year('$final')";
		else
			$sql = "Select bultos, datediff(fin,inicio), inicio, fin from z_almacen where cliente like $cliente and fin <= '$final'";
	}
return $sql;
}
//Consulta si la factura esta en el historico
function historico($factura)
{
	include("../inc/variables.php");
	$sql = "Select * from historico where factura like $factura";
	
	$consulta = mysql_db_query($dbname,$sql,$con);
	if(mysql_numrows($consulta)!=0)
	$cadena = "ok";
	else
	$cadena = "ko";
	return $cadena;
}
function agrega_historico($factura,$servicio,$cantidad,$unitario,$iva,$obs)
{
	$servicio = trim(utf8_encode($servicio));//Eliminamos espacios en blanco al principio y final
	include("../inc/variables.php");
	$sql = "Insert into historico (factura,servicio,cantidad,unitario,iva,obs) values
	('$factura','$servicio','$cantidad','$unitario','$iva','$obs')";
	$consulta = mysql_db_query($dbname,$sql,$con);
	//echo $sql;
}
/***********************************************************************************************/
//fin funciones axiliares*****************************************************
//FUNCION PRINCIPAL -- OBLIGATORIO EL CLIENTE
//Parametros del get cliente,mes,fecha_factura,codigo
//En puntual: fecha_inicial_factura, fecha_final_factura para filtrado
//Proforma: prueba = 1

if(isset($_GET[cliente]))
{
	//$ano_domini=date(Y);
	$ano_factura = explode("-",$_GET[fecha_factura]);
	$cliente = $_GET[cliente];
	$mes = $_GET[mes];
	$ano=$ano_factura[0];
	$codigo = $_GET[codigo];
	$historico = historico($codigo);
	$fecha_factura = $_GET[fecha_factura];
	$fecha_inicial_factura = $_GET[fecha_inicial_factura];
	$fecha_final_factura = $_GET[fecha_final_factura];
	$observaciones = $_GET[observaciones];
	//Filtro 1, clic en proforma
	if(isset($_GET[prueba]))
	{
		$fichero = "PROFORMA";
		$titulo = "FACTURA<BR/>PROFORMA";//Guardamos datos en profroma
	}
	else
	{
		$fichero = "FACTURA";
		$titulo = $fichero;
		//Guardamos datos en factura
	}
}
//CASOS DE Imprimir factura generada o ver el duplicado
if((isset($_GET[factura])) || (isset($_GET[duplicado])))
{
	if(isset($_GET[factura]))
	{
		$datos = "Select * from regfacturas where id like $_GET[factura]";
		
	}
	else
	{
		$datos = "Select * from regfacturas where id like $_GET[duplicado]";
		
	}
	$consulta = mysql_db_query($dbname,$datos,$con);
	$resultado = mysql_fetch_array($consulta);
	$cliente = $resultado[id_cliente];
	$fecha_factura = cambiaf($resultado[fecha]);
	$ano_factura = explode("-",$fecha_factura);
	$mes = intval($resultado[mes]);
	$codigo = $resultado[codigo];
	$historico = historico($codigo);
	$fecha_inicial_factura = $resultado[fecha_inicial];
	$fecha_final_factura = $resultado[fecha_final];
	$observaciones = $resultado[obs_alt];
	if(isset($_GET[duplicado]))
	{
		$fichero = "FACTURA (DUPLICADO)";
		$titulo = "FACTURA<BR/>DUPLICADO";//Guardamos datos en profroma
	}
	else
	{
		$fichero = "FACTURA";
		$titulo = $fichero;
		//Guardamos datos en factura
	}
}

$nombre_fichero = "<span style='font-size:16.0pt'>".$titulo."</span>";

//CABEZERA***************************************************************************/	
	$cabezera_factura = cabezera_factura($fichero,$fecha_factura,$codigo,$cliente);
//PRESENTACION************************************************************************/
//CASOS POSIBLES, MENSUAL y PUNTUAL en puntual hay que pasar los limites
//fecha_inicial_factura y fecha_final_factura
if(($fecha_inicial_factura != '0000-00-00') && ($fecha_final_factura != '0000-00-00'))
{
	$inicio = $fecha_inicial_factura;
	$final = $fecha_final_factura;
	
}
else
{
	$inicio = "0000-00-00";
	$final = "0000-00-00";
}
?>
<html>
<head>
<title><?php echo $fichero; if($inicio != "0000-00-00") echo " ocupacion puntual "; else dame_el_mes("m"); ?></title>
<link rel="stylesheet" type='text/css' href="estilo.css" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>
<body>
<?php
	echo $cabezera_factura;
	print("
	<table cellpadding='2px' cellspacing='0px' width='100%' id='tabloide'>
	<tr>
	<th align='center' width='48%' >Servicio</th>
	<th align='center' width='8%' >Cant.</th>
	<th align='center' width='12%' >P/Unitario</th>
	<th align='center' width='12%' >IMPORTE</th>
	<th align='center' width='8%' >IVA</th>
	<th align='center' width='12%' >TOTAL</th>
	</tr>");
//PARTE DEL CONTRATO Y DEL ALMACENAJE SI PROCEDE cuidado con el mes
//la primera linea tiene que ser el importe del mes del tipo de cliente
//VALIDO DESDE MAYO DEL 07
//DATOS SERVICIOS FIJOS**********************************************************/
//solo se cargan los fijos si no son ocupacion puntual
/*CHEQUEO DE HISTORICO, si no esta en el historico se agrega*/
if($historico == "ok")
{
	$sql = "Select * from historico where factura like $codigo";
	$consulta = mysql_db_query($dbname,$sql,$con);
	while($resultado=mysql_fetch_array($consulta))
	{
		$importe_sin_iva = $resultado[cantidad]*$resultado[unitario];
		echo "<tr>
		<td><p class='texto'>".ucfirst(utf8_decode($resultado[2]))." ".ucfirst(utf8_decode($resultado[6]))."</td>
		<td align='right'>".number_format($resultado[cantidad],2,',','.')."&nbsp;</td>
		<td align='right'>".number_format($resultado[unitario],2,',','.')."&euro;&nbsp;</td>
		<td align='right'>".number_format($importe_sin_iva,2,',','.')."&euro;&nbsp;</td>
		<td align='right'>".$resultado[iva]."%&nbsp;</td>
		<td align='right'>".number_format(iva($importe_sin_iva,$resultado[iva]),2,',','.')."&euro;&nbsp;</td></tr>";
		$total = $total + iva($importe_sin_iva,$resultado[5]);
		$bruto = $bruto + $importe_sin_iva;
		$celdas++;
		$cantidad++;
	}
	//echo  consulta_almacenaje($cliente,$mes,$inicio,$final);
		
}
else
{
	/*echo $ano_factura[2];
	echo $inicio;
	echo $final;*/
	if(((($mes >= 3) && ($ano_factura[2] == 2007))||(($ano_factura[2]>= 2008)) && ($inicio == "0000-00-00")) && ($final == "0000-00-00"))
	{
		$sql = "Select * from tarifa_cliente where ID_Cliente like $cliente order by Imp_Euro desc";
		//echo $sql;/*PUNTO DE CONTROL*/
		$consulta = mysql_db_query($dbname,$sql,$con);
		while ($resultado = mysql_fetch_array($consulta))
		{
			$importe_sin_iva = $resultado[7]*$resultado[4];
			echo "<tr>
			<td><p class='texto'>".ucfirst(codifica($resultado[2]))." ".ucfirst(codifica($resultado[6]))."</p></td>
			<td align='right'>".number_format($resultado[7],2,',','.')."&nbsp;</td>
			<td align='right'>".number_format($resultado[4],2,',','.')."&euro;&nbsp;</td>
			<td align='right'>".number_format($importe_sin_iva,2,',','.')."&euro;&nbsp;</td>
			<td align='right'>".$resultado[5]."%&nbsp;</td>
			<td align='right'>".number_format(iva($importe_sin_iva,$resultado[5]),2,',','.')."&euro;&nbsp;</td></tr>";
			$total = $total + iva($importe_sin_iva,$resultado[5]);
			$bruto = $bruto + $importe_sin_iva;
			$celdas++;
			$cantidad++;
			/*ALERTA LINEA A MODIFICAR EN EL CAMBIO*/
			$servicio_desc = ucfirst($resultado[2]);//." ".ucfirst(codifica($resultado[6]));
		
		if(($historico == "ko")&& (!isset($_GET[prueba]))) 
		//Agregamos al historico
			agrega_historico($codigo,codifica($servicio_desc),$resultado[7],$resultado[4],$resultado[5],ucfirst(codifica($resultado[6])));
			
		}
	}
/************************************************************************************/
//Devuelve la consulta para generar el almacenaje
/*Parte de consulta de importe e iva de almacenaje*/
    /*Buscamos los datos de importe e iva de almacenaje*/
    $sql = "Select datediff('".cambiaf($fecha_factura)."','2010-07-01')";
    //echo $sql;
    $consulta = mysql_db_query($dbname,$sql,$con);
    $diff = mysql_fetch_array($consulta);
    if($diff[0]>=0)
    {
        $sql = "select PrecioEuro, iva from servicios2 where nombre like '%Almacenaje%'";
        $consulta = mysql_db_query($dbname,$sql,$con);
        $par_almacenaje = mysql_fetch_array($consulta);
    }
    else
        $par_almacenaje = array('PrecioEuro'=>'0.70','iva'=>'16');
    /*Final datos de valores del almacenaje*/
	$sql = consulta_almacenaje($cliente,$mes,$inicio,$final);
	//echo $sql;/*PUNTO DE CONTROL*/
	
	$consulta = @mysql_db_query($dbname,$sql,$con);
	while ($resultado = @mysql_fetch_array($consulta))
	{
		$dias_almacen = $resultado[1];
		$subtotala = $resultado[0]*$dias_almacen*$par_almacenaje['PrecioEuro'];
		
        $totala = iva($subtotala,$par_almacenaje['iva']);
		echo "<tr>
		<td ><p class='texto'>Bultos Almacenados del  ".cambiaf($resultado[2])." al ".cambiaf($resultado[3])."</p></td>
		<td align='right'>".number_format($resultado[0],2,',','.')."&nbsp;</td>
		<td align='right'>0,70&euro;&nbsp;</td>
		<td align='right'>".number_format($subtotala,2,',','.')."&euro;&nbsp;</td>
		<td align='right'>".$par_almacenaje['iva']."%&nbsp;</td>
		<td align='right'>".number_format($totala,2,',','.')."&euro;&nbsp;</td></tr>";
		$cantidad = $resultado[0] + $cantidad;
		$bruto = $bruto + $subtotala;
		$total = $totala + $total;
		$celdas++;
		$cadena_texto = " del  ".cambiaf($resultado[2])." al ".cambiaf($resultado[3]);
	if(($historico == "ko")&& (!isset($_GET[prueba]))) //Agregamos al historico
		agrega_historico($codigo,"Bultos Almacenados",1,$subtotala,$par_almacenaje['iva'],$cadena_texto);
		
	}
//fin del almacenaje**********************************************************************/
//FIN DE ESTA PARTE
//Servicio contratado
//#####################Servicios No agrupados#############################################
//control de puntuales
	$sql = "Select d.Servicio, d.Cantidad, date_format(c.fecha,'%d-%m-%Y') as fecha, 
	d.PrecioUnidadEuros, d.ImporteEuro, d.iva, c.`Id Pedido` ,
	d.observaciones from `detalles consumo de servicios` as d join `consumo de servicios` as c 
	on c.`Id Pedido` = d.`Id Pedido` where c.Cliente like $cliente ";
//consulta de fecha
	$sql .= consulta_fecha($cliente,$mes,$inicio,$final); //con esta miramos los rangos de la factura
	$sql .= consulta_no_agrupado($cliente);
	//echo $sql;/*PUNTO DE CONTROL*/
	$consulta = mysql_db_query($dbname,$sql,$con);
	while ($resultado=mysql_fetch_array($consulta))
	{
		$subtotal = $resultado[4] + ($resultado[4]*$resultado[5])/100;
//acumulados
		$total = $subtotal + $total;
		$cantidad = $resultado[1] + $cantidad;
//fin acumulados
		echo "<tr>
		<td ><p class='texto'>".ucfirst($resultado[0])." ".codifica(ucfirst($resultado[7]))."</p></td>
		<td align='right'>".number_format($resultado[1],2,',','.')."&nbsp;</td>
		<td align='right'>".number_format($resultado[3],2,',','.')."&euro;&nbsp;</td>
		<td align='right'>".number_format($resultado[4],2,',','.')."&euro;&nbsp;</td>
		<td align='right'>".$resultado[5]."%&nbsp;</td>
		<td align='right'>".number_format($subtotal,2,',','.')."&euro;&nbsp;</td></tr>";
		$bruto = $bruto + $resultado[4];
		$celdas++;
		//$servicio_desc = ucfirst($resultado[0])." ".codifica(ucfirst($resultado[7]));
		
		if(($historico == "ko")&& (!isset($_GET[prueba]))) //Agregamos al historico
			agrega_historico($codigo,$resultado[0],$resultado[1],$resultado[3],$resultado[5],$resultado[7]);
		
	}
//#####################################Parte agrupada###############################################
	$sql = "Select d.Servicio, sum(d.Cantidad), date_format(c.fecha,'%d-%m-%Y') as fecha, 
	d.PrecioUnidadEuros, sum(d.ImporteEuro), d.iva, c.`Id Pedido` ,
	d.observaciones from `detalles consumo de servicios` as d join `consumo de servicios` as c 
	on c.`Id Pedido` = d.`Id Pedido` where c.Cliente like $cliente";
	$sql .= consulta_fecha($cliente,$mes,$inicio,$final);
	$sql .= consulta_agrupado($cliente);
	//echo $sql;//<- Punto de Control
	//echo $cliente.",".$mes.",".$inicio.",".$final;
	$consulta = mysql_db_query($dbname,$sql,$con);
	while ($resultado=mysql_fetch_array($consulta))
	{
		$subtotal = $resultado[4]+ ($resultado[4]*$resultado[5])/100;
//acumulados
		$total = $subtotal + $total;
		$cantidad = $resultado[1] + $cantidad;
//fin acumulados
		echo "<tr>
		<td ><p class='texto'>".ucfirst($resultado[0])." ".codifica(ucfirst($resultado[7]))."</p></td>
		<td align='right'>".number_format($resultado[1],2,',','.')."&nbsp;</td>
		<td align='right'>".number_format($resultado[3],2,',','.')."&euro;&nbsp;</td>
		<td align='right'>".number_format($resultado[4],2,',','.')."&euro;&nbsp;</td>
		<td align='right'>".$resultado[5]."%&nbsp;</td>
		<td align='right'>".number_format($subtotal,2,',','.')."&euro;&nbsp;</td></tr>";
		$bruto = $bruto + $resultado[4];
		$celdas++;
		//$servicio_desc = ucfirst($resultado[0])." ".codifica(ucfirst($resultado[7]));
		if(($historico == "ko")&& (!isset($_GET[prueba]))) //Agregamos al historico
			agrega_historico($codigo,ucfirst($resultado[0]),$resultado[1],$resultado[3],$resultado[5],ucfirst($resultado[7]));
		
	}
//descuento si procede
		$esql = "Select razon from clientes where id like $cliente";
		$consulta = mysql_db_query($dbname,$esql,$con);
		$resultado = mysql_fetch_array($consulta);
		if(($resultado[0] != "") && ($resultado[0] != ""))
		{
			$porcentaje = explode("%",$resultado[0]);
			$descuento = ($bruto * $porcentaje[0])/100;
			$descuento_con_iva = $descuento * 1.16;
			echo "<tr>
			<td ><p class='texto'>Descuento del ".$porcentaje[0]."%</p></td>
			<td align='right'>1&nbsp;</td>
			<td align='right'>-".number_format($descuento,2,',','.')."&euro;&nbsp;</td>
			<td align='right'>-".number_format($descuento,2,',','.')."&euro;&nbsp;</td>
			<td align='right'>18%&nbsp;</td>
			<td align='right'>-".number_format($descuento_con_iva,2,',','.')."&euro;&nbsp;</td></tr>";
		$descuento_historico = "-".$descuento;
		if(($historico == "ko")&& (!isset($_GET[prueba]))) //Agregamos al historico
			agrega_historico($codigo,"Descuento","1",$descuento_historico,"16", "del ".$porcentaje[0]);
		
		}
		else
		{
			$descuento = 0;
			$descuento_con_iva = 0;
		}
		$bruto = $bruto - $descuento;
		$total = $total - $descuento_con_iva;
} //Cierre de las que no estan en historico

//Compensacion de dise�o
	$coeficiente = 432 - ($celdas-1) * 18;
	if($coeficiente >= 1)
	{
		echo "<tr><td height='".$coeficiente."px'>&nbsp;</td>
		<td align='center'>&nbsp;</th>
		<td align='center'>&nbsp;</th>
		<td align='center'>&nbsp;</th>
		<td align='center'>&nbsp;</th>
		<td align='center'>&nbsp;</th>
		</tr>";
	}
	echo "<tr>
	<th align='center'>&nbsp;</th>
	<th align='right'>&nbsp;".$cantidad."&nbsp;</th>
	<th align='center'>&nbsp;</th>
	<th align='right'>".number_format($bruto,2,',','.')."&euro;&nbsp;</th>
	<th align='center'>&nbsp;</th>
	<th align='right'>".number_format($total,2,',','.')."&euro;&nbsp;</th>";
	echo "</table>";
//RESUMEN
	$total_iva = $total - $bruto;
	echo "<br/><table width='100%' cellpadding='2px' cellspacing='2px' style='font-size:10.0pt'><tr>
	<th width='15%'>&nbsp;</th>
	<th  class='celdilla_tot' >TOTAL BRUTO</th>
	<th width='15%'>&nbsp;</th>
	<th  class='celdilla_tot' >IVA</th>
	<th width='15%'>&nbsp;</th>
	<th  class='celdilla_tot' >TOTAL</th></tr>
	<tr>
	<th width='15%'>&nbsp;</th>
	<th  class='celdilla_tot' >".number_format($bruto,2,',','.')."&euro;</th>
	<th width='15%'>&nbsp;</th>
	<th  class='celdilla_tot' >".number_format($total_iva,2,',','.')."&euro;</th>
	<th width='15%'>&nbsp;</th>
	<th  class='celdilla_tot' >".number_format($total,2,',','.')."&euro;</th></tr></table>";
	//$pie_factura .= "<br />".$bruto."-".iva($bruto,16)."<br />";
	

//aqui insertaria la factura en la base de datos
//campos a insertar id_cliente, codigo, fecha, consulta,importe
//OPCIONES FACTURA NUEVA, PROFORMA, DUPLICADO o FACTURA
//if(($fichero!="PROFORMA") && (!isset($_GET[factura])) && (!isset($_GET[duplicado])))
//echo "COOOOOOOOOOOO".$inicio;
	//echo $final;
if(($fichero!="PROFORMA") && (!isset($_GET[duplicado])))
{
	$fecha = cambiaf($fecha_factura);
	if (isset($inicio) && ($final != '0000-00-00'))
	{
		$puntual = 1;
		$fecha_inicial = cambiaf($inicio);
		$fecha_final = cambiaf($final);
	}
	$importe_iva = number_format($total_iva,2,'.','');
	$importe_total = number_format($total,2,'.','');
	//estamos en Factura si es repetida no se agrega
	//Linea de teste de fechas
	
	if(comprueba_la_factura($cliente,$codigo,$fecha,$total_iva,$total)) //no existe
		if ($puntual == 1)
		{
			$esecuele = "Insert into regfacturas (id_cliente,codigo,fecha,iva,importe,obs_alt,fecha_inicial,fecha_final,mes,ano) values ('$cliente','$codigo','$fecha','$importe_iva','$importe_total',\"$observaciones\",'$fecha_inicial','$fecha_final','$mes','$ano')";	
		}
		else
		{
			$esecuele = "Insert into regfacturas (id_cliente,codigo,fecha,iva,importe,obs_alt,mes,ano) 	values ('$cliente','$codigo','$fecha','$importe_iva','$importe_total',\"$observaciones\",'$mes','$ano')";
		}
		//echo $esecuele;/*LINEA DE TEST*/
		$consulta=mysql_db_query($dbname,$esecuele,$con);
	//else
		//echo comprueba_la_factura($cliente,$codigo,$fecha,$total_iva,$total);
}
/******************COMPROBAMOS SI EXISTE LA FACTURA PARA NO CREARLA********************/
function comprueba_la_factura($cliente,$codigo,$fecha,$total_iva,$total)
{
		include("../inc/variables.php");
		$sql = "Select * from regfacturas where id_cliente like $cliente and codigo like $codigo and fecha like '$fecha'";
		$consulta = mysql_db_query($dbname,$sql,$con);
		if (mysql_numrows($consulta)==0)
			return true;
		else //existe
		{
			if(($resultado[iva]!=$total_iva) && ($resultado[importe]!=$total))
			{
				$sql = "Update regfacturas set iva='$total_iva',importe='$total' where id_cliente like '$cliente' and codigo like '$codigo' and fecha like '$fecha'";
				$consulta = mysql_db_query($dbname,$sql,$con);
			}
			return false;
		}
		
}	
/**************************************************************************************/	
//PIE FACTURA*************************************************************************/
	echo pie_factura($cliente,$observaciones,$codigo);

//echo $pie_factura;
?>
</body></html>


