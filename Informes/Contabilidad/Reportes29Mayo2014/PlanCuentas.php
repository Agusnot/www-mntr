<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");$ND=getdate();
?>
<table border="1" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
		<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>PLAN DE CUENTAS<br>Vigencia <?echo $Anio?></td></tr>
		<tr><td colspan="8" align="right">Fecha de Impresion <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>CUENTA</td><td>NOMBRE</td><td>NAT</td><td>TIPO</td><td>CC</td><td>BANC</td><td>TERC</td><td>PRESUP</td></tr>
<?
	$cons="Select Cuenta,Nombre,Naturaleza,Tipo,centrocostos,Banco,Tercero,afectacionpresup from Contabilidad.PlanCuentas where Anio=$Anio and Compania='$Compania[0]' Order By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td><td>$fila[6]</td><td>$fila[7]</td></tr>";
	}
?>
</table>