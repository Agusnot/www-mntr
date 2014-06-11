<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("ObtenerSaldos.php");
	$ND = getdate();
	if($Ver)
	{	if($FechaIni > $FechaFin)
		{	
		?><script language="javascript">alert("La Fecha Final no debe ser mayor a la Fecha Inicial")</script><?
		$Anio = $ND[year]; $MesIni = "01"; $DiaIni="01";
		$MesFin = $ND[mon]; $DiaFin = $ND[mday];
		}
	}
	if(!$Anio){$Anio=$ND[year];}
	if(!$FechaIni){ $FechaIni = $ND[year]."-01-01";$DiaIni = "01"; $MesIni = "01";}
	else{$FechaIni = $Anio."-".$MesIni."-".$DiaIni;}
	if(!$FechaFin){
		if($ND[mon]<10){$MesFin = "0".$ND[mon];}else{$MesFin=$ND[mon];}
		if($ND[mday<10]){$DiaFin = "0".$ND[mday];}else{$DiaFin=$ND[mday];}	
		$FechaFin = "$ND[year]-$MesFin-$DiaFin";}
	else{$FechaFin = $Anio."-".$MesFin."-".$DiaFin;}
	$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$cons="Select Comprobante,Numero,Fecha,Codigo1,Cantidad,
	NombreProd1,Presentacion,UnidadMedida,Cedula,Movimiento.AutoId,VrCosto,Movimiento.VrIVA,(TotCosto+Movimiento.VrIVA),Movimiento.UsuarioCre,TotCosto,Detalle
	from Consumo.Movimiento,Consumo.CodProductos 
	where Movimiento.AutoId=CodProductos.AutoId and Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' and Numero='$Numero'
	and CodProductos.Anio = $AnioComp";
        //echo $cons;
	$res=ExQuery($cons);echo ExError();
	$fila=ExFetch($res);
	$Fecha=$fila[0];$Usuario=$fila[13];$Detalle=$fila[15];
	if($fila[7]=="Anulado"){echo "<img style='position:absolute;left:100px;top:100px;' src='/Imgs/Anulado.gif'>";}
	
	$cons2="Select PrimApe,SegApe,PrimNom,SegNom,Direccion,Telefono from Central.Terceros where Identificacion='$fila[8]' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError();
	$fila2=ExFetch($res2);
	$NomTercero="$fila2[0] $fila2[1] $fila2[2] $fila2[2]";$Direccion=$fila2[4];$Telefono=$fila2[5];
	
?>
<head>
	<title><?echo $Sistema[$NoSistema]?></title>
</head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript" src="/calendario/popcalendar.js"></script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="FechaIni" value="<? echo $FechaIni;?>" />
<input type="hidden" name="FechaFin" value="<? echo $FechaFin;?>" />
<center><font style="font : 15px Tahoma;font-weight:bold">
<?echo $Compania[0]?><br /></font>
<font style="font : 12px Tahoma;">
<? echo $Compania[1]?><br /><? echo "$Compania[2] $Compania[3]"?><br />
</center></strong></font>

<table bordercolor='#e5e5e5' border="0" style='font : normal normal small-caps 14px Tahoma;' align="center">
<tr><td bgcolor="#e5e5e5"><strong><font size="3">
<div style="cursor:hand" onClick="location.href='OrdenCompra.php?DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $Numero?>&Comprobante=<? echo $Comprobante?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $AnioComp?>'">
Analisis de <? echo $fila[0]?></div></td></tr><tr><td align="center"><? echo $fila[1]?></td></tr>
</table>


<table border="1" bordercolor="white" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td bgcolor="#e5e5e5">Fecha</td><td><?echo $fila[2]?></td>
<td></td><td></td>
</td>
</tr>
<tr><td bgcolor="#e5e5e5">Proveedor</td><td><?echo $NomTercero?></td><td bgcolor="#e5e5e5">Identificacion</td><td><? echo $fila[8]?></td>
<tr><td bgcolor="#e5e5e5">Direccion</td><td><?echo $Direccion?></td>
<td bgcolor="#e5e5e5">Telefono</td><td><?echo $Telefono?></td></tr>
<tr>
<td bgcolor="#e5e5e5">Detalle</td><td colspan="3"><? echo $Detalle?></td>
</tr>
</table><br /><br />

<table border="1" bordercolor="#e5e5e5" width="100%" style='font : normal normal small-caps 11px Tahoma;'>
<tr bgcolor="#e5e5e5"><td colspan="7" style="font-weight:bold" align="center">RANGO DE FECHAS PARA ANALISIS DE CONSUMO</td></tr>
<tr><td colspan="7" align="center">
		A&ntilde;o:<select name="Anio" onchange="FORMA.submit()">
        <?	$cons1 = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio";
			$res1 = ExQuery($cons1);
			while($fila1 = ExFetch($res1))
			{
				if($Anio==$fila1[0]){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
				else {echo "<option value='$fila1[0]'>$fila1[0]</option>";}
				$AnioAd = $fila1[0] + 1;
			}
			if($Anio==$AnioAd){echo "<option selected value='$AnioAd'>$AnioAd</option>";}
			else{echo "<option value='$AnioAd'>$AnioAd</option>";}
		?></select>
        Desde:<select name="MesIni" onChange="FORMA.DiaIni.focus();">
		<? for($i=1;$i<=12;$i++)
		{
			if($MesIni==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
			else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
		}
		?></select>
		<input type='Text' name='DiaIni' style='width:20px;' maxlength="2" value="<? echo $DiaIni?>"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
		Hasta:<select name="MesFin">
		<? for($i=1;$i<=12;$i++)
		{
			if($MesFin==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
			else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
		}?></select>
		<input type='Text' name='DiaFin' style='width:20px;' maxlength="2" value='<? echo $DiaFin?>'
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
        <input type="submit" name="Ver" value="Ver" /></td>
</tr>
<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>Codigo</td><td>Nombre</td><td align="right">Cant</td><td>Esta Compra</td><td>Ult Compra</td>
<td>Nro Compra</td><td>Consumo</td></tr>
<?
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1 = "Select VrCosto,NoDocAfectado,VrIVA,Fecha,Numero,Comprobante,Cantidad from Consumo.Movimiento 
		where AutoId='$fila[9]' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and Fecha >= '$FechaIni'
                and Fecha <= '$FechaFin'
		and (TipoComprobante='Entradas' Or TipoComprobante='Orden de Compra') order by Fecha desc";
                //echo $cons1;
		$res1 = ExQuery($cons1);
		$UPC=""; $NroUPC=""; $FechaUPC="";
		while($fila1 = ExFetch($res1))
		{	if($fila1[1]!=$Numero)
			{	$UPC = $fila1[0] + ($fila1[2]/$fila1[6]);
				$NroUPC = $fila1[4];
				$FechaUPC = $fila1[3];$Comp=$fila1[5];
				break;}
		}
		echo "<tr><td>$fila[3]</td><td>$fila[5] $fila[6] $fila[7]</td><td align='right'>$fila[4]</td>
		<td align='right'>$ ".number_format(($fila[10]+($fila[11]/$fila[4])),2)."</td>
		<td align='right'>$ ".number_format($UPC,2)."</td>
		<td title='$FechaUPC' align='right'>".substr($Comp,0,5)."->$NroUPC</td>
		<td align='right'>".number_format($VrSalidas[$fila[9]][0],2)."</td></tr>";	
	} ?>
</form>
</body>