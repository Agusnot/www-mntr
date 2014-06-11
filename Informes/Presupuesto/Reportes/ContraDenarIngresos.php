<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	mysql_select_db("Presupuesto");

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
	function Encabezados()
	{
	global $Compania;global $Anio;global $MesIniLet;global $MesFinLet;
?>

<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<img src="/Imgs/ContraDenar.jpg" / style="width:100px;position:absolute">
<center>
<font face="tahoma" style="font-size:12px"><strong>
CONTRALORIA DEPARTAMENTAL DE NARIÑO<BR />
NIT 800.157.830-3</strong>
</font>
<hr />
</center><br />
<font face="tahoma" style="font-variant:small-caps" style="font-size:12px"><strong>
<center>
Ejecución Presupuestal de Ingresos<br />
Periodo: <? echo $MesIniLet?> a <? echo $MesFinLet?> de <? echo $Anio?><br />
Entidad : <? echo $Compania[0]?><br />
<? echo $Compania[1]?><br /><br />
</center>
<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>Codigo Presupuestal</td><td>Concepto del Rubro</td><td>Apropiacion Inicial</td><td>Adiciones</td><td>Reducciones</td><td>Creditos</td><td>Contra Creditos</td><td>Apropiación Definitiva</td><td>Ejecución del Periodo</td></td>

<?
	}
	Encabezados();
	if($Anio){
	$cons="Select Cuenta,Nombre,length(Cuenta) as Digitos from PlanCuentas where Cuenta like '1%' and PlanCuentas.Compania='$Compania[0]' and Anio=$Anio and Vigencia='Actual' having Digitos<=$NoDigitos Order By Cuenta";
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

		echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>".substr($fila[1],0,40)."</td>
		<td align='right'>".number_format($ApropIni,2)."</td><td align='right'>".number_format($Adiciones,2)."</td><td align='right'>".number_format($Reducciones,2)."</td><td align='right'>0</td><td align='right'>0</td><td align='right'>".number_format($ApropDef,2)."</td>";


		$TotReconoc=$Valores["Reconocimiento Presupuestal"][$fila[0]]["CCredito"];
		$IngTotales=$Valores["Ingreso Presupuestal"][$fila[0]]["CCredito"];
		$ReconocSinAfectar=$TotReconoc-$IngTotales;
	
		$RecxRecaudar=$ApropDef-$TotReconoc;

		echo "<td align='right'>".number_format($IngTotales,2)."</td>";
		$NumRec++;
	}
	}
?>
</table><br /><br />
<table border="0" style="font-size:12px">
<tr>
<td>
__________________________________<br />
Representante Legal
</td>
<td style="width:120px;"></td>
<td>
__________________________________<br />
Jefe de Presupuesto
</td>

</tr></table>