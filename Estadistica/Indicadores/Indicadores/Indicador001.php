<?
	//DATOS SESIÓN
	if($DatNameSID){session_name("$DatNameSID");}
	else{$Compania[0]='Clinica San Juan de Dios';}
	session_start();
	include("../../Funciones.php");
	$ND=getdate();
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Atenci&oacute;n Ambulatoria</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
body {
	background-image: url(../../Imgs/Fondo.jpg);
	margin-top: 50px;
	margin-left: 50px;
}
select{background-color:transparent;
border-color:#DDDDDD;
border-style:solid;
border-width:thin;
color:#333333;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:10px;
}
input{background-color:transparent;
border-color:#000000;
border-style:solid;
border-width:thin;
color:#000000;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:10px;
font-weight:bold;
}
.Estilo2 {font-weight: bold}
-->
</style>
<script type="text/JavaScript">
<!--
function Valida(){
	document.FORMA.FechaIni.value=''+document.FORMA.fi1.value+'-'+document.FORMA.fi2.value+'';
	document.FORMA.FechaFin.value=''+document.FORMA.ff1.value+'-'+document.FORMA.ff2.value+'';
	//alert('Fecha1: '+document.FORMA.FechaIni.value+' Fecha2: '+document.FORMA.FechaFin.value+'');
	if(document.FORMA.FechaIni.value==""){
		alert("Debes seleccionar la fecha inicial.");
	}
	else{
		if(document.FORMA.FechaFin.value==""){
			alert("Debes seleccionar la fecha final.");
		}
		else{
			if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
				alert("La fecha inicial debe ser menor a la fecha final.");
			}
			else{
				if((document.FORMA.fi1.value<document.FORMA.ff1.value)&&(document.FORMA.fi2.value<document.FORMA.ff2.value)){
					alert("El intervalo no debe superar los 12 meses.");
				}
				else{
					location.href='?DatNameSID=<? echo $DatNameSID?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&especialidad='+document.FORMA.especialidad.value+'&grupo='+document.FORMA.grupo.value+'&profesional='+document.FORMA.profesional.value+'&tipo='+document.FORMA.tipo.value+'';
				}				
			}
		}
	}
}
//-->
</script>
</head>
<?php
	function getMonthDays($Month,$Year){
		if( is_callable("cal_days_in_month")){
			return cal_days_in_month(CAL_GREGORIAN,$Month,$Year);
		}
		else{
			return date("d",mktime(0,0,0,$Month+1,0,$Year));
		}
	}
	
	$FechaIni=$_GET['FechaIni'];
	//echo $FechaIni;
	$FechaFin=$_GET['FechaFin'];
	//echo $FechaFin;
	
    if($FechaIni==NULL){
		if($ND[mon]<10){$C1="0";}else{$C1="";}
		$anoini=$ND[year]-1;	
		$FechaIni="$anoini-$C1$ND[mon]";
	}
		
	if($FechaFin==NULL){
		if($ND[mon]<10){$C1="0";}else{$C1="";}
		$FechaFin="$ND[year]-$C1$ND[mon]";
	}
	
	$especialidad=$_GET['especialidad'];
	switch($especialidad){
		case NULL:$qe="and especialidad is not null";
		break;
		case "0":$qe="and especialidad is not null";
		break;
		default:$qe="and especialidad='$especialidad'";
		break;
	}
	
	$grupo=$_GET['grupo'];
	switch($grupo){
		case NULL:$qg="and grupo is not null";
		break;
		case "0":$qg="and grupo is not null";
		break;
		default:$qg="and grupo='$grupo'";
		break;		
	}
	
	$profesional=$_GET['profesional'];
	switch($profesional){
		case NULL:$qp="and medico is not null";
		break;
		case "0":$qp="and medico is not null";
		break;
		default:$qp="and medico='$profesional'";
		break;		
	}
	
	$tipo=$_GET['tipo'];
	switch($tipo){
		case NULL:$qt="and cup is not null";
		break;
		case "0":$qt="and cup is not null";
		break;
		default:$qt="and cup='$tipo'";
		break;		
	}
		
	$aaaa1=substr("$FechaIni", -7,4);
	$mm1=substr("$FechaIni", -2,2);
	//echo "$aaaa1,$mm1,$dd1";
	
	$aaaa2=substr("$FechaFin", -7,4);
	$mm2=substr("$FechaFin", -2,2);
	//echo "$aaaa2,$mm2,$dd2";
	
	$FechaIni="$FechaIni-01";
	$diafin=getMonthDays($mm2,$aaaa2);
	$FechaFin="$FechaFin-$diafin";
	//echo"$FechaIni y $FechaFin";
