<?
	session_start();
	$ND=getdate();
	$Ahora="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]";
	mysql_select_db("salud", $conex);

	function VerificaVal($CodConsulta,$Msj,$Cedula,$Fondo)
	{
		echo "<tr bgcolor='$Fondo'><td>$Msj</td>";
		$cons="Select * from notasevolucion where cedula='$Cedula' and CodConsulta='$CodConsulta' order by Fecha desc";
		$res=ExQuery($cons);
		if(mysql_num_rows($res)==0)
		{
			echo "<td colspan=2><center><font color='#ff0000'>Nunca</font></td>";
		}
		else
		{
			$fila=ExFetch($res);
			echo "<td>$fila[0] - Dr. $fila[1]</td>";
			$HaceCuanto=Diferencia($Ahora,$fila[0]);
			echo "<td>$HaceCuanto</td>";
		}
	}

	function NoAtenciones($CodConsulta,$Msj,$Cedula,$Fondo,$Inicio,$Fin)
	{
		$cons="SELECT CodConsulta,Cedula,Count(CodConsulta) AS Cue
		FROM notasevolucion
		WHERE (Fecha>='$Inicio' and Fecha<='$Fin') GROUP BY CodConsulta,Cedula
		having cedula=$Cedula and CodConsulta=$CodConsulta";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		return $fila[2];
	}

	function Frecuencia($NoDias,$NoValorac)
	{
		$F1=round($NoValorac/$NoDias,2);$MsjAdc="x dia";
		if($F1<1){$NoDias=$NoDias/30;$MsjAdc=" x mes";}
		$F1=round($NoValorac/$NoDias,2);
		if($F1<1){$NoDias=$NoDias/12;$MsjAdc=" x año";}
		$F1=round($NoValorac/$NoDias,2);
		return "ø $F1 valoraciones $MsjAdc";
	}
	function Diferencia($Fecha1,$Fecha2)
	{
		$date1=$Fecha1;
		$date2=$Fecha2;
		
		$s = strtotime($date1)-strtotime($date2);
		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;
		$dif2= $d." " .dias." ".$h.hrs." ".$m."min";
		return $dif2;
	}
	function Diferencia2($Fecha1,$Fecha2)
	{
		$date1=$Fecha1;
		$date2=$Fecha2;
		
		$s = strtotime($date1)-strtotime($date2);
		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;
		$dif2= $d;
		return $dif2;
	}

?>
Ultimas valoraciones Intrahospitalarias:
<table border="1" style='font : normal normal small-caps 12px Tahoma;'>
<tr align="center" bgcolor="#c0c0c0"><td>Tipo Atencion</td><td>Ultima vez</td><td>Hace</td></tr>

<?
	if(!$Cedula){$Cedula=$Paciente[1];}

	VerificaVal(39131,'Medicina General',$Cedula,'');
	VerificaVal(39143,'Psiquiatria',$Cedula,'#E5E5E5');
	VerificaVal(35102,'Psicologia',$Cedula,'');
	VerificaVal(37601,'Nutrición y dietetica',$Cedula,'#E5E5E5');

		echo "<tr><td>Odontologia</td>";
		$cons="Select * from TtosEjecutadosOdontologia where cedula='$Cedula' order by Fecha desc";
		$res=ExQuery($cons);
		if(mysql_num_rows($res)==0)
		{
			echo "<td colspan=2><center><font color='#ff0000'>Nunca</font></td>";
		}
		else
		{
			$fila=ExFetch($res);
			echo "<td>$fila[0] - Dr. $fila[1]</td>";
			$HaceCuanto=Diferencia($Ahora,$fila[0]);
			echo "<td>$HaceCuanto</td>";
		}
?>
</td>
</tr>
</table>
<iframe src="/HistoriaClinica/Operacion/GraficoValoracionesxPte.php" width="560" height="200" frameborder="0"></iframe>
<br>
Frecuencia de atención en su ultima hospitalización

<?
	$cons="Select * from hospitalizacion where cedula='$Cedula' Order By IdHospitalizacion Desc";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$FechaIniHospi="$fila[3]";
	if($FechaIniHospi<'2004-06-25'){$FechaIniHospi='2004-06-25';}
	if($fila[7]>0){$FechaFinHospi="$fila[7]";}
	else{$DiaFinHospi=$ND[mday]+1;$FechaFinHospi="$ND[year]/$ND[mon]/$DiaFinHospi";}
	echo "<table border=1><tr bgcolor='#c0c0c0'><td>Concepto</td><td>Frecuencia</td>";

	$NoDiasHospi=Diferencia2($FechaFinHospi,$FechaIniHospi);
	$NoVeces=NoAtenciones(39131,'Medicina General',$Cedula,'',$FechaIniHospi,$FechaFinHospi);
	$Frec=Frecuencia($NoDiasHospi,$NoVeces);
	echo "<tr bgcolor='$Fondo'><td>Medicina General</td><td>$Frec</td>";
	
	$NoVeces=NoAtenciones(39143,'Psiquiatria',$Cedula,'',$FechaIniHospi,$FechaFinHospi);
	$Frec=Frecuencia($NoDiasHospi,$NoVeces);
	echo "<tr bgcolor='#E5E5E5'><td>Psiquiatria</td><td>$Frec</td>";
	
	$NoVeces=NoAtenciones(35102,'Psiquiatria',$Cedula,'',$FechaIniHospi,$FechaFinHospi);
	$Frec=Frecuencia($NoDiasHospi,$NoVeces);
	echo "<tr bgcolor='$Fondo'><td>Psicologia</td><td>$Frec</td>";

	$NoVeces=NoAtenciones(37601,'Nutricion',$Cedula,'',$FechaIniHospi,$FechaFinHospi);
	$Frec=Frecuencia($NoDiasHospi,$NoVeces);
	echo "<tr bgcolor='#E5E5E5'><td>Nutrición y dietetica</td><td>$Frec</td>";

	echo "</table>";

	
?>

