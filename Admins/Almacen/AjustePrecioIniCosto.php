<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/fpdf.php');
	include("Consumo/ObtenerSaldos.php");
	$Compania[0]="Clinica San Juan de Dios";
	$AlmacenPpal="SUMINISTROS";
	$Anio=2011;

$Anio=2011;$MesIni=12;$DiaIni=01;
$MesFin=12;$DiaFin=31;
	$FechaIni="$Anio-$MesIni-$DiaIni";
	$FechaFin="$Anio-$MesFin-$DiaFin";
	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
	$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
    $VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$ND=getdate();

	$cons="Select Codigo1,NombreProd1,UnidadMedida,Presentacion,AutoId from Consumo.CodProductos where Compania='$Compania[0]' 
	and AlmacenPpal='$AlmacenPpal' and Anio=$Anio order by NombreProd1,UnidadMedida,Presentacion";
echo $cons;
	$res=ExQuery($cons);
	$TotVrSaldoIni1=0;$TotVrEntradas1=0;$TotVrSalidas1=0;$TotSaldoFinal=0;
	while($fila=ExFetch($res))
	{
        $CantFinal=$VrSaldoIni[$fila[4]][0]+$VrEntradas[$fila[4]][0]-$VrSalidas[$fila[4]][0]+$VrDevoluciones[$fila[4]][0];
		$SaldoFinal=$VrSaldoIni[$fila[4]][1]+$VrEntradas[$fila[4]][1]-$VrSalidas[$fila[4]][1]+$VrDevoluciones[$fila[4]][1];
		if($CantFinal>0){$CostoUnidad=$SaldoFinal/$CantFinal;}
		else{$CostoUnidad=0;}
		echo $fila[4]."--->".$CostoUnidad."<br>";

		$cons99="Update Consumo.SaldosInicialesxAnio set vrunidad=$CostoUnidad,Cantidad=$CantFinal where AutoId=$fila[4] and Anio=2012 and AlmacenPpal='$AlmacenPpal'";
		$res99=ExQuery($cons99);

		$cons99="Update Consumo.SaldosInicialesxAnio set vrtotal=cantidad*VrUnidad where AutoId=$fila[4] and Anio=2012 and AlmacenPpal='$AlmacenPpal'";
		$res99=ExQuery($cons99);

		$cons99="Update Consumo.Movimiento set vrcosto=$CostoUnidad where AutoId=$fila[4] and Anio=2012 and AlmacenPpal='$AlmacenPpal' and TipoComprobante='Salidas'";
		$res99=ExQuery($cons99);


		$cons99="Update Consumo.Movimiento set totcosto=Cantidad*vrcosto where AutoId=$fila[4] and Anio=2012 and AlmacenPpal='$AlmacenPpal' and TipoComprobante='Salidas'";
		$res99=ExQuery($cons99);
		
	}