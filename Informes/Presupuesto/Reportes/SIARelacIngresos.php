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
	<?echo $Compania[1]?><br>S.I.A. Relación de Ingresos<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo Presupuestal</td><td>Fecha de Recaudo</td><td>Número de Recibo</td><td>Recibido De</td><td>Concepto Recaudo</td><td>Valor</td><td>Cuenta Bancaria</td><td>Cta Contable</tr>
<?
	$cons1="Select Comprobante,Numero,Cuenta from Contabilidad.Movimiento where Cuenta like '11%' and Compania='$Compania[0]'
	and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia' and Estado='AC'";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		$ReciboCaja[$fila1[0]][$fila1[1]]=$fila1[2];
	}

	$cons="Select Movimiento.Cuenta,Fecha,NoDocOrigen,Detalle,ContraCredito,Identificacion,DocOrigen,SIA,DocOrigen,NoDocOrigen from Presupuesto.Movimiento, 
	Presupuesto.Comprobantes,Presupuesto.PlanCuentas where Movimiento.Comprobante=Comprobantes.Comprobante and TipoComprobant='Ingreso presupuestal' 
	and Movimiento.Compania='$Compania[0]'
	and PlanCuentas.Cuenta=Movimiento.Cuenta and PlanCuentas.Compania='$Compania[0]' and PlanCuentas.Anio=$Anio and 
	Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia'
	and Comprobantes.Compania='$Compania[0]' and Estado='AC' Order By Fecha";

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$CuentaCont=$ReciboCaja[$fila[6]][$fila[2]];
		$fila[1]=str_replace("-","/",$fila[1]);
		$fila[3]=str_replace(",","",$fila[3]);
		$cons1="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila[5]'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);

		echo "<tr><td>'<strong>$fila[7]</strong>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila1[0] $fila1[1] $fila1[2] $fila1[3]</td><td>$fila[3]</td><td align='right'>".number_format($fila[4],2)."</td><td>$BancoRec</td><td>$CuentaCont</td></tr>";
		$Archivo=$Archivo."$fila[0],$fila[1],$fila[2],$fila1[0] $fila1[1] $fila1[2] $fila1[3],$fila[3],$fila[4],$BancoRec<br>";
	}

//	$Archivo=str_replace("<br>","\r\n",$Archivo);
//	$fichero = fopen("formato_200801_f06a_cdn.csv", "w+") or die('Error de apertura');
//	fwrite($fichero, $Archivo);	
//	fclose($fichero);

	echo "<tr><td colspan=3><a href='formato_200801_f06a_cdn.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";

?>
