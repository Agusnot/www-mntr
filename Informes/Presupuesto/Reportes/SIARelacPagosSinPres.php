<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();

	$cons="Select * from Central.Meses where Numero=$MesFin";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesFinLet=$fila[0];
	$UltDia=$fila[2];

?>
	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Relación de Pagos sin Afectacion Presupuestal<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Fecha de Pago</td><td>Número de Comprobante</td><td>Beneficiario</td><td>Identificacion</td><td>Detalle de Pago</td><td>Valor Comprobante</td><td>Descuentos</td><td>Neto Pagado</td><td>No de Cuenta</td><td>No. de Cheque</td></tr>
<?
	$cons4="Select sum(Haber),Comprobante,Numero 
	from Contabilidad.Movimiento 
	where Compania='$Compania[0]' and Estado='AC' and Cuenta like '24%' Group By Comprobante,Numero";
	$res4=ExQuery($cons4);echo ExError();
	while($fila4=ExFetch($res4))
	{
		$DatosDesto[$fila4[1]][$fila4[2]]=$fila4[0];
	}

	$cons4="Select sum(Debe),Movimiento.Comprobante,Numero 
	from Contabilidad.Movimiento,Contabilidad.Comprobantes 

	where 
	Movimiento.Comprobante=Comprobantes.Comprobante  and
	Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Estado='AC'
	and Comprobantes.TipoComprobant='Egreso' and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia'	
	Group By Movimiento.Comprobante,Numero";
	$res4=ExQuery($cons4);echo ExError();
	while($fila4=ExFetch($res4))
	{
		$SumDebes[$fila4[1]][$fila4[2]]=$fila4[0];
	}

	$cons2="Select sum(Credito),DocOrigen,NoDocOrigen 
	from Presupuesto.Movimiento,Presupuesto.Comprobantes 
	where 
	Movimiento.Comprobante=Comprobantes.Comprobante
	and Comprobantes.TipoComprobant='Egreso presupuestal' and Comprobantes.Compania='$Compania[0]'	 and 
	Movimiento.Compania='$Compania[0]' and Estado='AC' and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia'
	Group By DocOrigen,NoDocOrigen";
	$res2=ExQuery($cons2);echo ExError();
	while($fila2=ExFetch($res2))
	{
		$SumCreditos[$fila2[1]][$fila2[2]]=$fila2[0];
	}

	$cons2="Select DocOrigen,NoDocOrigen from Presupuesto.Movimiento where Compania='$Compania[0]' and Estado='AC'";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$DocsOr[$fila2[0]][$fila2[1]]=$fila2[1];
	}


	$cons="Select Fecha,Numero,Movimiento.Identificacion,Detalle,Haber,Cuenta,NoCheque,Movimiento.Comprobante,PrimApe,SegApe,PrimNom,SegNom 
	from Contabilidad.Movimiento,Contabilidad.Comprobantes,Central.Terceros
	
	where Movimiento.Comprobante=Comprobantes.Comprobante 
	and Terceros.Identificacion=Movimiento.Identificacion
	and Comprobantes.TipoComprobant='Egreso' 
	and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Estado='AC' and Cuenta like '11%'
	and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia'";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$cons9="Select Formato from Contabilidad.Comprobantes where Comprobante='$fila[7]' and Compania='$Compania[0]'";
		$res9=ExQuery($cons9);
		$fila9=ExFetch($res9);
		$Archivo=$fila9[0];

		$ExistePartePres=$DocsOr[$fila[7]][$fila[1]][0];
		if($ExistePartePres==0)
		{
			$Tercero="$fila[8] $fila[9] $fila[10] $fila[11]";

			$VrNeto=$fila[4];

			$Destos=$DatosDesto[$fila[7]][$fila[1]];

			$VrComp=$VrNeto+$Destos;
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand" onClick="open('/Informes/Contabilidad/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Comprobante=<? echo $fila[7]?>&Cuenta=0&Numero=<?echo $fila[1]?>','','width=700,height=500,scrollbars=yes')"><? echo "$fila[1]</td><td>$Tercero</td><td>$fila[2]</td><td>$fila[3]</td><td align='right'>".number_format($VrComp,2)."</td><td align='right'>".number_format($Destos,2)."</td><td align='right'>".number_format($VrNeto,2)."</td><td align='right'>$fila[5]</td><td>$fila[6]</td></tr>";
			$Archivo=$Archivo."$fila[0],$fila[1],$Tercero,$fila[2],$fila[3],$VrComp,$Destos,$VrNeto,$fila[5],$fila[6]<br>";
		}
		else
		{
			if(round($SumCreditos[$fila[7]][$fila[1]])!=round($SumDebes[$fila[7]][$fila[1]]))
			{

				$Tercero="$fila[8] $fila[9] $fila[10] $fila[11]";
	
				$VrNeto=$SumDebes[$fila[7]][$fila[1]]-$SumCreditos[$fila[7]][$fila[1]];
	
				$Destos=$DatosDesto[$fila[7]][$fila[1]];
	
				$VrComp=$VrNeto+$Destos;
				echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand" onClick="open('/Informes/Contabilidad/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Comprobante=<? echo $fila[7]?>&Cuenta=0&Numero=<?echo $fila[1]?>','','width=700,height=500,scrollbars=yes')"><? echo "$fila[1]</td><td>$Tercero</td><td>$fila[2]</td><td>$fila[3]</td><td align='right'>".number_format($VrComp,2)."</td><td align='right'>".number_format($Destos,2)."</td><td align='right'>".number_format($VrNeto,2)."</td><td align='right'>$fila[5]</td><td>$fila[6]</td></tr>";
				$Archivo=$Archivo."$fila[0],$fila[1],$Tercero,$fila[2],$fila[3],$VrComp,$Destos,$VrNeto,$fila[5],$fila[6]<br>";
			}
		}
	}
	
	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("formato_200801_f07b1_cdn.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);
	echo "<tr><td colspan=3><a href='formato_200801_f07b1_cdn.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";
	
