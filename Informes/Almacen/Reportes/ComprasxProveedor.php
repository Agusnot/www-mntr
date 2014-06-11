<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<em>
<?
	echo "<strong>$Compania[0] <br> $Compania[1]</strong>";
?><br>
Entradas x Proveedor	<br>
Periodo: <?echo "$PerIni - $PerFin"?>
</em><br><br>
<table border="1" style='font : normal normal small-caps 11px Tahoma;' bordercolor="#ffffff">
<?
	$cons="Select Cedula from Consumo.Movimiento where Fecha>='$PerIni' and Fecha<='$PerFin' and TipoComprobante='Entradas' and Compania='$Compania[0]' 
	and AlmacenPpal='$AlmacenPpal' and Anio=$Anio Group By Cedula";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons4="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila[0]' and Compania='$Compania[0]'";
		$res4=ExQuery($cons4);
		$fila4=ExFetch($res4);
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold'><td colspan=4 align='center'>$fila[0] - $fila4[0] $fila4[1] $fila4[2] $fila4[3]</td></tr>";

		$cons1="Select sum(TotCosto),Movimiento.Grupo, sum(Movimiento.VrIVA), numero from Consumo.Movimiento,Consumo.CodProductos
		where 
		Movimiento.AutoId=CodProductos.AutoId and TipoComprobante='Entradas' and Fecha>='$PerIni' and Fecha<='$PerFin' and Cedula='$fila[0]' 
		and CodProductos.Compania='$Compania[0]' 
		and CodProductos.AlmacenPpal='$AlmacenPpal' 
		and Movimiento.Compania='$Compania[0]'
		and Movimiento.AlmacenPpal='$AlmacenPpal' 
		and Movimiento.Estado='AC'  
		and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio
		and Movimiento.Grupo=CodProductos.Grupo
		Group By Movimiento.Grupo, Movimiento.VrIVA, Numero";
		$res1=ExQuery($cons1);echo ExError();
		echo "<tr style='font-weight:bold' align='center'><td>GRUPO</td><td>ENTRADA No.</td><td>VR COMPRA</td><td>VR IVA</td></tr>";
		$TotalIVA=0;
		while($fila1=ExFetch($res1))
		{
			$TotalIVA=$TotalIVA+$fila1[2];
			echo "<tr><td>$fila1[1]</td><td>$fila1[3]</td><td>".number_format($fila1[0],2)."</td><td align='right'>" . number_format($fila1[2],2) . "</td></tr>";
			$SubTotProv=$SubTotProv+$fila1[0];
		}
		$TotGral=$TotGral+$SubTotProv;
		echo "<tr style='font-weight:bold' align='right' bgcolor='#ffffff'><td></td><td></td><td bgcolor='#e5e5e5'>IVA</td><td bgcolor='#e5e5e5'>" . number_format($TotalIVA,2) . "</td></tr>";
		echo "<tr style='font-weight:bold' align='right' bgcolor='#ffffff'><td></td><td></td><td bgcolor='#e5e5e5'>Subtotal</td><td bgcolor='#e5e5e5'>" . number_format($SubTotProv,2) . "</td></tr>";
		echo "<tr style='font-weight:bold' align='right' bgcolor='#ffffff'><td></td><td></td><td bgcolor='#e5e5e5'>Total de la Compra</td><td bgcolor='#e5e5e5'>" . number_format(($TotalIVA+$SubTotProv),2) . "</td></tr>";
		
		$SubTotProv=0;
	}
		
		echo "<tr height='50'></tr><tr style='font-weight:bold' bgcolor='#ffffff'><td></td><td></td><td bgcolor='#00ffff'>Total Compras del Periodo</td><td align='right' bgcolor='#00ffff'>" . number_format($TotGral,2) . "</td></tr>";

?>
</table>
</body>
</html>
