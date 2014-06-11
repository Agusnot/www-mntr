<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
        //Ult. Mod. 26-03-2011
	/*$cons="Select distinct Comprobante,Numero,Fecha,Codigo1,Cantidad,NombreProd1,Presentacion,UnidadMedida,Cedula,Movimiento.AutoId,
        VrCosto,Movimiento.VrIVA,(TotCosto+Movimiento.VrIVA),Movimiento.UsuarioCre,TotCosto,CompContable,NumCompCont,Detalle,CentroCosto
	from Consumo.Movimiento,Consumo.CodProductos 
	where Movimiento.AutoId=CodProductos.AutoId and Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' and Numero='$Numero'
	and CodProductos.Anio = $Anio";*/
	$cons="Select distinct Comprobante,Numero,Fecha,Codigo1,sum(Cantidad),NombreProd1,Presentacion,UnidadMedida,Cedula,Movimiento.AutoId,
        VrCosto,Movimiento.VrIVA,sum(TotCosto+Movimiento.VrIVA),Movimiento.UsuarioCre,sum(TotCosto),CompContable,NumCompCont,Detalle,CentroCosto
	from Consumo.Movimiento,Consumo.CodProductos 
	where Movimiento.AutoId=CodProductos.AutoId and Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' and Numero='$Numero'
	and CodProductos.Anio = $Anio group by Comprobante,Numero,Fecha,Codigo1,NombreProd1,Presentacion,UnidadMedida,Cedula,Movimiento.AutoId,
	VrCosto,Movimiento.VrIVA,Movimiento.UsuarioCre,CompContable,NumCompCont,Detalle,CentroCosto";
	$res=ExQuery($cons);echo ExError();
	$fila=ExFetch($res);
	$Fecha=$fila[0];$Usuario=$fila[13];
	$CompContable=$fila[15];$NumCompConta=$fila[16];
	if($fila[7]=="Anulado"){echo "<img style='position:absolute;left:100px;top:100px;' src='/Imgs/Anulado.gif'>";}
	
	$cons2="Select PrimApe,SegApe,PrimNom,SegNom,Direccion,Telefono from Central.Terceros where Identificacion='$fila[8]' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError();
	$fila2=ExFetch($res2);
	$NomTercero="$fila2[0] $fila2[1] $fila2[2] $fila2[3]";$Direccion=$fila2[4];$Telefono=$fila2[5];
	$Detall=$fila[17];
?>
<head>
	<title><?echo $Sistema[$NoSistema]?></title>
</head>

<body background="/Imgs/Fondo.jpg">
<center><font style="font : 15px Tahoma;font-weight:bold">
<? echo strtoupper($Compania[0])?><br /></font>
<font style="font : 12px Tahoma;">
<? echo $Compania[1]?><br /><? echo "$Compania[2] $Compania[3]"?><br />
</center></strong></font>
<br />
<table border="1" bordercolor="white" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td bgcolor="#e5e5e5">Fecha y Hora </td><td><?echo $fila[2]?></td>
<td></td><td></td>
<td rowspan="4">
<table bordercolor='#e5e5e5' border="1" style='font : normal normal small-caps 14px Tahoma;' align="right">
<tr><td bgcolor="#e5e5e5"><font size="4"><strong><? echo $fila[0]?></strong></font></td></tr><tr><td align="center"><? echo $fila[1]?></td></tr>
</table>
</td>
</tr>


<tr><td bgcolor="#e5e5e5">Tercero</td><td><?echo $NomTercero?></td><td bgcolor="#e5e5e5">Identificacion</td><td><? echo $fila[8]?></td>
<tr><td bgcolor="#e5e5e5">Direccion</td><td><?echo $Direccion?></td>
<td bgcolor="#e5e5e5">Telefono</td><td><?echo $Telefono?></td>
<tr><td bgcolor="#e5e5e5">Detalle</td><td colspan="3"><?echo $Detall?></td>


</table>

<br /><br />
<table border="1" bordercolor="#e5e5e5" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>Codigo</td><td>Nombre</td><td>CC</td><td align="right">Cantidad</td><td>Vr Unidad</td><td>Vr IVA</td><td>Total</td></tr>
<?
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[3]</td><td>$fila[5] $fila[6] $fila[7]</td><td>$fila[18]</td><td align='right'>$fila[4]</td><td align='right'>".number_format($fila[10],2)."</td><td align='right'>".number_format($fila[11],2)."</td><td align='right'>".number_format($fila[12],2)."</td></tr>";	
		$SubTotal=$SubTotal+$fila[14];
		$IVA=$IVA+$fila[11];
		$Total=$Total+$fila[12];
        }
	echo "<tr><td colspan=5></td><td bgcolor='#e5e5e5'><strong>SUBTOTAL</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($SubTotal,2)."</td></tr>";
	echo "<tr><td colspan=5></td><td bgcolor='#e5e5e5'><strong>IVA</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($IVA,2)."</td></tr>";
	echo "<tr><td colspan=5></td><td bgcolor='#e5e5e5'><strong>TOTAL</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($Total,2)."</td></tr>";
	$Total=round($Total);
	$Letras=NumerosxLet($Total);
	
	$cons55="Select Mensaje1 from Consumo.Comprobantes where Compania='$Compania[0]' and Comprobante='$Comprobante'";
	$res55=ExQuery($cons55);echo ExError();
	$fila55=ExFetch($res55);
?>
<tr><td colspan="7">SON: <font size="-2"><? echo strtoupper($Letras)?></td></tr>
<tr><td colspan="7">
<table bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;'>
<tr bgcolor="#e5e5e5"><td colspan="6" align="center"><strong><? echo ("$CompContable <br>$NumCompConta")?></td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Concepto</td><td align="right">Debitos</td><td align="right">Creditos</td><td>CC</td></tr>
<?
	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Movimiento.Cuenta,Debe,Haber,CC,DocSoporte,'',Estado,UsuarioCre,nombre
	from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Movimiento.Anio=PlanCuentas.Anio and PlanCuentas.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]' and 
	Comprobante='$CompContable' and Numero='$NumCompConta' Order By Debe Desc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="Select centrocostos from Central.CentrosCosto where codigo='$fila[9]' and Compania='$Compania[0]' and Anio=$Anio";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		echo "<tr><td>$fila[6]</td><td>$fila[14]</td><td align='right'>".number_format($fila[7],2)."</td><td align='right'>".number_format($fila[8],2)."</td><td>$fila[9] - $fila1[0]</td></tr>";
		$TotDebe=$TotDebe+$fila[7];$TotHaber=$TotHaber+$fila[8];$Fecha=$fila[1];
	}
	echo "<tr><td colspan=4>Fecha: $Fecha</td></tr>";
	echo "<tr align='right' bgcolor='#e5e5e5' style='font-weight:bold'><td colspan=2>SUMAS</td><td>".number_format($TotDebe,2)."</td><td>".number_format($TotHaber,2)."</td></tr>";
?>



</table>

</table>

<br /><br /><br />
<table border="0" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td>_________________________________<br /><strong><?echo strtoupper($Usuario)?></strong><br>Entrega</td><td style="width:130px;"></td><td>_________________________________<br /><strong><?echo $NomTercero?></strong><br>Recibe</td>
</table><br><font size="-1">
<?
	echo "<em>$fila55[0]</em>";
?></font>
<img src="/Imgs/Logo.jpg" style="width: 70px; height: 79px; position: absolute; top: 5px; left: 50px;" />
</body>