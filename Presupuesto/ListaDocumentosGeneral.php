<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">
<table border="1" style="width:1400px;" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#666699" style="color:white;font-weight:bold"><td>Cuenta</td><td>Fecha</td><td>Comprobante</td><td>Numero</td><td>Tercero</td><td>Detalle</td><td>Doc Afectado</td><td>Numero</td><td>Credito</td><td>Contra Credito</td></tr>

<?
	if($Movimiento){$condAdc=" and $Movimiento>0";}
	if($Modo==0){$condPeriodo=" and date_part('month',Fecha)>=$MesIni and date_part('month',Fecha)<=$MesFin ";}
	if($Modo==1){$condPeriodo=" and date_part('month',Fecha)>=$MesIni and date_part('month',Fecha)<$MesFin ";}
	if($Modo==2){$condPeriodo=" and date_part('month',Fecha)=$MesFin ";}
	if($Disminucion){$condDisminucion=" Or TipoComprobant='$Disminucion'";}
	$cons="Select Cuenta,Fecha,Movimiento.Comprobante,Numero,Detalle,CompAfectado,DocSoporte,Credito,ContraCredito,Identificacion 
	from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and
	Cuenta ilike '$Cuenta%' $condPeriodo and (TipoComprobant='$Tipo' $condDisminucion) $condAdc  and Estado='AC' and date_part('year',Fecha)=$Anio
	 and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Movimiento.Compania=Comprobantes.Compania
	  and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
	
		$cons4="Select TipoComprobant,Archivo from Presupuesto.Comprobantes where Comprobante='$fila[2]' and Compania='$Compania[0]'";
		$res4=ExQuery($cons4);
		$fila4=ExFetch($res4);
		$Archivo1=$fila4[1];

		$cons4="Select TipoComprobant,Archivo from Presupuesto.Comprobantes where Comprobante='$fila[5]' and Compania='$Compania[0]'";
		$res4=ExQuery($cons4);
		$fila4=ExFetch($res4);
		$Archivo2=$fila4[1];
	
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="";$Fondo=1;}
		$cons1="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila[9]' and Terceros.Compania='$Compania[0]'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$Tercero="$fila1[0] $fila1[1] $fila1[2] $fila1[3]";
		echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>$fila[1]</td><td>".substr($fila[2],0,35)."</td><td align='center' style='color:blue'>";
		?><a style="cursor:hand" onclick="open('/Informes/Presupuesto/<? echo $Archivo1?>?DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $fila[3]?>&Comprobante=<? echo $fila[2]?>&Vigencia=<? echo $Vigencia?>&ClaseVigencia=<? echo $ClaseVigencia?>','','width=650,height=500,scrollbars=yes')"><? echo "$fila[3]</td><td>$Tercero</td><td>$fila[4]</td><td>$fila[5]</td><td align='center'>";?>
        <a style="cursor:hand" onclick="open('/Informes/Presupuesto/<? echo $Archivo2?>?DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $fila[6]?>&Comprobante=<? echo $fila[5]?>&Vigencia=<? echo $Vigencia?>&ClaseVigencia=<? echo $ClaseVigencia?>','','width=650,height=500,scrollbars=yes')" style="color:blue">
		<? echo "$fila[6]</td><td align='right'>".number_format($fila[7],2)."</td><td align='right'>".number_format($fila[8],2)."</td></tr>";
		$TotCredito=$TotCredito+$fila[7];
		$TotCCredito=$TotCCredito+$fila[8];
	}
	echo "<tr><td colspan=7></td><td colspan=3><hr></td></tr>";
	echo "<tr align='right' style='font-weight:bold'><td colspan=7></td><td>SUMAS</td><td>".number_format($TotCredito,2)."</td><td>".number_format($TotCCredito,2)."</td></tr>";
?>

</table>

</body>