?>
<body>
<form id="FORMA" name="FORMA" method="post" action="">
  <div align="center">
    <table align="center" cellpadding="5">
      
      <tr>
        <td height="10" nowrap="nowrap"><div align="justify">
          <table border="0">
            <tr>
              <td nowrap="nowrap"><div align="justify"><strong>FECHA DESDE </strong></div></td>
              <td width="10" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap"><div align="justify"><strong>
                  <select name="fi1" id="fi1">
<?php
	for($i=2010;$i<=2020;$i++){
		echo'<option value="'.$i.'"';
		if($aaaa1==$i){
			echo'selected="selected"';
		}
		echo'>'.$i.'</option>';
	}
?>
                  </select>
                  <select name="fi2" id="fi2">
                    <option value="01"<?php if("$mm1"=='01'){echo'selected="selected"';}?>>Enero</option>
                    <option value="02"<?php if("$mm1"=='02'){echo'selected="selected"';}?>>Febrero</option>
                    <option value="03"<?php if("$mm1"=='03'){echo'selected="selected"';}?>>Marzo</option>
                    <option value="04"<?php if("$mm1"=='04'){echo'selected="selected"';}?>>Abril</option>
                    <option value="05"<?php if("$mm1"=='05'){echo'selected="selected"';}?>>Mayo</option>
                    <option value="06"<?php if("$mm1"=='06'){echo'selected="selected"';}?>>Junio</option>
                    <option value="07"<?php if("$mm1"=='07'){echo'selected="selected"';}?>>Julio</option>
                    <option value="08"<?php if("$mm1"=='08'){echo'selected="selected"';}?>>Agosto</option>
                    <option value="09"<?php if("$mm1"=='09'){echo'selected="selected"';}?>>Septiembre</option>
                    <option value="10"<?php if("$mm1"=='10'){echo'selected="selected"';}?>>Octubre</option>
                    <option value="11"<?php if("$mm1"=='11'){echo'selected="selected"';}?>>Noviembre</option>
                    <option value="12"<?php if("$mm1"=='12'){echo'selected="selected"';}?>>Diciembre</option>
                  </select>
              </strong></div></td>
              <td width="10" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap"><div align="right"><strong>HASTA</strong></div></td>
              <td width="10" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap"><strong>
                <select name="ff1" id="ff1">
<?php
	for($i=2010;$i<=2020;$i++){
		echo'<option value="'.$i.'"';
		if($aaaa2==$i){
			echo'selected="selected"';
		}
		echo'>'.$i.'</option>';
	}
?>
                </select>
                <select name="ff2" id="ff2">
                  <option value="01"<?php if("$mm2"=='01'){echo'selected="selected"';}?>>Enero</option>
                  <option value="02"<?php if("$mm2"=='02'){echo'selected="selected"';}?>>Febrero</option>
                  <option value="03"<?php if("$mm2"=='03'){echo'selected="selected"';}?>>Marzo</option>
                  <option value="04"<?php if("$mm2"=='04'){echo'selected="selected"';}?>>Abril</option>
                  <option value="05"<?php if("$mm2"=='05'){echo'selected="selected"';}?>>Mayo</option>
                  <option value="06"<?php if("$mm2"=='06'){echo'selected="selected"';}?>>Junio</option>
                  <option value="07"<?php if("$mm2"=='07'){echo'selected="selected"';}?>>Julio</option>
                  <option value="08"<?php if("$mm2"=='08'){echo'selected="selected"';}?>>Agosto</option>
                  <option value="09"<?php if("$mm2"=='09'){echo'selected="selected"';}?>>Septiembre</option>
                  <option value="10"<?php if("$mm2"=='10'){echo'selected="selected"';}?>>Octubre</option>
                  <option value="11"<?php if("$mm2"=='11'){echo'selected="selected"';}?>>Noviembre</option>
                  <option value="12"<?php if("$mm2"=='12'){echo'selected="selected"';}?>>Diciembre</option>
                </select>
              </strong></td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr>
        <td height="10" nowrap="nowrap"><div align="justify">
          <table border="0">
            <tr>
              <td nowrap="nowrap" class="Estilo2"><div align="right">ESPECIALIDAD</div></td>
              <td width="10" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap"><strong>
                <select name="especialidad" id="especialidad">
                  <option value="0" <?php if($qe=="and especialidad is not null"){echo'selected="selected"';} ?> >TODOS</option>
