<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();

	include("Funciones.php");
	include("GeneraValoresEjecucion.php");

	$cons="Select * from Central.Meses where Numero=$MesIni";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesIniLet=$fila[0];
	$cons="Select * from Central.Meses where Numero=$MesFin";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesFinLet=$fila[0];
	$UltDia=$fila[2];
	$ND=getdate();


?>
	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="15"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Relación de Pagos<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="15" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr><td>Fecha de Pago</td><td>Codigo Presupuestal</td><td>Tipo de Pago</td><td>Fte Financiacion</td><td>No Comprobante</td><td>Beneficiario</td><td>Cedula o NIT</td><td>Detalle de Pago</td><td>Valor Comprobante</td><td>Desctos Seg Social</td><td>Retenciones</td><td>Otros Descuentos</td><td>Neto Pagado</td><td>Banco</td><td>No de Cuenta</td><td>Cheque</td><td>Cta Contable</td><td>Nombre</td></tr>

<?

	$cons2="Select ClasePago,TipoPago,Comprobante,Numero from Contabilidad.Movimiento where Compania='$Compania[0]' and Estado='AC' and date_part('year',Fecha)=$Anio";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$DatosPago[$fila2[2]][$fila2[3]]=array($fila2[0],$fila2[1]);
	}
	
	$cons3="Select sum(Haber),Comprobante,Numero from Contabilidad.Movimiento where Compania='$Compania[0]' and Estado='AC' and Cuenta like '2425%' and date_part('year',Fecha)=$Anio Group By Comprobante,Numero";
	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		$DatosSegSoc[$fila3[1]][$fila3[2]]=$fila3[0];
	}

	$cons3="Select sum(Haber),Comprobante,Numero from Contabilidad.Movimiento where Compania='$Compania[0]' and Estado='AC' and Cuenta like '2436%' and date_part('year',Fecha)=$Anio
	Group By Comprobante,Numero";
	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		$DatosRetenc[$fila3[1]][$fila3[2]]=$fila3[0];
	}

	$cons3="Select sum(Haber),Comprobante,Numero from Contabilidad.Movimiento where Compania='$Compania[0]' and Estado='AC' and Cuenta NOT like '2436%' and Cuenta NOT Like '2425%'
	and cuenta NOT Like '1%' and date_part('year',Fecha)=$Anio Group By Comprobante,Numero";
	$res3=ExQuery($cons3);echo ExError();
	while($fila3=ExFetch($res3))
	{
		$DatosOtros[$fila3[1]][$fila3[2]]=$fila3[0];
	}

	$cons3="Select Haber,Cuenta,NoCheque,Comprobante,Numero 
	from Contabilidad.Movimiento 
	where Compania='$Compania[0]' and Estado='AC' and cuenta Like '11%' and date_part('year',Fecha)=$Anio";
	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		$DatosCheque[$fila3[3]][$fila3[4]]=array($fila3[0],$fila3[1],$fila3[2]);
	}

	$cons4="Select NomBanco,NumCuenta,Cuenta,FteFinanciacion,Nombre from Contabilidad.PlanCuentas 
	where Compania='$Compania[0]' and Anio='$Anio'";
	$res4=ExQuery($cons4);
	while($fila4=ExFetch($res4))
	{
		$DatosCuenta[$fila4[2]]=array($fila4[0],$fila4[1],$fila4[3],$fila4[4]);
	}

	$cons4="Select sum(ContraCredito),CompAfectado,DocSoporte from Presupuesto.Movimiento,Presupuesto.Comprobantes 
	where 
	Movimiento.Comprobante=Comprobantes.Comprobante and
	Comprobantes.TipoComprobant='Disminucion a egreso presupuestal' and Estado='AC' 
	and Movimiento.Vigencia='Actual'
	and Movimiento.Compania='$Compania[0]' 
	and Fecha>='$Anio-$MesIni-01' 
	and Fecha<='$Anio-$MesFin-$UltDia' 
	Group By CompAfectado,DocSoporte
	Order By DocSoporte";
	$res4=ExQuery($cons4);
	while($fila4=ExFetch($res4))
	{
		$DisminucEgresos[$fila4[1]][$fila4[2]]=$fila4[0];
	}

	$cons="Select Fecha,Movimiento.Cuenta,DocOrigen,NoDocOrigen,Movimiento.Identificacion,Detalle,Credito,PrimApe,SegApe,PrimNom,SegNom,SIA,Movimiento.Comprobante,Numero 
	from Presupuesto.Movimiento,Presupuesto.Comprobantes,Central.Terceros,Presupuesto.PlanCuentas 
	where Movimiento.Comprobante=Comprobantes.Comprobante 
	and PlanCuentas.Cuenta=Movimiento.Cuenta
	and PlanCuentas.Anio=$Anio
	and Movimiento.Identificacion=Terceros.Identificacion 
	and Comprobantes.TipoComprobant='Egreso presupuestal' and Estado='AC' 
	and Movimiento.Vigencia='Actual'
	and PlanCuentas.Vigencia='Actual'
	
	and Movimiento.Compania='$Compania[0]' 
	and Comprobantes.Compania='$Compania[0]' 
	and Terceros.Compania='$Compania[0]'
	and PlanCuentas.Compania='$Compania[0]'

	and Fecha>='$Anio-$MesIni-01' 
	and Fecha<='$Anio-$MesFin-$UltDia' 
	Order By Numero";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$Tercero="$fila[7] $fila[8] $fila[9] $fila[10]";
		
		$ClasePago=$DatosPago[$fila[2]][$fila[3]][0];$TipoPago=$DatosPago[$fila[2]][$fila[3]][0];
		$DesctosSegSoc=$DatosSegSoc[$fila[2]][$fila[3]];

		$Retencion=$DatosRetenc[$fila[2]][$fila[3]];

		$OtrosDtos=$DatosOtros[$fila[2]][$fila[3]];
		$Pago=$DatosCheque[$fila[2]][$fila[3]][0];
		$CtaBanco=$DatosCheque[$fila[2]][$fila[3]][1];
		$Cheque=$DatosCheque[$fila[2]][$fila[3]][2];

		$Banco=$DatosCuenta[$CtaBanco][0];$Cuenta=$DatosCuenta[$CtaBanco][1];
		$FteFinanciacion=$DatosCuenta[$CtaBanco][2];$NombreCuenta=$DatosCuenta[$CtaBanco][3];
		
		$Pago=$fila[6]-$DesctosSegSoc-$Retencion-$OtrosDtos;
		$fila[6]=$fila[6]-$DisminucEgresos[$fila[12]][$fila[13]];
		echo "<tr><td>$fila[0]</td><td><strong>$fila[11]</strong>$fila[1]</td><td>$TipoPago</td><td>$FteFinanciacion</td><td>$fila[3]</td><td>$Tercero</td><td>$fila[4]</td><td>".$fila[5]."</td><td align='right'>".number_format($fila[6],2)."</td>";
		echo "<td align='right'>".number_format($DesctosSegSoc,2)."</td><td align='right'>".number_format($Retencion,2)."</td><td align='right'>".number_format($OtrosDtos,2)."</td><td align='right'>".number_format($Pago,2)."</td>";
		echo "<td>$Banco</td><td>$Cuenta</td><td>$Cheque</td><td>$CtaBanco</td><td>$NombreCuenta</td></tr>";
		$Archivo=$Archivo."$fila[0],$fila[1],$ClasePago,$TipoPago,$fila[3],$Tercero,$fila[4],$fila[5],$fila[6],$DesctosSegSoc,$Retencion,$OtrosDtos,$Pago,$Banco,$Cuenta,$Cheque<br>";

	}
	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("formato_200801_f07b_cdn.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td colspan=3><a href='formato_200801_f07b_cdn.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";

