<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="Select NumDias from Central.Meses where Numero=$MesFin";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$UltDia=$fila[0];
	$Separador=";";
?>

	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Relaci&oacute;n de Cuentas Bancarias<br>Periodo: <?echo "$NombreMes[$MesIni] a $NombreMes[$MesFin] de $Anio"?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresi&oacute;n <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Banco</td><td>Codigo Contable</td><td>No. de Cuenta</td><td>Destinaci&oacute;n</td><td>Ingresos</td><td>Saldo Libros</td><td>Saldo Extractos</td><td>Saldo Tesoreria</td></tr>
<?

	$Archivo="Banco $Separador Codigo Contable $Separador No de Cuenta $Separador Destinacion $Separador Ingresos $Separador Saldo Libros $Separador Saldo Extractos $Separador Saldo Tesoreria\n";

	$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,NomBanco,NumCuenta,Destinacion from Contabilidad.PlanCuentas 
	where Banco=1 and Compania='$Compania[0]' and Anio=$Anio
	Order By Cuenta";
	$resCta=ExQuery($consCta);
	while($filaCta=ExFetch($resCta))
	{

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$SaldoF=$fila[0]-$fila[1];

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$MovDebe=$fila[0];

		$cons="Select SaldoExtracto from Contabilidad.SaldosConciliacion where Compania='$Compania[0]' and Cuenta='$filaCta[0]' and Anio=$Anio and Mes=$MesFin";
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res);
		$SaldoExt=$fila[0];
		
		if($SaldoF || $MovDebe || $SaldoExt)
		{
			echo "<tr><td>$filaCta[4]</td><td>$filaCta[0]</td><td>$filaCta[5]</td><td>$filaCta[6]</td><td align='right'>".number_format($MovDebe,2)."</td><td align='right'>".number_format($SaldoF,2)."</td><td align='right'>".number_format($SaldoExt,2)."</td><td align='right'>".number_format($SaldoF,2)."</td></tr>";
			$Archivo=$Archivo."$filaCta[4] $Separador $filaCta[0] $Separador $filaCta[5] $Separador $filaCta[6] $Separador $MovDebe $Separador $SaldoF $Separador $SaldoExt $Separador $SaldoF\n";
		}
	}
	$fichero = fopen("SIACtasBancarias.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td colspan=7><a href='SIACtasBancarias.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";

?>

