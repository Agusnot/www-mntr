<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	ini_set("memory_limit","512M");
	include("Funciones.php");
	include("CalcularSaldos.php");

	$cons="Select NumDias from Central.Meses where Numero=$MesFin";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MaxNumDias=$fila[0];

	ObtieneValoresxDocxCuenta("$Anio-$MesIni-01","$Anio-$MesFin-$MaxNumDias","%");
?>
<body background="/Imgs/Fondo.jpg">
<table border="1" bordercolor="#ffffff" style="width:1400px;" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#666699" style="color:white;font-weight:bold"><td>Cuenta</td><td>Fecha</td><td>Comprobante</td><td>Numero</td><td>Detalle</td><td>Tercero</td><td>Doc Afectado</td><td>Numero</td><td>Credito</td><td>Contra Credito</td></tr>

<?
	if($Movimiento){$condAdc=" and $Movimiento>0";}
	if($Modo==0){$condPeriodo=" and date_part('month',Fecha)>=$MesIni and date_part('month',Fecha)<=$MesFin ";}
	if($Modo==1){$condPeriodo=" and date_part('month',Fecha)>=$MesIni and date_part('month',Fecha)<$MesFin ";}
	if($Modo==2){$condPeriodo=" and date_part('month',Fecha)=$MesFin and ";}
	if($Disminucion){$condDisminucion=" Or TipoComprobant='$Disminucion'";}
	$cons="Select Cuenta,Fecha,Movimiento.Comprobante,Numero,Detalle,'','',sum(Credito),sum(ContraCredito),Identificacion,Vigencia,ClaseVigencia 
	from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and
	Cuenta ilike '$Cuenta%' $condPeriodo and (TipoComprobant='$Tipo' $condDisminucion) $condAdc  and Estado='AC' and Movimiento.Compania='$Compania[0]'
	and Comprobantes.Compania='$Compania[0]' and Movimiento.Compania=Comprobantes.Compania
	and date_part('year',Fecha)=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'
	Group By Cuenta,Movimiento.Comprobante,Numero,Fecha,Detalle,Identificacion,Vigencia,ClaseVigencia Order By Numero";
	$PerIni="$Anio-$MesIni-01";
	$PerFin="$Anio-$MesFin-$MaxNumDias";

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
	
		$Valor=CalcularSaldoxDocxCuenta($fila[0],$fila[3],$fila[2],"$Anio-$MesIni-01","$Anio-$MesFin-$MaxNumDias",$fila[10],$fila[11],$Vigencia,$ClaseVigencia);

		if($Valor!=0)
		{

		$cons4="Select TipoComprobant,Archivo from Presupuesto.Comprobantes where Comprobante='$fila[2]' and Compania='$Compania[0]'";
		$res4=ExQuery($cons4);
		$fila4=ExFetch($res4);
		$Archivo1=$fila4[1];

		$cons1="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila[9]' and Terceros.Compania='$Compania[0]'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$Tercero="$fila1[0] $fila1[1] $fila1[2] $fila1[3]";

		$cons2="Select CompAfectado,DocSoporte from Presupuesto.Movimiento where Comprobante='$fila[2]' and Numero='$fila[3]' and Cuenta='$fila[0]'
		and Compania='$Compania[0]'  and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";

		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);

		if($Movimiento=="Credito" && $Valor>0){$Credito=$Valor;$CCredito=0;}
		elseif($Movimiento=="Credito" && $Valor<0){$Credito=0;$CCredito=$Valor;}
		elseif($Movimiento=="ContraCredito" && $Valor>0){$CCredito=$Valor;$Credito=0;}
		elseif($Movimiento=="ContraCredito" && $Valor<0){$CCredito=0;$Credito=$Valor;}

		$Credito=abs($Credito);$CCredito=abs($CCredito);
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>$fila[1]</td><td>".substr($fila[2],0,35)."</td><td align='center'>";?>
        <a style="cursor:hand" onclick="open('/Informes/Presupuesto/<? echo $Archivo1?>?DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $fila[3]?>&Comprobante=<? echo $fila[2]?>&Vigencia=<? echo $Vigencia?>&ClaseVigencia=<? echo $ClaseVigencia?>','','width=650,height=500,scrollbars=yes')" style="color:blue">
        <? echo "$fila[3]</td><td>$fila[4]</td><td>$Tercero</td><td>$fila2[0]</td><td align='center'>$fila2[1]</td><td align='right'>".number_format($Credito,2)."</td><td align='right'>".number_format($CCredito,2)."</td></tr>";}
		$TotCredito=$TotCredito+$Credito;
		$TotCCredito=$TotCCredito+$CCredito;
		$Credito=0;$CCredito=0;
	}
	echo "<tr><td colspan=7></td><td colspan=3><hr></td></tr>";
	echo "<tr align='right' style='font-weight:bold'><td colspan=7></td><td>SUMAS</td><td>".number_format($TotCredito,2)."</td><td>".number_format($TotCCredito,2)."</td></tr>";
?>

</table>

</body>