<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="Select Comprobante,Numero,Fecha,Codigo1,Cantidad,NombreProd1,Presentacion,UnidadMedida,Cedula,Movimiento.AutoId,VrCosto,Movimiento.VrIVA,(TotCosto+Movimiento.VrIVA),Movimiento.UsuarioCre,TotCosto,CentroCosto
	from Consumo.Movimiento,Consumo.CodProductos 
	where Movimiento.AutoId=CodProductos.AutoId and Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' and Numero='$Numero'
        and CodProductos.Anio=$Anio";
	//echo $cons;
        $res=ExQuery($cons);echo ExError();
	$fila=ExFetch($res);
	$Fecha=$fila[0];$Usuario=$fila[13];
	if($fila[7]=="Anulado"){echo "<img style='position:absolute;left:100px;top:100px;' src='/Imgs/Anulado.gif'>";}
	
	$cons2="Select PrimApe,SegApe,PrimNom,SegNom,Direccion,Telefono from Central.Terceros where Identificacion='$fila[8]' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError();
	$fila2=ExFetch($res2);
	$NomTercero="$fila2[0] $fila2[1] $fila2[2] $fila2[2]";$Direccion=$fila2[4];$Telefono=$fila2[5];
	
	$cons9="Select CentroCostos from Central.CentrosCosto where Codigo='$fila[15]' and Compania='$Compania[0]'";
	$res9=ExQuery($cons9);
	$fila9=ExFetch($res9);
	$CentroCostos=$fila9[0];
?>
<head>
	<title><?echo $Sistema[$NoSistema]?></title>
</head>

<body background="/Imgs/Fondo.jpg">
<center><font style="font : 15px Tahoma;font-weight:bold">
<?echo $Compania[0]?><br /></font>
<font style="font : 12px Tahoma;">
<? echo $Compania[1]?><br /><? echo "$Compania[2] $Compania[3]"?><br />
</center></strong></font>
<br />
<table border="1" bordercolor="white" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td bgcolor="#e5e5e5">Fecha y Hora </td><td><?echo $fila[2]?></td>
<td></td><td></td>
<td rowspan="4">
<table bordercolor='#e5e5e5' border="1" style='font : normal normal small-caps 14px Tahoma;' align="right">
<tr><td bgcolor="#e5e5e5"><? echo $fila[0]?></td></tr><tr><td align="center"><? echo $fila[1]?></td></tr>
</table>
</td>
</tr>


<tr><td bgcolor="#e5e5e5">Usuario</td><td><?echo $NomTercero?></td><td bgcolor="#e5e5e5">Identificacion</td><td><? echo $fila[8]?></td>
<tr><td bgcolor="#e5e5e5">Centro de Costos</td><td colspan="3"><? echo " $fila[15] - $CentroCostos"?></td>


</table>

<br /><br />
<table border="1" bordercolor="#e5e5e5" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>Codigo</td><td>Nombre</td><td align="right">Cantidad</td><td>Vr Unidad</td><td>Vr IVA</td><td>Total</td></tr>
<?
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[3]</td><td>$fila[5] $fila[6] $fila[7]</td><td align='right'>$fila[4]</td><td align='right'>".number_format($fila[10],2)."</td><td align='right'>".number_format($fila[11],2)."</td><td align='right'>".number_format($fila[12],2)."</td></tr>";	
		$SubTotal=$SubTotal+$fila[14];
		$IVA=$IVA+$fila[11];
		$Total=$Total+$fila[12];
	}
	echo "<tr><td colspan=4></td><td bgcolor='#e5e5e5'><strong>SUBTOTAL</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($SubTotal,2)."</td></tr>";
	echo "<tr><td colspan=4></td><td bgcolor='#e5e5e5'><strong>IVA</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($IVA,2)."</td></tr>";
	echo "<tr><td colspan=4></td><td bgcolor='#e5e5e5'><strong>TOTAL</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($Total,2)."</td></tr>";
	$Letras=NumerosxLet($Total);
	
	$cons55="Select Mensaje1 from Consumo.Comprobantes where Compania='$Compania[0]' and Comprobante='$Comprobante'";
	$res55=ExQuery($cons55);echo ExError();
	$fila55=ExFetch($res55);
?>
<tr><td colspan="6">SON: <font size="-2"><? echo strtoupper($Letras)?></td></tr>

</table>

<br /><br /><br />
<table border="0" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td>_________________________________<br /><strong><?echo $Usuario?>
</table><br><font size="-1">
<?
	echo "<em>$fila55[0]</em>";
?></font>
</body>