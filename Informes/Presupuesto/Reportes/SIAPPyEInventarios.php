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
	<?echo $Compania[1]?><br>S.I.A. Propiedad, Planta y Equipo - Inventario<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo Contable</td><td>Nombre Cuenta</td><td>Saldo Inicial</td><td>Entradas</td><td>Salidas</td></tr>
<?


	$consCta="Select Cuenta,Nombre from Contabilidad.PlanCuentas 
	where Cuenta like '16%' and Compania='$Compania[0]' and Anio=$Anio and Tipo='Detalle' and Cuenta not like '1685%'
	Order By Cuenta";
	$resCta=ExQuery($consCta);
	while($filaCta=ExFetch($resCta))
	{

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Fecha<='$Anio-$MesIni-01' and Estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$SaldoIni=$fila[0]-$fila[1];if(!$SaldoIni){$SaldoIni=0;}

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Entradas=$fila[0];if(!$Entradas){$Entradas=0;}
		$Salidas=$fila[1];if(!$Salidas){$Salidas=0;}

		echo "<tr><td>$filaCta[0]</td><td>$filaCta[1]</td><td align='right'>".number_format($SaldoIni,2)."</td><td align='right'>".number_format($Entradas,2)."</td><td align='right'>".number_format($Salidas,2)."</td></tr>";
		$Archivo=$Archivo."$filaCta[0],$filaCta[1],$SaldoIni,$Entradas,$Salidas<br>";
	}

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("formato_200801_f05b_agr.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td><a href='formato_200801_f05b_agr.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";

?>

