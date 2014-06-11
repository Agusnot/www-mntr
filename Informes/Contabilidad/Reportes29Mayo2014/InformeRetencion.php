<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";


?>

<table border="1" rules="groups" bordercolor="#ffffff" width="70%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
<tr><td colspan="3"><center><strong><?echo strtoupper($Compania[0])?><br>
<?echo $Compania[1]?><br>INFORME DE RETENCIONES<br>Corte a: <?echo $PerFin?></td></tr>
</table>

<?	
	$cons="Select Cuenta,Comprobante,Numero from Contabilidad.Movimiento where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' and Movimiento.Compania='$Compania[0]'";

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="Select Haber,ConceptoRte from Contabilidad.Movimiento where Comprobante='$fila[1]' and Numero='$fila[2]' and ConceptoRte!='0' and
		ConceptoRte!=''
		and Estado='AC' and Movimiento.Compania='$Compania[0]' and Cuenta NOT ilike '1%'";

		$res1=ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			$ConceptoRte[$fila[0]][$fila1[1]]=$ConceptoRte[$fila[0]][$fila1[1]]+$fila1[0];
		}

	}


	$cons="Select Cuenta from Contabilidad.Movimiento where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
	and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' and Movimiento.Compania='$Compania[0]' Group By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons2="Select Nombre from Contabilidad.PlanCuentas where Cuenta='$fila[0]' and Compania='$Compania[0]' and Anio=$Anio";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$NomCuenta=$fila2[0];
		if(count($ConceptoRte[$fila[0]])>0){

		echo "<table border='1' width='70%' bordercolor='#ffffff' style='font-family:$Estilo[8];font-size:12;font-style:$Estilo[10]'>";
		echo "<tr bgcolor='#e5e5e5'><td colspan=2><strong><center>$NomCuenta - $fila[0]</td></tr>";
		echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'><td>Concepto</td><td>Valor</td></tr>";

		while (list($val,$cad) = each ($ConceptoRte[$fila[0]])) 
		{
			if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
			else{$BG="white";$Fondo=1;}
			echo "<tr bgcolor='$BG'><td>$val</td><td align='right'>".number_format($cad,2)."</td></tr>";
			$SubTotal=$SubTotal+$cad;
		}
		echo "<tr bgcolor='#e5e5e5' align='right' style='font-weight:bold'><td>Subtotal</td><td>".number_format($SubTotal,2)."</td></tr>";
		$SubTotal=0;
		echo "</table><br><br>";}
	}
?>

<table border="1" width="70%" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5"><td colspan="2"><strong><center>RESUMEN</td></tr>
<?
	$SubTotal=0;
	echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'><td>Concepto</td><td>Valor</td></tr>";
	$cons1="Select sum(Haber),ConceptoRte from Contabilidad.Movimiento where Fecha>='$PerIni' and Fecha<='$PerFin' and ConceptoRte!='0' and ConceptoRte!=''
	and Estado='AC' and Movimiento.Compania='$Compania[0]' and Cuenta NOT ilike '1%'
	Group By ConceptoRte";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}
	
		echo "<tr bgcolor='$BG'><td>$fila1[1]</td><td align='right'>".number_format($fila1[0],2)."</td></tr>";
		$SubTotal=$SubTotal+$fila1[0];
	}

	echo "<tr bgcolor='#e5e5e5' align='right' style='font-weight:bold'><td>Subtotal</td><td>".number_format($SubTotal,2)."</td></tr>";
?>
</table>