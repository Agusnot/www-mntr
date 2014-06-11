<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$Anio=$AnioAc;
	if(!$Cuenta && $Cuenta!='0'){exit;}
?>
<style>body{background:<?echo $Estilo[6]?>;color:<?echo $Estilo[7]?>;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>}</style>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>

<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">

<tr class="Tit1"><td>Mes</td><td>Saldo Inicial</td><td>Debitos</td><td>Creditos</td><td>Saldo Final</td></tr>
<?
	if($TerceroSel)
	{
		$cons="Select PrimApe,SegApe,PrimNom from Central.Terceros where Identificacion='$TerceroSel' and Terceros.Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError($res);
		$fila=ExFetch($res);
		$CondAdc=" and Identificacion='$TerceroSel'";
		echo "<tr class='Tit1'><td colspan=5><center><font color='#ffff00'>TERCERO SELECCIONADO: $TerceroSel - " . strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]") . "</td></tr>";
	}

	$consPrev="Select Naturaleza from Contabilidad.PlanCuentas where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$AnioAc";
	$resPrev=ExQuery($consPrev,$conex);
	$filaPrev=ExFetch($resPrev);

	if($filaPrev[0]=="Debito"){$SaldoI=$MATMOVSICuenta[$Cuenta][0]-$MATMOVSICuenta[$Cuenta][1];}
	if($filaPrev[0]=="Credito"){$SaldoI=$MATMOVSICuenta[$Cuenta][1]-$MATMOVSICuenta[$Cuenta][0];}


	for($i=1;$i<=12;$i++)
	{
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>".strtoupper($NombreMes[$i])."</td>";
		if($filaPrev[0]=="Debito"){$SaldoF=$SaldoI-$MATMOVMPCuenta[$Cuenta][1][$i]+$MATMOVMPCuenta[$Cuenta][0][$i];}
		elseif($filaPrev[0]=="Credito"){$SaldoF=$SaldoI+$MATMOVMPCuenta[$Cuenta][1][$i]-$MATMOVMPCuenta[$Cuenta][0][$i];}
		echo "<td align='right'>".number_format($SaldoI,2)."</td><td align='right'>".number_format($MATMOVMPCuenta[$Cuenta][0][$i],2)."</td><td align='right'>".number_format($MATMOVMPCuenta[$Cuenta][1][$i],2)."</td><td align='right'>".number_format($SaldoF,2)."</td></tr>";
		$SumDeb=$SumDeb+$Debitos;
		$SumCred=$SumCred+$Creditos;
		$SaldoI=$SaldoF;
	}
?>
<tr class="Tit1" align="right"><td colspan="2"><center>TOTAL</td><td><?echo number_format($SumDeb,2)?></td><td><?echo number_format($SumCred,2)?></td><td>&nbsp;</td></tr>
</table>
</body>