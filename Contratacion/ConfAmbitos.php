<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto WHERE Compania='$Compania[0]' and Anio=$ND[year] Order By Codigo";
	//echo $cons."<br>";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$CentCost[$fila[0]]=$fila[1];
	}
	if($Eliminar)
	{
		$cons="Delete from Salud.Ambitos where Ambito='$Ambito' and consultaextern=$ConsExt and hospitalizacion=$Hospitalizacion and hospitaldia=$HospitalDia and pyp=$PyP and urgencias=$Urgencias and Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();
	}
	$result=ExQuery("Select * from Salud.Ambitos where Compania='$Compania[0]' order by ambito");
?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<TR bgcolor="#e5e5e5" style="font-weight:bold">
		<TD>Proceso</TD><td>Consulta Externa</td><td>Hospitalizacion</td><td>Hospital Dia</td><td>PyP</td><td>Urgencias</td><td>Centro De Costos</td><td colspan="2"></td>
	</TR>
<?php 
	while($row = ExFetchArray($result))
	{ 
		
		if($row[2]==1){$ConsExt='Si';}else{$ConsExt='No';}
		if($row[3]==1){$Hosp='Si';}else{$Hosp='No';}
		if($row[4]==1){$HospDia='Si';}else{$HospDia='No';}
		if($row[5]==1){$Pyp='Si';}else{$Pyp='No';}
		if($row[6]==1){$Urgen='Si';}else{$Urgen='No';}
		if($row[0]!='Sin Ambito'){
			echo "<tr align='center'><td>".$row['ambito']."</td><td align='center'>$ConsExt</td><td>$Hosp</td><td>$HospDia</td><td>$Pyp</td><td>$Urgen</td>
			<td>".$row['centrocostos']." - ".$CentCost[$row['centrocostos']]."</td><td>";?>
			<img src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfAmbitos.php?DatNameSID=<? echo $DatNameSID?>&ConsExterna=<? echo $row[2]?>&Hospitalizacion=<? echo $row['hospitalizacion']?>&HospitalDia=<? echo $row['hospitaldia']?>&PyP=<? echo $row['pyp']?>&Urgencias=<? echo $row['urgencias']?>&Edit=1&Ambito=<? echo $row['ambito']?>'"></td><td>
			<img style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfAmbitos.php?DatNameSID=<? echo $DatNameSID?>&ConsExt=<? echo $row[2]?>&Hospitalizacion=<? echo $row['hospitalizacion']?>&HospitalDia=<? echo $row['hospitaldia']?>&PyP=<? echo $row['pyp']?>&Urgencias=<? echo $row['urgencias']?>&Eliminar=1&Ambito=<? echo $row['ambito']?>';}" src="/Imgs/b_drop.png"></td></tr>
<?		} 
	}
?>
</table><br>
<input type="button" onClick="location.href='NewConfAmbitos.php?DatNameSID=<? echo $DatNameSID?>'" value="Nuevo">

</body>
</html>
