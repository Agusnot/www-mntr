<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("GeneraValoresEjecucion2.php");

	$Apropiacion=GeneraApropiacion();
	$Valores=GeneraValores();
	$cons="Select * from Central.Meses where Numero=$MesIni";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesIniLet=$fila[0];
	$cons="Select * from Central.Meses where Numero=$MesFin";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesFinLet=$fila[0];
	
	$ND=getdate();
?>

	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Ejecución Presupuestal de Ingresos<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>

<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Codigo Presupuestal</td><td>Nombre Rubro</td><td>Presupuesto Inicial</td><td>Adiciones</td><td>Reducciones</td><td>Recaudos</td></tr>

<?
	$cons="Select Cuenta,Nombre,SIA from Presupuesto.PlanCuentas where Cuenta like '1%' and Tipo='Detalle' and Anio=$Anio Order By Cuenta";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{

		$Cuenta=$fila[0];
		$ApropIni=$Apropiacion[$fila[0]];
		$fila[1]=str_replace(",","",$fila[1]);

		if($Valores["Adicion"][$fila[0]]["Credito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["Credito"];}
		elseif($Valores["Adicion"][$fila[0]]["CCredito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["CCredito"];}
		else{$Adiciones=0;}

		if($Valores["Reduccion"][$fila[0]]["Credito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["Credito"];}
		elseif($Valores["Reduccion"][$fila[0]]["CCredito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["CCredito"];}
		else{$Reducciones=0;}

		$Creditos=$Valores["Traslado"][$fila[0]]["Credito"];
		$CCreditos=$Valores["Traslado"][$fila[0]]["CCredito"];
		$ApropDef=$ApropIni+$Adiciones-$Reducciones+$Creditos-$CCreditos;


		$IngTotales=$Valores["Ingreso presupuestal"][$fila[0]]["CCredito"]-$Valores["Disminucion a ingreso presupuestal"][$fila[0]]["Credito"];

		echo "<tr><td><strong>'$fila[2]</strong>$fila[0]</td><td>".$fila[1]."</td>
		<td align='right'>".number_format($ApropIni,2)."</td><td align='right'>".number_format($Adiciones,2)."</td><td align='right'>".number_format($Reducciones,2)."</td>";
		echo "<td align='right'>".number_format($IngTotales,2)."</td>";
		$NumRec++;
		$Archivo=$Archivo."$fila[2]$fila[0],$fila[1],$ApropIni,$Adiciones,$Reducciones,$IngTotales<br>";

	}

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("formato_200801_f06_agr.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td><a href='formato_200801_f06_agr.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";
?>

