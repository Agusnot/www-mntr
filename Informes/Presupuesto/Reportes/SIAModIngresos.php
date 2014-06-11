<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>

	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Modificaciones al Presupuesto de Ingresos<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo Presupuestal</td><td>Acto Administrativo</td><td>Fecha</td><td>Adición</td><td>Reducción</td></tr>
<?
	$cons="Select Movimiento.Cuenta,SIA,Detalle,Fecha,ContraCredito,Credito from Presupuesto.Movimiento,Presupuesto.Comprobantes,Presupuesto.PlanCuentas 
	where Movimiento.Comprobante=Comprobantes.Comprobante and PlanCuentas.Cuenta=Movimiento.Cuenta and Estado='AC' and PlanCuentas.Anio=$Anio and Movimiento.Anio=$Anio
	and PlanCuentas.Compania='$Compania[0]'
	and Movimiento.Compania='$Compania[0]'
	and PlanCuentas.Vigencia='Actual'
	 and Comprobantes.Compania='$Compania[0]' and Estado='AC' and (TipoComprobant='Adicion' Or TipoComprobant='Reduccion') and Movimiento.Cuenta like '1%'";

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$fila[2]=str_replace(",","",$fila[2]);
		$fila[3]=str_replace("-","/",$fila[3]);
		echo "<tr><td>'<strong>$fila[1]</strong>$fila[0]</td><td>$fila[2]</td><td>$fila[3]</td><td align='right'>".number_format($fila[4],2)."</td><td align='right'>".number_format($fila[5],2)."</td></tr>";
		$Archivo=$Archivo."$fila[1]$fila[0],$fila[2],$fila[3],$fila[4],$fila[5]<br>";
	}
	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("formato_200801_f08a_agr.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td colspan=3><a href='formato_200801_f08a_agr.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";
