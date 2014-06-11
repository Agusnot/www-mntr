<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("Consumo/ObtenerSaldos.php");
	$FechaIni="$Anio-$MesIni-$DiaIni";
	$FechaFin="$Anio-$MesFin-$DiaFin";
	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
	$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
        $VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$ND=getdate();

?><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>


<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<body>
<?
	function Encabezados()
	{
		global $Compania;global $Fecha;global $NumPag;global $TotPaginas;global $ND;
		?>
		<table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
		<tr><td colspan="11"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>INFORME CONTABLE - <? echo $AlmacenPpal?><br>Corte a: <?echo $Fecha?></td></tr>
		<tr><td colspan="11" align="right">Fecha de Impresi&oacute;n <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>

</table>
<table border="1" bordercolor="white" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
<td>Grupo</td><td>Concepto</td><td>Saldo Inicial</td><td>Entradas</td><td>Salidas</td><td>Saldo Final</td></tr>		
<?	}

	Encabezados();
	$cons="Select AutoId,Grupo from Consumo.CodProductos where Compania='$Compania[0]' 
	and AlmacenPpal='$AlmacenPpal' and Anio=$Anio order by Grupo";
	$res=ExQuery($cons);
	$TotVrSaldoIni1=0;$TotVrEntradas1=0;$TotVrSalidas1=0;$TotSaldoFinal=0;
	while($fila=ExFetch($res))
	{
//		$SaldoFinal=$VrSaldoIni[$fila[4]][1]+$VrEntradas[$fila[4]][1]-$VrSalidas[$fila[4]][1];
		$SIxGR[$fila[1]]=$SIxGR[$fila[1]]+$VrSaldoIni[$fila[0]][1];
		$ENTxGR[$fila[1]]=$ENTxGR[$fila[1]]+$VrEntradas[$fila[0]][1];
                if(!$VrDevoluciones[$fila[0]][1]){$VrDevoluciones[$fila[0]][1] = 0;}
		$SALxGR[$fila[1]]=$SALxGR[$fila[1]]+$VrSalidas[$fila[0]][1]-$VrDevoluciones[$fila[0]][1];
	}


	$cons2="Select sum(Movimiento.TotCosto),Movimiento.Grupo,CentroCosto from Consumo.Movimiento,Consumo.CodProductos
	where TipoComprobante='Devoluciones' and CodProductos.AutoId=Movimiento.AutoId and Movimiento.Compania='$Compania[0]'
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.Compania='$Compania[0]' 
	and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio
	and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.Estado='AC' 
	and Fecha>='$FechaIni' and Fecha<='$FechaFin'
	Group By Movimiento.Grupo,CentroCosto";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		//$cons3="Select centrocostos from Central.CentrosCosto where Codigo='$fila2[2]' and Compania='$Compania[0]' and Anio=$Anio";
		//$res3=ExQuery($cons3);
		//$fila3=ExFetch($res3);
		$DevolucionesxGrp[$fila2[1]][$fila2[2]]=$DevolucionesxGrp[$fila2[1]][$fila2[2]] + $fila2[0];
		//$i++;
	}

        $cons2="Select sum(Movimiento.TotCosto)+sum(Movimiento.VrIVA),Movimiento.Grupo,CentroCosto from Consumo.Movimiento,Consumo.CodProductos
	where TipoComprobante='Salidas' and CodProductos.AutoId=Movimiento.AutoId and Movimiento.Compania='$Compania[0]' 
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.Compania='$Compania[0]' 
	and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio
	and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.Estado='AC' 
	and Fecha>='$FechaIni' and Fecha<='$FechaFin'
	Group By Movimiento.Grupo,CentroCosto";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
            $fila2[0] = $fila2[0] - $DevolucionesxGrp[$fila2[1]][$fila2[2]];
            $cons3="Select centrocostos from Central.CentrosCosto where Codigo='$fila2[2]' and Compania='$Compania[0]' and Anio=$Anio";
            $res3=ExQuery($cons3);
            $fila3=ExFetch($res3);
            $SalidasxGrp[$fila2[1]][$i]=array($fila2[0],$fila3[0],$fila2[2]);
            $i++;
	}

	$cons2="Select sum(Movimiento.TotCosto),Movimiento.Grupo from Consumo.Movimiento,Consumo.CodProductos
	where TipoComprobante='Salida Ajuste' and CodProductos.AutoId=Movimiento.AutoId and Movimiento.Compania='$Compania[0]' 
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.Compania='$Compania[0]' 
	and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio
	and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.Estado='AC' 
	and Fecha>='$FechaIni' and Fecha<='$FechaFin'
	Group By Movimiento.Grupo";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$SalidasxAjuste[$fila2[1]][$i]=array($fila2[0],"AJUSTE DE INVENTARIO (SALIDA)","");
		$i++;
	}
	echo "</br></br>";
	$cons2="Select sum(Movimiento.TotCosto),Movimiento.Grupo from Consumo.Movimiento,Consumo.CodProductos
	where TipoComprobante='Ingreso Ajuste' and CodProductos.AutoId=Movimiento.AutoId and Movimiento.Compania='$Compania[0]' 
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.Compania='$Compania[0]' 
	and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio
	and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.Estado='AC' 
	and Fecha>='$FechaIni' and Fecha<='$FechaFin'
	Group By Movimiento.Grupo";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$IngresosxAjuste[$fila2[1]][$i]=array($fila2[0],"AJUSTE DE INVENTARIO (ENTRADA)","");
		$i++;
	}

	$cons="Select Grupo from Consumo.CodProductos where Estado='AC' and Compania='$Compania[0]' 
	and AlmacenPpal='$AlmacenPpal' and Anio=$Anio Group by Grupo Order By Grupo";
	$res=ExQuery($cons);
	$TotVrSaldoIni1=0;$TotVrEntradas1=0;$TotVrSalidas1=0;$TotSaldoFinal=0;
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td>";
		echo "<td></td>";
		echo "<td align='right'><strong>".number_format($SIxGR[$fila[0]],2)."</td>";
		echo "<td align='right'><strong>".number_format($ENTxGR[$fila[0]],2)."</td>";
		echo "<td align='right'><strong>".number_format($SALxGR[$fila[0]],2)."</td>";
		$SaldoF[$fila[0]]=$SIxGR[$fila[0]]+$ENTxGR[$fila[0]]-$SALxGR[$fila[0]];
		echo "<td align='right'><strong>".number_format($SaldoF[$fila[0]],2)."</td>";
		echo "</tr>";
		
		if(count($SalidasxGrp[$fila[0]])>0){
		foreach($SalidasxGrp[$fila[0]] as $SalxCC)
		{
			echo "<tr><td></td><td>".$SalxCC[1] . " " . $SalxCC[2]."</td><td></td><td></td><td align='right'>".number_format($SalxCC[0],2)."</td></tr>";
		}}
		if(count($SalidasxAjuste[$fila[0]])>0){
		foreach($SalidasxAjuste[$fila[0]] as $SalxCC)
		{
			echo "<tr><td></td><td>".$SalxCC[1] . " " . $SalxCC[2]."</td><td></td><td align='right'></td><td align='right'>".number_format($SalxCC[0],2)."</td></tr>";
		}}
		if(count($IngresosxAjuste[$fila[0]])>0){
		foreach($IngresosxAjuste[$fila[0]] as $IngxCC)
		{
			echo "<tr><td></td><td>".$IngxCC[1] . " " . $IngxCC[2]."</td><td></td><td align='right'>".number_format($IngxCC[0],2)."</td><td align='right'></td></tr>";
		}}
		$TotSI=$TotSI+$SIxGR[$fila[0]];
		$TotEnt=$TotEnt+$ENTxGR[$fila[0]];
		$TotSal=$TotSal+$SALxGR[$fila[0]];
		$TotSF=$TotSF+$SaldoF[$fila[0]];
	}
	echo "<tr bgcolor='#e5e5e5' align='right' style='font-weight:bold'><td colspan=2>TOTALES</td><td>".number_format($TotSI,2)."</td><td>".number_format($TotEnt,2)."</td><td>".number_format($TotSal,2)."</td><td>".number_format($TotSF,2)."</td></tr>";
?>
</table>
</body>
</html>
