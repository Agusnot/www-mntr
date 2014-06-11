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
<form name="FORMA" method="post">
Mostrar Solo : 
<select name="Grupo" onChange="document.FORMA.submit();">
<option value="%"></option>
<?
	$consPrev="Select Grupo from Consumo.CodProductos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio Group By Grupo";
	$resPrev=ExQuery($consPrev);
	while($filaPrev=ExFetch($resPrev))
	{
		if($Grupo==$filaPrev[0]){echo "<option selected value='$filaPrev[0]'>$filaPrev[0]</option>";}
		else{echo "<option value='$filaPrev[0]'>$filaPrev[0]</option>";}
	}
?>
</select>
<?
	function Encabezados()
	{
		global $Compania;global $Fecha;global $NumPag;global $TotPaginas;global $ND;
		?>
		<table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
		<tr><td colspan="13"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>EXISTENCIAS GENERALES - <? echo $AlmacenPpal?><br>Corte a: <?echo $Fecha?></td></tr>
		<tr><td colspan="13" align="right">Fecha de Impresi&oacute;n <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
<td rowspan="2">Codigo</td><td rowspan="2">Nombre</td><td colspan="2">Saldo Inicial</td>
<td colspan="5">Movimientos Periodo</td><td colspan="2">Saldo Final</td><td rowspan="2">Costo Unidad</td></tr>
<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
<?
?>
<td>Cantidad</td><td>Valor</td><td>Entradas</td><td>Valor</td><td>Salidas</td><td>Valor</td><td>Devoluciones</td><td>Cantidad</td><td>Valor</td></tr>
		
<?	
	}

	Encabezados();
	$consPrev="Select Grupo from Consumo.CodProductos where Grupo Like '$Grupo%' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' Group By Grupo";
	$resPrev=ExQuery($consPrev);
	while($filaPrev=ExFetch($resPrev))
	{
	echo "<tr><td colspan='13' bgcolor='#e5e5e' align='center'><strong>$filaPrev[0]</td></tr>";$NumRec++;
	$cons="Select Codigo1,NombreProd1,UnidadMedida,Presentacion,AutoId from Consumo.CodProductos 
	where Grupo='$filaPrev[0]' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio 
	order by NombreProd1,UnidadMedida,Presentacion";
	$res=ExQuery($cons);
	$TotVrSaldoIni1=0;$TotVrEntradas1=0;$TotVrSalidas1=0;$TotSaldoFinal=0;
	while($fila=ExFetch($res))
	{
		if($NumRec>=$Encabezados)
		{
			echo "</table><P>&nbsp;</P>";
			$NumPag++;
			Encabezados();
			$NumRec=0;
		}
		$CantFinal=$VrSaldoIni[$fila[4]][0]+$VrEntradas[$fila[4]][0]-$VrSalidas[$fila[4]][0]+$VrDevoluciones[$fila[4]][0];
		$SaldoFinal=$VrSaldoIni[$fila[4]][1]+$VrEntradas[$fila[4]][1]-$VrSalidas[$fila[4]][1]+$VrDevoluciones[$fila[4]][1];
		if($CantFinal>0){$CostoUnidad=$SaldoFinal/$CantFinal;}
		else{$CostoUnidad=0;}
		?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><?
		echo"<td align='center'>$fila[0]</td><td>$fila[1] $fila[2] $fila[3]</td>
		<td align='right'>".number_format($VrSaldoIni[$fila[4]][0],2)."</td><td align='right'>".number_format($VrSaldoIni[$fila[4]][1],2)."</td>
		<td align='right'>".number_format($VrEntradas[$fila[4]][0],2)."</td><td align='right'>".number_format($VrEntradas[$fila[4]][1],2)."</td>
		<td align='right'>".number_format($VrSalidas[$fila[4]][0],2)."</td><td align='right'>".number_format($VrSalidas[$fila[4]][1],2)."</td>
                <td align='right'>".number_format($VrDevoluciones[$fila[4]][0],2)."</td>
		<td align='right'>".number_format($CantFinal,2)."</td><td align='right'>".number_format($SaldoFinal,2)."</td>
		<td align='right'>".number_format($CostoUnidad,2)."</td></tr>";
		$NumRec++;
		$TotVrSaldoIni1=$TotVrSaldoIni1+$VrSaldoIni[$fila[4]][1];
		$TotVrEntradas1=$TotVrEntradas1+$VrEntradas[$fila[4]][1];
		$TotVrSalidas1=$TotVrSalidas1+$VrSalidas[$fila[4]][1]-$VrDevoluciones[$fila[4]][1];
                
		$TotSaldoFinal=$TotSaldoFinal+$SaldoFinal;
		
	}
	echo "<tr bgcolor='#e5e5e5' style='font-weight=bold; font-size=12px'><td colspan='2' align='right'>TOTALES</td>
	<td align='right'></td><td align='right'>".number_format($TotVrSaldoIni1,2)."</td>
	<td align='right'></td><td align='right'>".number_format($TotVrEntradas1,2)."</td>
	<td align='right'></td><td align='right'>".number_format($TotVrSalidas1,2)."</td>
        
	<td align='right'></td><td align='right'>".number_format($TotSaldoFinal,2)."</td><td align='right'></td></tr>";
		$TotVrSaldoIni1=0;
		$TotVrEntradas1=0;
		$TotVrSalidas1=0;
		$TotSaldoFinal=0;
	}
?>
</table>
</form>
</body>
</html>
