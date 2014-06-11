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
	<tr><td colspan="13"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Ejecución de Gastos<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="13" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
<tr bgcolor="#e5e5e5" align="center"><td>Codigo Presupuestal</td><td>Nombre Rubro</td><td>Codigo Programa</td><td>Apropiación Inicial</td><td>Creditos</td><td>Contra Creditos</td><td>Aplazamientos</td><td>Desaplazamientos</td><td>Reducciones</td><td>Adiciones</td><td>Compromisos Registro Presupuestal</td><td>Obligaciones</td><td>Pagos</td></tr>
<?

	$cons="Select Cuenta,Nombre,SIA from Presupuesto.PlanCuentas where Cuenta like '2%' and Tipo='Detalle' and Anio=$Anio and Vigencia='Actual' Order By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{

		$ApropIni=$Apropiacion[$fila[0]];

		$Cuenta=$fila[0];
		if($Valores["Adicion"][$fila[0]]["Credito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["Credito"];}
		elseif($Valores["Adicion"][$fila[0]]["CCredito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["CCredito"];}
		else{$Adiciones=0;}

		if($Valores["Reduccion"][$fila[0]]["Credito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["Credito"];}
		elseif($Valores["Reduccion"][$fila[0]]["CCredito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["CCredito"];}
		else{$Reducciones=0;}

		$Creditos=$Valores["Traslado"][$fila[0]]["Credito"];
		$CCreditos=$Valores["Traslado"][$fila[0]]["CCredito"];
		$ApropDef=$ApropIni+$Adiciones-$Reducciones+$Creditos-$CCreditos;


		$TotDispo=$Valores["Disponibilidad"][$fila[0]]["Credito"]-$Valores["Disminucion a disponibilidad"][$fila[0]]["CCredito"];
		$TotCompromisos=$Valores["Compromiso presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a compromiso"][$fila[0]]["CCredito"];
		$TotObligaciones=$Valores["Obligacion presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a obligacion presupuestal"][$fila[0]]["CCredito"];
		$Egresos=$Valores["Egreso presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a egreso presupuestal"][$fila[0]]["CCredito"];

		$DispoSinAfecta=$TotDispo-$TotCompromisos;
		$CompromisosSinAfecta=$TotCompromisos;
		$ObligacSinAfectar=$TotObligaciones-$Egresos;
		$SaldoDispo=$ApropDef-$TotDispo;

		
		echo "<tr><td><strong>$fila[2]</strong>$fila[0]</td><td>".$fila[1]."</td><td>ND</td>
		<td align='right'>".number_format($ApropIni,2)."</td><td align='right'>".number_format($Creditos,2)."</td><td align='right'>".number_format($CCreditos,2)."</td><td align='right'>0</td><td align='right'>0</td>
		
		<td align='right'>".number_format($Reducciones,2)."</td><td align='right'>".number_format($Adiciones,2)."</td><td align='right'>".number_format($CompromisosSinAfecta,2)."</td>";
		echo "<td align='right'>".number_format($TotObligaciones,2)."</td><td align='right'>".number_format($Egresos,2)."</td></tr>";
		$NumRec++;
		$Archivo=$Archivo."$fila[0]$fila[2],$fila[1],ND,$ApropIni,$Creditos,$CCreditos,0,0,$Reducciones,$Adiciones,$CompromisosSinAfecta,$ObligacSinAfectar,$Egresos<br>";
	}
	
	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("formato_200801_f07_agr.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td colspan=3><a href='formato_200801_f07_agr.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";
	
?>
</table>