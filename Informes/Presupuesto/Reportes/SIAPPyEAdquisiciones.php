<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();

	$cons="Select NumDias from Central.Meses where Numero=$MesFin";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$UltDia=$fila[0];
?>

	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Propiedad, Planta y Equipo - Aquisiciones y Bajas<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Fecha de Adquisición o Baja</td><td>Concepto</td><td>Valor</td><td>Detalle</td><td>Codigo Contable</td></tr>
<?


	$consCta="Select Fecha,Cuenta,Debe,Haber,Detalle from Contabilidad.Movimiento
	where Cuenta like '16%' and Cuenta NOT LIKE '1685%' and Compania='$Compania[0]' and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'
	Order By Cuenta";
	$resCta=ExQuery($consCta);echo ExError();
	while($filaCta=ExFetch($resCta))
	{
		$Fecha=$filaCta[0];
		$Fecha=str_replace("-","/",$Fecha);
		$Concepto=str_replace(",","",$Concepto);
		if($filaCta[2]>0){$Concepto="Adquisicion";$Valor=$filaCta[2];}
		if($filaCta[3]>0){$Concepto="Baja";$Valor=$filaCta[3];}
		echo "<tr><td>$Fecha</td><td>$Concepto</td><td align='right'>".number_format($Valor,2)."</td><td>$filaCta[4]</td><td>$filaCta[1]</td></tr>";
		$Archivo=$Archivo."$Fecha,$Concepto,$Valor,$filaCta[4],$filaCta[1]<br>";
	}

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("formato_200801_f05a_agr.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td><a href='formato_200801_f05a_agr.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";

?>

