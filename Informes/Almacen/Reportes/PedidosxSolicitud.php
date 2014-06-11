<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("Consumo/ObtenerSaldos.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	$FechaIni="$Anio-$MesIni-$DiaIni";
	$FechaFin="$Anio-$MesFin-$DiaFin";

	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
	$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" width="100%" bordercolor="#e5e5e5">
<?

	$cons20="Select sum(Cantidad),AutoId from Consumo.Movimiento where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and TipoComprobante='Salidas' 
	and Fecha>='$FechaIni' and Fecha<='$FechaFin'
	Group By AutoId";
	$res20=ExQuery($cons20);
	while($fila20=ExFetch($res20))
	{
		$SalidasProd[$fila20[1]]=$fila20[0];
	}
	echo "<tr bgcolor='#e5e5e5' style='font-weight:bold'><td>Cod</td><td>Producto</td><td>Aprob</td><td>Entr</td><td>Pend</td><td>Exist</tr>";

	$cons="Select sum(CantAprobada),SolicitudConsumo.AutoId,NombreProd1,Presentacion,UnidadMedida
	from Consumo.SolicitudConsumo,Consumo.CodProductos
	where SolicitudConsumo.AutoId=CodProductos.AutoId and  SolicitudConsumo.Estado='Aprobada' and SolicitudConsumo.Compania='$Compania[0]' and SolicitudConsumo.AlmacenPpal='$AlmacenPpal' 
	and Fecha>='$FechaIni' and Fecha<='$FechaFin'
	and CodProductos.AlmacenPpal='$AlmacenPpal' and CodProductos.Compania='$Compania[0]'
	Group By SolicitudConsumo.AutoId,NombreProd1,Presentacion,UnidadMedida Order By NombreProd1,Presentacion,UnidadMedida";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$CantExistencias=$VrSaldoIni[$fila[1]][0]+$VrEntradas[$fila[1]][0]-$VrSalidas[$fila[1]][0];
		$Entregas=$SalidasProd[$fila[1]];if(!$Entregas){$Entregas=0;}$Pend=$fila[0]-$Entregas;
		if($CantExistencias<$Pend){
		echo "<tr><td>$fila[1]</td><td>$fila[2] $fila[3] $fila[4]</td><td align='right'>".number_format($fila[0],2)."</td><td align='right'>".number_format($Entregas,2)."</td><td align='right'>".number_format($Pend,2)."</td><td align='right'>".number_format($CantExistencias,2)."</td></tr>";}
	}
		
	echo "</table>";
?>
</table>
</body>
</html>
