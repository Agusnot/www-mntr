<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg"><?
if($Anio!=''&&$MesFin!=''&&$DiaIni!=''&&$DiaFin!=''){
	if($Ambito){$Amb=" and tiposervicio='$Ambito'";$Amb2=" and ambito='$Ambito'";}
	$cons="select count(servicios.numservicio)
	from central.terceros,salud.servicios
	where terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and identificacion=servicios.cedula and servicios.fechaing>='$Anio-$MesIni-$DiaIni' and 	
	servicios.fechaing<='$Anio-$MesFin-$DiaFin' $Amb";	
	//echo $cons;	
	$res=ExQuery($cons);
	$contIng=0;
	if(ExNumRows($res)>0){
		$fila=ExFetch($res);
		$contIng=$fila[0];		
	}
	$cons="select count(servicios.numservicio)
	from central.terceros,salud.servicios
	where terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and identificacion=servicios.cedula and servicios.fechaegr>='$Anio-$MesIni-$DiaIni' and 	
	servicios.fechaegr<='$Anio-$MesFin-$DiaFin' $Amb";	
	$res=ExQuery($cons);
	$contEgr=0;
	if(ExNumRows($res)>0){
		$fila=ExFetch($res);
		$contEgr=$fila[0];		
	}
	$cons="select sum(nocamas) from salud.pabellones where compania='$Compania[0]' $Amb2";
	$res=ExQuery($cons);
	$fila=ExFetch($res); $NoCamas=$fila[0];
	$timestamp1 = mktime(0,0,0,$MesIni,$DiaIni,$Anio); 
	$timestamp2 = mktime(0,0,0,$MesFin,$DiaFin,$Anio); 
	$segundos_diferencia = $timestamp1 - $timestamp2;
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
	$dias_diferencia = abs($dias_diferencia); 
	$dias_diferencia = floor($dias_diferencia);
	$dias_diferencia++;
	$contCamasDisp=$dias_diferencia*$NoCamas;	
	
	$cons="select sum(numpacientes) from salud.censogeneral where compania='$Compania[0]' and dia>='$Anio-$MesIni-$DiaIni' and dia<='$Anio-$MesFin-$DiaFin' $Amb2";
	$res=ExQuery($cons);
	$fila=ExFetch($res); 
	$contPacientes=$fila[0];
	$Ocupacionalidad=($contPacientes*100)/$contCamasDisp;
	
    $cons="select cedula,count(cedula) from salud.servicios,salud.ambitos where fechaing>='$Anio-$MesIni-$DiaIni 00:00:00' and fechaing<='$Anio-$MesFin-$DiaFin 23:59:59'
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
		$cons2="select numservicio,fechaing from salud.servicios,salud.ambitos where fechaing>='$Anio-$MesIni-$DiaIni 00:00:00' 
		and fechaing<='$Anio-$MesFin-$DiaFin 23:59:59' and ambito=tiposervicio and hospitalizacion=1 and ambitos.compania='$Compania[0]' and servicios.compania='$Compania[0]'
		and cedula='$fila[0]' order by numservicio";	
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
				$PacsMen42[$fila2[0]]=array($fila2[0],$fila[0]);
			}
			if($BanMay24==1){				
				$contIngMay42++;
				//$PacsMay42[$fila2[0]]=array($fila2[0],$fila[0]);
			}
		}
	}
	//echo "contIngMayores a 42=$contIngMay42 contIngMenores a 42=$contIngMen42<br>";
	?>
    
    <table bordercolor="#e5e5e5" border="1"  cellpadding="2" cellspacing="1"style='font : normal normal small-caps 12px Tahoma;' align="center">	
    	<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>Indicador</td><td>No</td></tr>
        <tr><td>Ingresos del Periodo</td><td align="center"><? echo $contIng?></td></tr>
       	<tr><td>Egresos del Periodo</td><td align="center"><? echo $contEgr?></td></tr>
        <tr><td>No de Camas Disponibles</td><td align="center"><? echo $contCamasDisp?></td></tr>
        <tr><td>No de Camas Ocupadas</td><td align="center"><? echo $contPacientes?></td></tr>
        <tr><td>Ocupacionalidad</td><td align="center"><? echo round($Ocupacionalidad,2)."% &nbsp;($contPacientes / $contCamasDisp)"?></td></tr>
        <tr><td>Reingresos despues de 24 horas</td>
        	<td align="center"<? 
			if($contIngMay42>0)
			{?> 
            	style="cursor:hand" title="Ver Listado"
				onClick="open('/Estadistica/VerIngMay42.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&FechaIni=<? echo "$Anio-$MesIni-$DiaIni"?>&FechaFin=<? echo "$Anio-$MesFin-$DiaFin"?>&Tipo=1','','width=800,height=600')"<? }?>>
			<? echo $contIngMay42?></td>
       	</tr>
        <tr><td>Reingresos antes de 24 horas</td>
        	<td align="center"<? 
			if($contIngMen42>0)
			{?> 
            	style="cursor:hand" title="Ver Listado"
				onClick="open('/Estadistica/VerIngMay42.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&FechaIni=<? echo "$Anio-$MesIni-$DiaIni"?>&FechaFin=<? echo "$Anio-$MesFin-$DiaFin"?>&Tipo=2','','width=800,height=600')"<? 
			}?>>
			<? echo $contIngMen42?></td>
       	</tr>
    </table>
<?
}?>
</body>
</html>
