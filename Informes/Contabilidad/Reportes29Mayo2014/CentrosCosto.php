<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	$ND=getdate();
?>
<table border="1" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
		<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>CENTROS DE COSTO<br>Vigencia <?echo $Anio?></td></tr>
		<tr><td colspan="8" align="right">Fecha de Impresion <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>CODIGO</td><td>CENTRO</td><td>TIPO</td></tr>
<?
	$cons="Select codigo,centrocostos,tipo from central.centroscosto where Anio=$Anio and Compania='$Compania[0]' Order By Codigo";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>";
		if($fila[2]=="Titulo")
		{
			echo "<strong>" . strtoupper($fila[1]);
		}
		else
		{
			echo "<ul>".$fila[1];
		}
		echo "</td><td>$fila[2]</td></tr>";
	}
?>
</table>