<?php
	$cons1="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1)){
		echo'<option value="'.$fila1[0].'"';
		if("$especialidad"=="$fila1[0]"){echo'selected="selected"';}
		echo'>'.$fila1[0].'</option>';
	}
?>
                </select>
              </strong></td>
              <td width="10" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap" class="Estilo2">GRUPO</td>
              <td width="10" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap"><strong>
                <select name="grupo" id="grupo">
                  <option value="0" <?php if($qg=="and grupo is not null"){echo'selected="selected"';} ?> >TODOS</option>
<?php
	$cons2="select grupo,codigo from contratacionsalud.gruposservicio where grupo like 'CONSULTA%' and compania='$Compania[0]'";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2)){
		echo'<option value="'.$fila2[1].'"';
		if("$grupo"=="$fila2[1]"){echo'selected="selected"';}
		echo'>'.$fila2[0].'</option>';
	}
?>
                </select>
              </strong></td>
              <td width="10" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap"><div align="right"><strong>PROFESIONAL</strong></div></td>
              <td width="10" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap"><strong>
                <select name="profesional" id="profesional">
                  <option value="0" <?php if($qp=="and medico is not null"){echo'selected="selected"';} ?> >TODOS</option>
<?php
	$cons3="select usuario from salud.medicos where estadomed='Activo' $qe order by usuario";
	echo $cons3;
	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3)){
		$cons4="select nombre from central.usuarios where usuario='$fila3[0]' order by nombre";
		$res4=ExQuery($cons4);
		while($fila4=ExFetch($res4)){
			echo'<option value="'.$fila3[0].'"';
			if("$profesional"=="$fila3[0]"){echo'selected="selected"';}
			echo'>'.$fila4[0].'</option>';
		}
	}
?>
                </select>
              </strong></td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr>
        <td height="10" nowrap="nowrap"><div align="justify">
          <table>
            <tr>
              <td nowrap="nowrap"><strong>TIPO CONSULTA </strong></td>
              <td width="10" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap"><strong>
                <select name="tipo" id="tipo">
                  <option value="" <?php if($qt=="and cup is not null"){echo'selected="selected"';} ?> >TODOS</option>
<?php
	$cons4="select nombre,codigo from contratacionsalud.cups where nombre like 'CONSULTA%' $qg";
	$res4=ExQuery($cons4);
	while($fila4=ExFetch($res4)){
		echo'<option value="'.$fila4[1].'"';
		if("$tipo"=="$fila4[1]"){echo'selected="selected"';}
		echo'>'.$fila4[0].'</option>';
	}
