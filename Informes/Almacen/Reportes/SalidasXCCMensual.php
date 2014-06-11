<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("Consumo/ObtenerSaldos.php");
	$ND=getdate();

	$FechaIni="$Anio-$MesIni-$DiaIni";
	$FechaFin="$Anio-$MesFin-$DiaFin";
	
	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
	$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,$FechaIni,$FechaFin);

	$Meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre","TOTAL ANUAL");

	$cons="Select centrocostos,Codigo from Central.CentrosCosto where Compania='$Compania[0]' and Anio=$Anio and Tipo='Detalle'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$CentrosCostos[$fila[1]]=$fila[0];
	}


	$cons0="select max(Fecha),AutoId,vrcosto from consumo.movimiento where Anio=$Anio 
	and TipoComprobante='Entradas' and Estado='AC' and AlmacenPpal='$AlmacenPpal' Group By AutoId,VrCosto Order By Autoid";
	$res0=ExQuery($cons0);
	while($fila0=ExFetch($res0))
	{
		$PrMax[$fila0[1]]=$fila0[2];
	}


?>
<body>
<table  border=1 cellpadding=0 style='font : normal normal small-caps 11px Tahoma;' bordercolor="#e5e5e5">
<tr><td>Mes</td><td>Centro Costo</td><td>Agrupacion</td><td>Codigo</td><td>Elemento</td><td>Cantidad</td><td>$ Promedio</td><td>$ Ultimo </td></tr>
</body>

<?
	$cons="select date_part('month',Movimiento.Fecha),centrocosto,CodProductos.Grupo,Movimiento.AutoId,(NombreProd1 || ' ' || UnidadMedida || ' ' || Presentacion) as Nombre,sum(Cantidad)
	from consumo.movimiento,consumo.codproductos 
	where 
	movimiento.autoid=codproductos.autoid and
	movimiento.AlmacenPpal='$AlmacenPpal' and codproductos.almacenppal='$AlmacenPpal'
	and Fecha>='$FechaIni' and Fecha<='$FechaFin' and Movimiento.Estado='AC'
	and Movimiento.Compania=CodProductos.Compania and CodProductos.Compania='$Compania[0]'
	and CodProductos.Anio=Movimiento.Anio and Movimiento.TipoComprobante='Salidas'
	Group By date_part('month',Fecha),centrocosto,CodProductos.Grupo,Movimiento.AutoId,Nombre

	Order By date_part('month',Fecha),centrocosto,Grupo,Nombre";

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$CantFinal=$VrSaldoIni[$fila[3]][0]+$VrEntradas[$fila[3]][0]-$VrSalidas[$fila[3]][0]+$VrDevoluciones[$fila[3]][0];
		$SaldoFinal=$VrSaldoIni[$fila[3]][1]+$VrEntradas[$fila[3]][1]-$VrSalidas[$fila[3]][1]+$VrDevoluciones[$fila[3]][1];
		if($CantFinal>0){$CostoUnidad=$SaldoFinal/$CantFinal;}

		echo "<tr><td>".$Meses[$fila[0]]."</td><td>".$CentrosCostos[$fila[1]]."</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td align='right'>".number_format($fila[5])."</td><td align='right'>".number_format($CostoUnidad)."</td><td align='right'>".number_format($PrMax[$fila[3]])."</td></tr>";
	}
	
?>
