<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body background="/Imgs/Fondo.jpg">
<table bordercolor="#e5e5e5" border="1"  cellpadding="2" cellspacing="1"style='font : normal normal small-caps 12px Tahoma;' align="center">	
<?
	if($Tipo==1){?>
		<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    		<td colspan="5">PACIENTES CON REINGRESOS DESPUES DE 24 HORAS</td>
	  	</tr>	
<?	}
	elseif($Tipo==2){?>
		<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    		<td colspan="5">PACIENTES CON REINGRESOS ANTES DE 24 HORAS</td>
	  	</tr>
<?	}?>
  	<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Identificacion</td><td>Nombre</td><td>Ambito</td><td>Fecha Ingreso</td><td>Fecha Egreso</td>
  	</tr>
<?	$cons="select cedula,count(cedula) from salud.servicios,salud.ambitos 
	where fechaing>='$FechaIni 00:00:00' and fechaing<='$FechaFin 23:59:59'
	and ambito=tiposervicio and hospitalizacion=1 and ambitos.compania='$Compania[0]' and servicios.compania='$Compania[0]' $Amb
	group by cedula having(count(cedula)>1) order by cedula";
	$res=ExQuery($cons);
	//echo $cons;
	$contIngMay42=0; $contIngMen42=0;
	while($fila=ExFetch($res))
	{
		//echo "$fila[0] <br>";
		$BanServ=0;
		$BanMay24=0;
		$BanMen24=0;
		$cons2="select numservicio,fechaing,cedula,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom),tiposervicio,fechaing,fechaegr
		from salud.servicios,salud.ambitos,central.terceros
		where fechaing>='$FechaIni 00:00:00' 
		and fechaing<='$FechaFin 23:59:59' and ambito=tiposervicio and hospitalizacion=1 and ambitos.compania='$Compania[0]' and servicios.compania='$Compania[0]'
		and cedula='$fila[0]' and terceros.compania='$Compania[0]' and identificacion=cedula order by numservicio";	
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{
			if($BanServ==0){
				$FechaAnt=$fila2[1];				
				$BanServ=1;
			}
			else
			{				
				$FeAnterior=explode(" ",$FechaAnt);
				$FAnt=explode("-",$FeAnterior[0]);
				$MAnt=explode(":",$FeAnterior[1]);
				
				$FeActual=explode(" ",$fila2[1]);
				$FAct=explode("-",$FeActual[0]);
				$MAct=explode(":",$FeActual[1]);
	
				//  (hora,minuto,segundo,dia,mes,año)
				$fecha1 = mktime($MAnt[0],$MAnt[1],$MAnt[2],$FAnt[2],$FAnt[1],$FAnt[0]); 
				$fecha2 = mktime($MAct[0],$MAct[1],$MAct[2],$FAct[2],$FAct[1],$FAct[0]); 
				$diferencia = $fecha2-$fecha1; 
				$diff= abs((int)($diferencia/(60*60))); 
				if($diff<48){$BanMen24=1;}
				else{$BanMay24=1;}
				//echo "FechaAnt=$FechaAnt FechaActual=$fila2[1] HoradDif=$diff<br>";
			}
			if($BanMen24==1)
			{
				$contIngMen42++;				
				if($Tipo==2){
					echo "<tr><td>$fila2[2]</td><td>$fila2[3]</td><td>$fila2[4]</td><td>$fila2[5]</td><td>$fila2[6]&nbsp;</td></tr>";
					//$PacsMay42[$fila2[0]]=array($fila2[0],$fila[0]);
				}
			}
			if($BanMay24==1){				
				$contIngMay42++;
				if($Tipo==1){
					echo "<tr><td>$fila2[2]</td><td>$fila2[3]</td><td>$fila2[4]</td><td>$fila2[5]</td><td>$fila2[6]&nbsp;</td></tr>";
					//$PacsMay42[$fila2[0]]=array($fila2[0],$fila[0]);
				}
			}
		}
	}?>
</table>    
</body>
</html>