?>
                </select>
              </strong></td>
              </tr>
          </table>
        </div></td>
      </tr>
      <tr>
        <td height="10" nowrap="nowrap"><div align="center"><span class="Estilo2">
          <input name="Enviar" type="button" id="Enviar" onclick="Valida()" value="CONSULTAR" />
          </span>
          <input name="FechaIni" type="hidden" id="FechaIni" />
          <input name="FechaFin" type="hidden" id="FechaFin" />
        </div></td>
      </tr>
      
      <tr>
        <td nowrap="nowrap"><div align="center">
		<?php
			//OPORTUNIDAD GENERAL
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$FechaIni 00:00:00' and '$FechaFin 00:23:59' $qt $qp";
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD GENERAL
			if($base1>0){
				$oportunidadg=number_format($cuentai1/$base1,2);
			}
			//MÁXIMO VALOR
				@$maximo1=max($estadistica);
			//MÍNIMO VALOR
				@$minimo1=min($estadistica);
			//ESCALA
			$pendiente=($maximo1-$minimo1)/(6-1);
			$d1=number_format($pendiente*(1-1)+$minimo,2);
			$d2=number_format($pendiente*(2-1)+$minimo,2);
			$d3=number_format($pendiente*(3-1)+$minimo,2);
			$d4=number_format($pendiente*(4-1)+$minimo,2);
			$d5=number_format($pendiente*(5-1)+$minimo,2);
			$d6=number_format($pendiente*(6-1)+$minimo,2);
			//CONTAR MESES
			switch($mm2){
				case"01":
					$mes_1="02";$mes_2="03";$mes_3="04";$mes_4="05";$mes_5="06";$mes_6="07";$mes_7="08";$mes_8="09";$mes_9="10";$mes_10="11";$mes_11="12";$mes_12="01";
					$cambio_anio=11;					
				break;
				case"02":
					$mes_1="03";$mes_2="04";$mes_3="05";$mes_4="06";$mes_5="07";$mes_6="08";$mes_7="09";$mes_8="10";$mes_9="11";$mes_10="12";$mes_11="01";$mes_12="02";
					$cambio_anio=10;
				break;
				case"03":
					$mes_1="04";$mes_2="05";$mes_3="06";$mes_4="07";$mes_5="08";$mes_6="09";$mes_7="10";$mes_8="11";$mes_9="12";$mes_10="01";$mes_11="02";$mes_12="03";
					$cambio_anio=9;
				break;
				case"04":
					$mes_1="05";$mes_2="06";$mes_3="07";$mes_4="08";$mes_5="09";$mes_6="10";$mes_7="11";$mes_8="12";$mes_9="01";$mes_10="02";$mes_11="03";$mes_12="04";
					$cambio_anio=8;
				break;
				case"05":
					$mes_1="06";$mes_2="07";$mes_3="08";$mes_4="09";$mes_5="10";$mes_6="11";$mes_7="12";$mes_8="01";$mes_9="02";$mes_10="03";$mes_11="04";$mes_12="05";
					$cambio_anio=7;
				break;
				case"06":
					$mes_1="07";$mes_2="08";$mes_3="09";$mes_4="10";$mes_5="11";$mes_6="12";$mes_7="01";$mes_8="02";$mes_9="03";$mes_10="04";$mes_11="05";$mes_12="06";
					$cambio_anio=6;
				break;
				case"07":
					$mes_1="08";$mes_2="09";$mes_3="10";$mes_4="11";$mes_5="12";$mes_6="01";$mes_7="02";$mes_8="03";$mes_9="04";$mes_10="05";$mes_11="06";$mes_12="07";
					$cambio_anio=5;
				break;
				case"08":
					$mes_1="09";$mes_2="10";$mes_3="11";$mes_4="12";$mes_5="01";$mes_6="02";$mes_7="03";$mes_8="04";$mes_9="05";$mes_10="06";$mes_11="07";$mes_12="08";
					$cambio_anio=4;
				break;
				case"09":
					$mes_1="10";$mes_2="11";$mes_3="12";$mes_4="01";$mes_5="02";$mes_6="03";$mes_7="04";$mes_8="05";$mes_9="06";$mes_10="07";$mes_11="08";$mes_12="09";
					$cambio_anio=3;
				break;
				case"10":
					$mes_1="11";$mes_2="12";$mes_3="01";$mes_4="02";$mes_5="03";$mes_6="04";$mes_7="05";$mes_8="06";$mes_9="07";$mes_10="08";$mes_11="09";$mes_12="10";
					$cambio_anio=2;
				break;
				case"11":
					$mes_1="12";$mes_2="01";$mes_3="02";$mes_4="03";$mes_5="04";$mes_6="05";$mes_7="06";$mes_8="07";$mes_9="08";$mes_10="09";$mes_11="10";$mes_12="11";
					$cambio_anio=1;
				break;
				case"12":
					$mes_1="01";$mes_2="02";$mes_3="03";$mes_4="04";$mes_5="05";$mes_6="06";$mes_7="07";$mes_8="08";$mes_9="09";$mes_10="10";$mes_11="11";$mes_12="12";
					$cambio_anio=0;
				break;
			}
			//OPORTUNIDAD MES 1
			$anio_consulta=$aaaa1;
			if($cambio_anio==0){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_1,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_1-01 00:00:00' and '$anio_consulta-$mes_1-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 1
			$oportunidad1=$minimo1;
			if($base1>0){
				$oportunidad1=number_format($cuentai1/$base1,2);				
			}
			
			
			//OPORTUNIDAD MES 2
			$anio_consulta=$aaaa1;
			if($cambio_anio==1){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_2,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_2-01 00:00:00' and '$anio_consulta-$mes_2-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 2
			$oportunidad2=$minimo1;
			if($base1>0){
				$oportunidad2=number_format($cuentai1/$base1,2);				
			}
			
			
			//OPORTUNIDAD MES 3
			$anio_consulta=$aaaa1;
			if($cambio_anio==2){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_3,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_3-01 00:00:00' and '$anio_consulta-$mes_3-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 3
			$oportunidad3=$minimo1;
			if($base1>0){
				$oportunidad3=number_format($cuentai1/$base1,2);				
			}
			

			//OPORTUNIDAD MES 4
			$anio_consulta=$aaaa1;
			if($cambio_anio==3){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_4,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_4-01 00:00:00' and '$anio_consulta-$mes_4-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 4
			$oportunidad4=$minimo1;
			if($base1>0){
				$oportunidad4=number_format($cuentai1/$base1,2);				
			}
			

			//OPORTUNIDAD MES 5
			$anio_consulta=$aaaa1;
			if($cambio_anio==4){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_5,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_5-01 00:00:00' and '$anio_consulta-$mes_5-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 5
			$oportunidad5=$minimo1;
			if($base1>0){
				$oportunidad5=number_format($cuentai1/$base1,2);				
			}
			
			
			//OPORTUNIDAD MES 6
			$anio_consulta=$aaaa1;
			if($cambio_anio==5){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_6,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_6-01 00:00:00' and '$anio_consulta-$mes_6-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 6
			$oportunidad6=$minimo1;
			if($base1>0){
				$oportunidad6=number_format($cuentai1/$base1,2);				
			}
			
			
			//OPORTUNIDAD MES 7
			$anio_consulta=$aaaa1;
			if($cambio_anio==6){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_7,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_7-01 00:00:00' and '$anio_consulta-$mes_7-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 7
			$oportunidad7=$minimo1;
			if($base1>0){
				$oportunidad7=number_format($cuentai1/$base1,2);				
			}



			//OPORTUNIDAD MES 8
			$anio_consulta=$aaaa1;
			if($cambio_anio==7){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_8,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_8-01 00:00:00' and '$anio_consulta-$mes_8-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 8
			$oportunidad8=$minimo1;
			if($base1>0){
				$oportunidad8=number_format($cuentai1/$base1,2);				
			}
			
			
			//OPORTUNIDAD MES 9
			$anio_consulta=$aaaa1;
			if($cambio_anio==8){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_9,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_9-01 00:00:00' and '$anio_consulta-$mes_9-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 9
			$oportunidad9=$minimo1;
			if($base1>0){
				$oportunidad9=number_format($cuentai1/$base1,2);				
			}



			//OPORTUNIDAD MES 10
			$anio_consulta=$aaaa1;
			if($cambio_anio==9){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_10,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_10-01 00:00:00' and '$anio_consulta-$mes_10-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 10
			$oportunidad10=$minimo1;
			if($base1>0){
				$oportunidad10=number_format($cuentai1/$base1,2);				
			}
			
			
			//OPORTUNIDAD MES 11
			$anio_consulta=$aaaa1;
			if($cambio_anio==10){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_11,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_11-01 00:00:00' and '$anio_consulta-$mes_11-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 11
			$oportunidad11=$minimo1;
			if($base1>0){
				$oportunidad11=number_format($cuentai1/$base1,2);				
			}
			
			
			//OPORTUNIDAD MES 12
			$anio_consulta=$aaaa1;
			if($cambio_anio==11){$anio_consulta=$aaaa2;}
			$diafin_1=getMonthDays($mes_12,$anio_consulta);
			$consi1="select fecha,fechacrea from salud.agenda where compania='$Compania[0]' and fechacrea between '$anio_consulta-$mes_12-01 00:00:00' and '$anio_consulta-$mes_12-$diafin_1 00:23:59' $qt $qp";
			//echo $consi1;
			$contador=0;
			$cuentai1=0;
			$resi1=ExQuery($consi1);
			$base1=ExNumRows($resi1);
			while($filai1=ExFetch($resi1)){
				//FECHA1
				$fecha11=substr("$filai1[1]", -19,4);
				$fecha12=substr("$filai1[1]", -14,2);
				if($fecha12<10){$fecha12=str_replace("0","","$fecha12");}
				$fecha13=substr("$filai1[1]", -11,2);
				if($fecha13<10){$fecha13=str_replace("0","","$fecha13");}
				//echo "Fecha1: $fecha11,$fecha12,$fecha13, ";
				//FECHA2
				$filai1[0]="$filai1[0] XX:XX:XX";
				$fecha21=substr("$filai1[0]", -19,4);
				$fecha22=substr("$filai1[0]", -14,2);
				if($fecha22<10){$fecha22=str_replace("0","","$fecha22");}
				$fecha23=substr("$filai1[0]", -11,2);
				if($fecha23<10){$fecha23=str_replace("0","","$fecha23");}
				//echo "Fecha2: $fecha21,$fecha22,$fecha23 --> ";
				//RESTA DE DÍAS
				$ano1 = $fecha11; 
				$mes1 = $fecha12; 
				$dia1 = $fecha13; 
				$ano2 = $fecha21; 
				$mes2 = $fecha22; 
				$dia2 = $fecha23; 
				$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
				$timestamp2 = mktime(23,59,59,$mes2,$dia2,$ano2); 
				$segundos_diferencia = $timestamp1 - $timestamp2; 
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia); 
				$dias_diferencia = floor($dias_diferencia);
				//echo"diferencia: $dias_diferencia </br></br>";
				$cuentai1=$cuentai1+$dias_diferencia;
				$estadistica[$contador]=$dias_diferencia;
				$contador++;		
			}
			//VALOR FINAL OPORTUNIDAD 12
			$oportunidad12=$minimo1;
			if($base1>0){
				$oportunidad12=number_format($cuentai1/$base1,2);				
			}
			
			//PRUEBA OPORTUNIDAD
			
			$tes1=$oportunidad1+$oportunidad2+$oportunidad3+$oportunidad4+$oportunidad5+$oportunidad6+$oportunidad7+$oportunidad8+$oportunidad9+$oportunidad10+$oportunidad11+$oportunidad12;
			$tes1=$tes1/12;
			
			//IMPRESIÓN DE DATOS
									
			//echo"Promedio: $oportunidadg </br>Maximo:$maximo1 </br>Minimo: $minimo1 </br>d1: $d1, d2: $d2, d3: $d3, d4: $d4, d5: $d5, d6: $d6 </br>oportunidad1: $oportunidad1 </br>oportunidad2: $oportunidad2 </br>oportunidad3: $oportunidad3 </br>oportunidad4: $oportunidad4 </br>oportunidad5: $oportunidad5 </br>oportunidad6: $oportunidad6</br>oportunidad7: $oportunidad7</br>oportunidad8: $oportunidad8</br>oportunidad9: $oportunidad9</br>oportunidad10: $oportunidad10</br>oportunidad11: $oportunidad11</br>oportunidad12: $oportunidad12 </br>prueba: $tes1</br>";
			
		?>
          <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="800" height="450">
            <param name="movie" value="APPS/flash/Estadistica001.swf" />
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <param name="menu" value="false" />
            <param name="flashvars" value="oportunidadg=<?php echo $oportunidadg; ?>&amp;maximo1=<?php echo $maximo1; ?>&amp;minimo1=<?php echo $minimo1; ?>&amp;d1=<?php echo $d1; ?>&amp;d2=<?php echo $d2; ?>&amp;d3=<?php echo $d3; ?>&amp;d4=<?php echo $d4; ?>&amp;d5=<?php echo $d5; ?>&amp;d6=<?php echo $d6; ?>&amp;mes_1=<?php echo $mes_1; ?>&amp;mes_2=<?php echo $mes_2; ?>&amp;mes_3=<?php echo $mes_3; ?>&amp;mes_4=<?php echo $mes_4; ?>&amp;mes_5=<?php echo $mes_5; ?>&amp;mes_6=<?php echo $mes_6; ?>&amp;mes_7=<?php echo $mes_7; ?>&amp;mes_8=<?php echo $mes_8; ?>&amp;mes_9=<?php echo $mes_9; ?>&amp;mes_10=<?php echo $mes_10; ?>&amp;mes_11=<?php echo $mes_11; ?>&amp;mes_12=<?php echo $mes_12; ?>&amp;oportunidad1=<?php echo $oportunidad1; ?>&amp;oportunidad2=<?php echo $oportunidad2; ?>&amp;oportunidad3=<?php echo $oportunidad3; ?>&amp;oportunidad4=<?php echo $oportunidad4; ?>&amp;oportunidad5=<?php echo $oportunidad5; ?>&amp;oportunidad6=<?php echo $oportunidad6; ?>&amp;oportunidad7=<?php echo $oportunidad7; ?>&amp;oportunidad8=<?php echo $oportunidad8; ?>&amp;oportunidad9=<?php echo $oportunidad9; ?>&amp;oportunidad10=<?php echo $oportunidad10; ?>&amp;oportunidad11=<?php echo $oportunidad11; ?>&amp;oportunidad12=<?php echo $oportunidad12; ?>&amp;FechaIni=<?php echo $FechaIni; ?>&amp;FechaFin=<?php echo $FechaFin; ?>" />
            <embed src="APPS/flash/Estadistica001.swf" width="800" height="450" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent" menu="false" flashvars="oportunidadg=<?php echo $oportunidadg; ?>&amp;maximo1=<?php echo $maximo1; ?>&amp;minimo1=<?php echo $minimo1; ?>&amp;d1=<?php echo $d1; ?>&amp;d2=<?php echo $d2; ?>&amp;d3=<?php echo $d3; ?>&amp;d4=<?php echo $d4; ?>&amp;d5=<?php echo $d5; ?>&amp;d6=<?php echo $d6; ?>&amp;mes_1=<?php echo $mes_1; ?>&amp;mes_2=<?php echo $mes_2; ?>&amp;mes_3=<?php echo $mes_3; ?>&amp;mes_4=<?php echo $mes_4; ?>&amp;mes_5=<?php echo $mes_5; ?>&amp;mes_6=<?php echo $mes_6; ?>&amp;mes_7=<?php echo $mes_7; ?>&amp;mes_8=<?php echo $mes_8; ?>&amp;mes_9=<?php echo $mes_9; ?>&amp;mes_10=<?php echo $mes_10; ?>&amp;mes_11=<?php echo $mes_11; ?>&amp;mes_12=<?php echo $mes_12; ?>&amp;oportunidad1=<?php echo $oportunidad1; ?>&amp;oportunidad2=<?php echo $oportunidad2; ?>&amp;oportunidad3=<?php echo $oportunidad3; ?>&amp;oportunidad4=<?php echo $oportunidad4; ?>&amp;oportunidad5=<?php echo $oportunidad5; ?>&amp;oportunidad6=<?php echo $oportunidad6; ?>&amp;oportunidad7=<?php echo $oportunidad7; ?>&amp;oportunidad8=<?php echo $oportunidad8; ?>&amp;oportunidad9=<?php echo $oportunidad9; ?>&amp;oportunidad10=<?php echo $oportunidad10; ?>&amp;oportunidad11=<?php echo $oportunidad11; ?>&amp;oportunidad12=<?php echo $oportunidad12; ?>&amp;FechaIni=<?php echo $FechaIni; ?>&amp;FechaFin=<?php echo $FechaFin; ?>"></embed>
          </object>
        </div></td>
      </tr>
    </table>
  </div>
</form>
</body>
</html>
