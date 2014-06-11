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
<?echo $Compania[1]?><br>EJECUCION DE INGRESOS<br>
Periodo: <? echo $MesIniLet?> a <? echo $MesFinLet?> de <? echo $Anio?><br />
Fecha de Impresion: <?echo "$ND[year]-$ND[mon]-$ND[mday]";?>

<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td rowspan="2">Codigo</td><td rowspan="2">Nombre</td><td rowspan="2">Apropiaci&oacute;n Inicial</td><td colspan="2">Modificaciones</td><td rowspan="2">Apropiaci&oacute;n Definitiva</td><td colspan="3">Reconocimientos</td><td colspan="2">Recaudos</td><td rowspan="2">Saldo Apropiacion</td>
<tr bgcolor="#e5e5e5" align="center"><td>Adiciones</td><td>Reducciones</td><td>Del periodo</td><td>Totales</td><td>Sin Afectacion</td><td>Del periodo</td><td>Totales</td></tr>

<?
	}
	Encabezados();
	if($Anio){
	if(!$ClaseVigencia){$Vigencia="Actual";}
	if($ClaseVigencia=="CxP" || $ClaseVigencia=="Reservas"){$Vigencia="Anteriores";}
	$cons="Select Cuenta,Nombre,length(Cuenta) as Digitos from Presupuesto.PlanCuentas where Cuenta like '1%' and Anio=$Anio and Vigencia='$Vigencia' and Compania='$Compania[0]' 
	and ClaseVigencia='$ClaseVigencia'
	Group By Cuenta,Nombre
	having length(Cuenta)<=$NoDigitos Order By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{

		if($NumRec>=$Encabezados)
		{
			echo "</table><P>&nbsp;</P>";
			$NumPag++;
			Encabezados();
			$NumRec=0;
		}

		$Cuenta=$fila[0];
		$ApropIni=$Apropiacion[$fila[0]];

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
		<td align='right'>".number_format($ApropIni,2)."</td><td align='right'>".number_format($Adiciones,2)."</td><td align='right'>".number_format($Reducciones,2)."</td><td align='right'>".number_format($ApropDef,2)."</td>";


		$TotReconoc=$Valores["Reconocimiento presupuestal"][$fila[0]]["CCredito"]-$Valores["Disminucion a reconocimiento"][$fila[0]]["Credito"];
		$TotReconocPer=$Valores2["Reconocimiento presupuestal"][$fila[0]]["CCredito"]-$Valores2["Disminucion a reconocimiento"][$fila[0]]["Credito"];

		$IngTotales=$Valores["Ingreso presupuestal"][$fila[0]]["CCredito"]-$Valores["Disminucion a ingreso presupuestal"][$fila[0]]["Credito"];
		$IngTotalesPer=$Valores2["Ingreso presupuestal"][$fila[0]]["CCredito"]-$Valores2["Disminucion a ingreso presupuestal"][$fila[0]]["Credito"];

		$ReconocSinAfectar=$TotReconoc-$IngTotales;
		$RecxRecaudar=$ApropDef-$TotReconoc;

		echo "<td align='right'>".number_format($TotReconocPer,2)."</td><td align='right'>".number_format($TotReconoc,2)."</td><td align='right'>".number_format($ReconocSinAfectar,2)."</td><td align='right'>".number_format($IngTotalesPer,2)."</td><td align='right'>".number_format($IngTotales,2)."</td>
		<td align='right'>".number_format($RecxRecaudar,2)."</td>";
		$NumRec++;
	}
	}
?>
</table>