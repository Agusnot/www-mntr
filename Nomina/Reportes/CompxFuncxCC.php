<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$Vinculacion==""){$Vin="and nomina.vinculacion='$Vinculacion' and tiposvinculacion.tipovinculacion=nomina.vinculacion";}
	$cons="select mes from central.meses where numero='$Mes'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesR=$fila[0];
?>
<html>
<body background="/Imgs/Fondo.jpg">
<font size=3 face='Tahoma'>
<? echo strtoupper($Compania[0]);?><br></font>
<font size=2 face='Tahoma'>
<? echo $Compania[1];?><br>
Comprobantes por Funcionario/Centro Costos</font><br>
Mes: <? echo $MesR?> / <? echo $Anio?>
<center><br><br>
<table border="1" bordercolor="#ffffff" width="80%" style='font : normal normal small-caps 14px Tahoma;'/>
<?
	$TotSumDed=0;$TotSumDev=0;$cont=0;
	$consPrev="Select centrocostos from central.centroscosto where compania='$Compania[0]' and anio='$Anio' and tipo='Detalle' Order By CentroCostos";
//	echo $consPrev;
	$resPrev=ExQuery($consPrev);
	while($filaPrev=ExFetch($resPrev))
	{
//		echo $filaPrev[0]."<br>";
	//------	BUSQUEDA DE PERSONAS Y CENTROS DE COSTOS		
		$consNom="select terceros.identificacion,contratos.fecinicio,contratos.fecfin,terceros.primnom,terceros.segnom,terceros.primape,terceros.segape,
	tiposvinculacion.tipovinculacion,contratos.numero,porcentaje,cc from central.terceros,nomina.contratos,nomina.tiposvinculacion,nomina.centrocostos,nomina.nomina
	where terceros.compania='$Compania[0]' and terceros.identificacion=contratos.identificacion and contratos.identificacion=centrocostos.identificacion and contratos.tipovinculacion=tiposvinculacion.codigo 
	and (terceros.tipo='Empleado' or regimen='Empleado') and contratos.estado='Activo' and centrocostos.numcontrato=contratos.numero and centrocostos.cc='$filaPrev[0]' $Vin
	group by terceros.identificacion,contratos.fecinicio,contratos.fecfin,terceros.primnom,terceros.segnom,terceros.primape,terceros.segape, 
	tiposvinculacion.tipovinculacion,contratos.numero,porcentaje,cc order by primape";
//	echo $consNom."<br>";
		$resNom=ExQuery($consNom);
		$ContNom=ExNumRows($resNom);
//		echo $ContNom."<br>";
		if($ContNom>0)
		{
			?>
			<tr bgcolor='#EEF6F6' align="center"><td colspan="5"><font style="font-weight:bold"><? echo $filaPrev[0];?></font></td></tr>
			<tr bgcolor='#EEF6F6' align="center" style="font-weight:bold"><td>Identificacion</td><td>Nombre</td><td>Devengados</td><td>Deducidos</td><td>Neto</td></tr>
			<?
		}
		$SumDed=0;$SumDev=0;
		while($fila=ExFetch($resNom))
		{
			
			$cons1="select sum(valor) from nomina.nomina where identificacion='$fila[0]' and (Movimiento='Deducidos' or Movimiento='PostDeducidos')
		and (ClaseRegistro='AutoRegistro' Or ClaseRegistro='Valor')";
			$res1=ExQuery($cons1);
			$fila1=ExFetch($res1);
			$cons2="select sum(valor) from nomina.nomina where identificacion='$fila[0]' and (Movimiento='Devengados' or Movimiento='PostDevengados')
			and (ClaseRegistro='AutoRegistro' Or ClaseRegistro='Valor')";
			$res2=ExQuery($cons2);
			$fila2=ExFetch($res2);
			$porc=$fila[9]*0.01;
//			echo $fila[0]."    ".$porc."<br>";
			?>
			<tr align="center"><td><? echo $fila[0];?></td><td><? echo $fila[3]." ".$fila[4]." ".$fila[5]." ".$fila[6];?></td><td><? $fila2[0]=$fila2[0]*$porc; echo "$ ".$fila2[0];?></td>
			<td><? $fila1[0]=$fila1[0]*$porc; echo "$ ".$fila1[0];?></td><td><? $NetoP=$fila2[0]-$fila1[0]; echo "$ ".$NetoP;?></td></tr>
			<?
			$SumDev=$SumDev+$fila2[0];
			$SumDed=$SumDed+$fila1[0];
			$cont++;
		}
		if($SumDed>0||$SumDev>0)
		{
			$TotSumDev=$TotSumDev+$SumDev;
			$TotSumDed=$TotSumDed+$SumDed;
			
			?>
			<tr bgcolor="#C4C4C4"><td colspan="2">TOTALES</td><td><? echo "$ ".$SumDev;?></td><td><? echo "$ ".$SumDed;?></td><td><? $Neto=$SumDev-$SumDed; echo "$ ".$Neto; ?></td></tr>
             <tr><td><? // echo "$TotSumDev+$SumDev   echo " # $cont<br>";?>----------------------------------------------------</td></tr>
			<?
		}
	}
	if($TotSumDed>0&&$TotSumDev>0)
	{
		?>
        <tr  bgcolor="#C4C4C4" align="center"><td colspan="5"><font size="+2">GRAN TOTAL</font></td></tr>
		<tr bgcolor="#C4C4C4"><td colspan="2">TOTALES</td><td><? echo "$ ".$TotSumDev;?></td><td><? echo "$ ".$TotSumDed;?></td><td><? $NetoT=$TotSumDev-$TotSumDed; echo "$ ".$NetoT; ?></td></tr>
		<?
	}
?>
</table>
</center>
</body>
</html>	