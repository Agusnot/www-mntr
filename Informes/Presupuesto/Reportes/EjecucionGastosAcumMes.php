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
	
		$MesIni=$MesFin;
		$Valores2=GeneraValores();



	function Encabezados()
	{
	global $Compania;global $Anio;global $MesIniLet;global $MesFinLet;
	$ND=getdate();
?>

<style>
P{PAGE-BREAK-AFTER: always;}
</style>

<font face="tahoma" style="font-variant:small-caps" style="font-size:11px">
<strong><?echo strtoupper($Compania[0])?></strong><br>
<?echo $Compania[1]?><br>EJECUCION DE GASTOS<br>
Periodo: <? echo $MesIniLet?> a <? echo $MesFinLet?> de <? echo $Anio?><br />
Fecha de Impresion: <?echo "$ND[year]-$ND[mon]-$ND[mday]";?>


<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td rowspan="2">Codigo</td><td rowspan="2">Nombre</td><td rowspan="2">Apropiacion Inicial</td><td colspan="4">Modificaciones</td><td rowspan="2">Apropiación Definitiva</td><td colspan="3">Disponibilidades</td><td colspan="3">Compromisos</td><td colspan="3">Obligaciones</td><td colspan="2">Pagos</td><td rowspan="2">Saldo Disponible</td>
<tr bgcolor="#e5e5e5" align="center"><td>Adiciones</td><td>Reducciones</td><td>Creditos</td><td>Contra Creditos</td><td>Del mes</td><td>Totales</td><td>Sin Afectacion</td><td>Del mes</td><td>Totales</td><td>Sin Afectacion</td><td>Del mes</td><td>Totales</td><td>Sin Afectacion</td><td>Del mes</td><td>Totales</td></tr>
<?
	}
	Encabezados();
	if($Anio){
	if(!$ClaseVigencia){$Vigencia="Actual";}
	if($ClaseVigencia=="CxP" || $ClaseVigencia=="Reservas"){$Vigencia="Anteriores";}
	$cons="Select Cuenta,Nombre,length(Cuenta) as Digitos from Presupuesto.PlanCuentas where Cuenta like '2%' and Anio=$Anio and Vigencia='$Vigencia' and Compania='$Compania[0]' 
	and ClaseVigencia='$ClaseVigencia'
	Group By Cuenta,Nombre
	having length(Cuenta)<=$NoDigitos Order By Cuenta";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{


		if($NumRec>=$Encabezados)
		{
			echo "</table><P>&nbsp;</P>";
			$NumPag++;
			Encabezados();
			$NumRec=0;
		}
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

		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>".$fila[1]."</td>
		<td align='right'>".number_format($ApropIni,2)."</td><td align='right'>".number_format($Adiciones,2)."</td><td align='right'>".number_format($Reducciones,2)."</td><td align='right'>".
		number_format($Creditos,2)."</td><td align='right'>".number_format($CCreditos,2)."</td><td align='right'>".number_format($ApropDef,2)."</td>";

		$TotDispo=$Valores["Disponibilidad"][$fila[0]]["Credito"]-$Valores["Disminucion a disponibilidad"][$fila[0]]["CCredito"];
		$TotDispoPer=$Valores2["Disponibilidad"][$fila[0]]["Credito"]-$Valores2["Disminucion a disponibilidad"][$fila[0]]["CCredito"];

		$TotCompromisos=$Valores["Compromiso presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a compromiso"][$fila[0]]["CCredito"];
		$TotCompromisosPer=$Valores2["Compromiso presupuestal"][$fila[0]]["Credito"]-$Valores2["Disminucion a compromiso"][$fila[0]]["CCredito"];

		$TotObligaciones=$Valores["Obligacion presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a obligacion presupuestal"][$fila[0]]["CCredito"];
		$TotObligacionesPer=$Valores2["Obligacion presupuestal"][$fila[0]]["Credito"]-$Valores2["Disminucion a obligacion presupuestal"][$fila[0]]["CCredito"];

		$Egresos=$Valores["Egreso presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a egreso presupuestal"][$fila[0]]["CCredito"];
		$EgresosPer=$Valores2["Egreso presupuestal"][$fila[0]]["Credito"]-$Valores2["Disminucion a egreso presupuestal"][$fila[0]]["CCredito"];

		$DispoSinAfecta=$TotDispo-$TotCompromisos;
		$CompromisosSinAfecta=$TotCompromisos-$TotObligaciones;
		$ObligacSinAfectar=$TotObligaciones-$Egresos;
		$SaldoDispo=$ApropDef-$TotDispo;

		echo "<td align='right'>".number_format($TotDispoPer,2)."</td><td align='right'>".number_format($TotDispo,2)."</td><td align='right'>".number_format($DispoSinAfecta,2)."</td><td align='right'>".number_format($TotCompromisosPer,2)."</td><td align='right'>".number_format($TotCompromisos,2)."</td>
		<td align='right'>".number_format($CompromisosSinAfecta,2)."</td><td align='right'>".number_format($TotObligacionesPer,2)."</td><td align='right'>".number_format($TotObligaciones,2)."</td><td align='right'>".number_format($ObligacSinAfectar,2)."</td>
		<td align='right'>".number_format($EgresosPer,2)."</td><td align='right'>".number_format($Egresos,2)."</td><td align='right'>".number_format($SaldoDispo,2)."</td>";
		$NumRec++;

	}
	}
?>
</table>