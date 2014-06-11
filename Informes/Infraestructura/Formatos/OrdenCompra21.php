<?
	session_start();
	include("Funciones.php");
	$cons="Select Tipo,NumeroOrdenCompra,FechaOrdenCompra,Nombre,Caracteristicas,Marca,Grupo,AutoId,CostoInicial,
	VrIVA,(CostoInicial+VrIVA),UsuarioCrea,IncluyeIVA,PorcIVA,EstadoTipo,Cedula 
	from InfraEstructura.CodElementos
	where CodElementos.Compania='$Compania[0]' and 
	Tipo='$Tipo' and NumeroOrdenCompra=$Numero";
	$res=ExQuery($cons);echo ExError();
	$fila=ExFetch($res);
	$Fecha=$fila[2];$Usuario=$fila[11];
	if($fila[11]=="ANULADO"){echo "<img style='position:absolute;left:100px;top:100px;' src='/Imgs/Anulado.gif'>";}
	$cons2="Select PrimApe,SegApe,PrimNom,SegNom,Direccion,Telefono from Central.Terceros where Identificacion='$fila[15]' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError();
	$fila2=ExFetch($res2);
	$NomTercero="$fila2[0] $fila2[1] $fila2[2] $fila2[3]";$Direccion=$fila2[4];$Telefono=$fila2[5];
	
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

<table bordercolor='#e5e5e5' border="0" style='font : normal normal small-caps 14px Tahoma;' align="center">
<tr><td bgcolor="#e5e5e5"><strong><font size="3">
<div style="cursor:hand" onClick="location.href='OrdenCompra2.php?Analisis=1&Numero=<? echo $Numero?>&Comprobante=<? echo $Comprobante?>&AlmacenPpal=<? echo $AlmacenPpal?>&AnioComp=<? echo $Anio?>'">
<? echo $fila[0]?></div></td></tr><tr><td align="center"><? echo $fila[1]?></td></tr>
</table>


<table border="1" bordercolor="white" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td bgcolor="#e5e5e5">Fecha</td><td><?echo $fila[2]?></td>
<td></td><td></td>



</td>
</tr>


<tr><td bgcolor="#e5e5e5">Proveedor</td><td><?echo $NomTercero?></td><td bgcolor="#e5e5e5">Identificacion</td><td><? echo $fila[15]?></td>
<tr><td bgcolor="#e5e5e5">Direccion</td><td><?echo $Direccion?></td>
<td bgcolor="#e5e5e5">Telefono</td><td><?echo $Telefono?></td></tr>

</table>

<br /><br /><center>
<font style="font : 12px Tahoma;">
De acuerdo a su cotizacion, sirvase enviar con destino a <? echo $Compania[0]?> los siguientes articulos:<br><br>
</font></center>
<table border="1" bordercolor="#e5e5e5" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>Nombre</td><td>Marca</td><td>Grupo</td><td>Costo</td><td>VrIva</td><td>Total</td></tr>
<?
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[13] && $fila[13]!=0)
		{
			if($fila[12]==1)
			{
				$fila[9] = $fila[10] * $fila[13]/100;
				$fila[8] = $fila[10] - $fila[9];	
			}	
		}
		
		echo "<tr><td>$fila[3]</td><td>$fila[5]</td><td>$fila[6]</td>
		<td align='right'>".number_format($fila[8],2)."</td>
		<td align='right'>".number_format($fila[9],2)."</td>
		<td align='right'>".number_format($fila[10],2)."</td></tr>";	
		$SubTotal=$SubTotal+$fila[8];
		$IVA=$IVA+$fila[9];
		$Total=$Total+$fila[10];
	}
	echo "<tr><td colspan='2'></td><td bgcolor='#e5e5e5' align='right'><strong>TOTALES</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($SubTotal,2)."</td>
	<td align='right' bgcolor='#e5e5e5'><strong>".number_format($IVA,2)."</td>
	<td align='right' bgcolor='#e5e5e5'><strong>".number_format($Total,2)."</td></tr>";
	$Letras=NumerosxLet(round($Total,0));
	$cons55="Select Mensaje1 from Consumo.Comprobantes where Compania='$Compania[0]' and Comprobante='$Comprobante'";
	$res55=ExQuery($cons55);echo ExError();
	$fila55=ExFetch($res55);
?>
<tr><td colspan="6">SON: <font size="-2"><? echo strtoupper($Letras)?></td></tr>
<tr><td colspan="5">

</table>

<br /><br /><br />
<table border="0" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td>_________________________________<br /><strong>Elaboro<br><? echo $Usuario?></strong></td>
<td style="width:100px;">
<td>_________________________________<strong><br>Aprobo<br>Director General
</tr>
</table><br><font size="-1">
<?
	echo "<em>$fila55[0]</em>";
?></font>
</body>