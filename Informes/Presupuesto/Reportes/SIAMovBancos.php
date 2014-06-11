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
	<tr><td colspan="12"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Movimiento de Bancos<br>Periodo: <?echo "$NombreMes[$MesIni] a $NombreMes[$MesFin] de $Anio"?></td></tr>
	<tr><td colspan="12" align="right">Fecha de Impresi&oacute;n <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Banco</td><td>No. de Cuenta</td><td>Denominacion</td><td>Fte de Financiacion</td><td>Saldo a 1 de Enero</td><td>Ingresos</td><td>Egresos</td><td>Notas Debito</td><td>Notas Credito</td><td>Saldo a 31 de Dic x Libros</td><td>Saldo a 31 de Dic x Extractos</td></tr>
<?

	$Archivo="Banco $Separador No Cuenta $Separador Denominacion $Separador Fte Financiacion $Separador Saldo 1o Ene $Separador Ingresos $Separador Egresos $Separador Mov Debe $Separador Mov Haber $Separador Saldo Final $Separador Saldo Extractos\n";

	$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,NomBanco,NumCuenta,Destinacion,Nombre,FteFinanciacion from Contabilidad.PlanCuentas 
	where Banco=1 and Compania='$Compania[0]' and Anio=$Anio
	Order By Cuenta";
	$resCta=ExQuery($consCta);
	while($filaCta=ExFetch($resCta))
	{

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Fecha<'$Anio-01-01' and Estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Saldo1Ene=$fila[0]-$fila[1];

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento,Contabilidad.Comprobantes 
		where Cuenta='$filaCta[0]' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Comprobantes.Comprobante=Movimiento.Comprobante 
		and (Comprobantes.TipoComprobant='Ingreso' Or Comprobantes.Comprobante='Consignacion Bancaria')
		and  Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'";
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res);
		$Ingresos=$fila[0];

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento,Contabilidad.Comprobantes 
		where Cuenta='$filaCta[0]' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Comprobantes.Comprobante=Movimiento.Comprobante 
		and (Comprobantes.TipoComprobant='Egreso')
		 and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'";
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res);
		$Egresos=$fila[1];

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento,Contabilidad.Comprobantes 
		where Cuenta='$filaCta[0]' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Comprobantes.Comprobante=Movimiento.Comprobante 
		and (Comprobantes.TipoComprobant!='Ingreso' And Comprobantes.Comprobante!='Consignacion Bancaria')
		 and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'";
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res);
		$MovDebe=$fila[0];

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento,Contabilidad.Comprobantes 
		where Cuenta='$filaCta[0]' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Comprobantes.Comprobante=Movimiento.Comprobante 
		and (Comprobantes.TipoComprobant!='Egreso')
		 and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'";
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res);
		$MovHaber=$fila[1];


		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$SaldoF=$fila[0]-$fila[1];

		$cons="Select SaldoExtracto from Contabilidad.SaldosConciliacion where Compania='$Compania[0]' and Cuenta='$filaCta[0]' and Anio=$Anio and Mes=12";
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res);
		$SaldoExt=$fila[0];


		if($Saldo1Ene || $Ingresos || $Egresos || $MovDebe || $MovHaber || $MovHaber || $SaldoF || $SaldoExt)
		{
			echo "<tr><td>$filaCta[4]</td><td>$filaCta[5]</td><td>$filaCta[7]</td><td>$filaCta[8]</td><td align='right'>".number_format($Saldo1Ene,2)."</td><td align='right'>".number_format($Ingresos,2)."</td><td align='right'>".number_format($Egresos,2)."</td><td align='right'>".number_format($MovDebe,2)."</td><td align='right'>".number_format($MovHaber,2)."</td><td align='right'>".number_format($SaldoF,2)."</td><td align='right'>".number_format($SaldoExt,2)."</td></tr>";
			$Archivo=$Archivo."$filaCta[4] $Separador $filaCta[5] $Separador $filaCta[7] $Separador $filaCta[8] $Separador $Saldo1Ene $Separador $Ingresos $Separador $Egresos $Separador $MovDebe $Separador $MovHaber $Separador $SaldoF $Separador $SaldoExt\n";
		}
	}

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("SIAMovBancos.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td colspan=7><a href='SIAMovBancos.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";

?>

