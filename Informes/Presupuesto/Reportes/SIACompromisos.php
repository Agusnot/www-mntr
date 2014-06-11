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
	<tr><td colspan="10"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Relación de Compromisos<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="10" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo Presupuestal</td><td>Nombre Rubro</td><td>Numero de CDP</td><td>Fecha de CDP</td><td>Valor de CDP</td><td>Fecha de Registro</td><td>Valor Registro</td><td>Beneficiario</td><td>Cedula o NIT</td><td>Detalle</td></tr>
<?

	$cons2="Select Comprobante,Numero,Fecha,sum(Credito) from Presupuesto.Movimiento where Estado='AC' 
	and Compania='$Compania[0]' and date_part('year',Fecha)=$Anio and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia' 
	Group By Comprobante,Numero,Fecha";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$CompRelacionado[$fila2[0]][$fila2[1]]=array($fila2[2],$fila2[3]);
	}


	$cons="Select Movimiento.Cuenta,PlanCuentas.Nombre,Fecha,Credito,PrimApe,SegApe,PrimNom,SegNom,Movimiento.Identificacion,Detalle,Movimiento.Comprobante,Numero,ContraCredito,CompAfectado,DocSoporte,SIA
	from Presupuesto.Movimiento,Central.Terceros,Presupuesto.PlanCuentas,Presupuesto.Comprobantes
	where Comprobantes.Comprobante=Movimiento.Comprobante and Movimiento.Cuenta=PlanCuentas.Cuenta 
	and Movimiento.Identificacion=Terceros.Identificacion
	
	and date_part('year',Fecha)=$Anio and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia'
	and PlanCuentas.Anio=$Anio and Movimiento.Estado='AC' and 
	(Comprobantes.TipoComprobant='Compromiso presupuestal' Or Comprobantes.TipoComprobant='Disminucion a compromiso')
	and PlanCuentas.Vigencia='Actual'
	and Movimiento.Compania='$Compania[0]' 
	and PlanCuentas.Compania='$Compania[0]'
	and Terceros.Compania='$Compania[0]' and Movimiento.Vigencia='Actual'
	Order By Numero";

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$FechaCDP=$CompRelacionado[$fila[13]][$fila[14]][0];
		$ValorCDP=$CompRelacionado[$fila[13]][$fila[14]][1];
		if($fila[12]){$fila[3]=$fila[12]*-1;}
		echo "<tr><td><strong>$fila[15]</strong>$fila[0]</td><td>$fila[1]</td><td>$fila[14]</td><td>$FechaCDP</td><td align='right'>".number_format($ValorCDP,2)."</td><td>$fila[2]</td><td align='right'>".number_format($fila[3],2)."</td><td>$fila[4] $fila[5] $fila[6] $fila[7]</td><td>$fila[8]</td><td>$fila[9]</td></tr>";
		$Archivo=$Archivo."\"$fila[15]$fila[0]\" $Separador $fila[1] $Separador $fila[14] $Separador $FechaCDP $Separador $ValorCDP $Separador $fila[2] $Separador $fila[3] $Separador $fila[4] $fila[5] $fila[6] $fila[7] $Separador $fila[8] $Separador $fila[9]\n";
	}

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("SIARelacCompromisos.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td colspan=3><a href='SIARelacCompromisos.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";

?>
