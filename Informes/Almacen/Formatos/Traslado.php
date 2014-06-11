<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="Select Comprobante,Numero,Fecha,Codigo1,Cantidad,NombreProd1,Presentacion,UnidadMedida,Cedula,Movimiento.AutoId,VrCosto,Movimiento.UsuarioCre,TotCosto,IDTraslado
	from Consumo.Movimiento,Consumo.CodProductos 
	where Movimiento.AutoId=CodProductos.AutoId and Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
	and Movimiento.Anio = $Anio and CodProductos.Anio = $Anio
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' and Numero='$Numero' 
	and TipoTraslado='O' order by IDTraslado";
	$res=ExQuery($cons);echo ExError();
	$fila=ExFetch($res);
	$Fecha=$fila[0];$Usuario=$fila[11];
	//if($fila[7]=="Anulado"){echo "<img style='position:absolute;left:100px;top:100px;' src='/Imgs/Anulado.gif'>";}
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
<tr><td bgcolor="#e5e5e5">Fecha</td><td><?echo $fila[2]?></td>
<td></td><td></td>
<td rowspan="4">
<table bordercolor='#e5e5e5' border="1" style='font : normal normal small-caps 14px Tahoma;' align="right">
<tr><td bgcolor="#e5e5e5"><? echo $fila[0]?></td></tr><tr><td align="center"><? echo $fila[1]?></td></tr>
</table>
</td>
</tr>
</table>

<br /><br />
<table border="1" bordercolor="#e5e5e5" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold;"><td colspan="4">ORIGEN</td><td colspan="4">DESTINO</td></tr>
<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>Almacen</td><td>Codigo</td><td>Nombre</td><td align="right">Cantidad</td>
<td>Almacen</td><td>Codigo</td><td>Nombre</td></tr>
<?
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$AlmacenPpal</td><td>$fila[3]</td><td>$fila[5] $fila[6] $fila[7]</td><td>$fila[4]</td>";
		$cons1 = "Select Codigo1,NombreProd1,Presentacion,UnidadMedida,Movimiento.AutoId,VrCosto,AlmacenPpalD
				from Consumo.Movimiento,Consumo.CodProductos 
				where Movimiento.AutoId=CodProductos.AutoId and Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
				and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal=Movimiento.AlmacenPpalD
				and Movimiento.Anio = $Anio and CodProductos.Anio = $Anio 
				and Comprobante='$Comprobante' and Numero='$Numero' and TipoTraslado='D'
				and IDTraslado='$fila[13]'"; //echo "$cons1<br><br>";
		$res1 = ExQuery($cons1);
		$fila1 = ExFetch($res1);
		echo "<td>$fila1[6]</td><td>$fila1[0]</td><td>$fila1[1] $fila1[2] $fila1[3]</td></tr>";
	}
	$cons55="Select Mensaje1 from Consumo.Comprobantes where Compania='$Compania[0]' and Comprobante='$Comprobante'";
	$res55=ExQuery($cons55);echo ExError();
	$fila55=ExFetch($res55);
?>
</table>
</table>

<br /><br /><br />
<table border="0" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td>_________________________________<br /><strong><?echo $Usuario?>
</table><br><font size="-1">
<?
	echo "<em>$fila55[0]</em>";
?></font>
</body>