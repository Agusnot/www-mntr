<?php
session_name("$DatNameSID");
	session_start();
	include("Funciones.php");
	if(!$CuentaIni){$CuentaIni=0;}
	if(!$CuentaFin){$CuentaFin=9999999999;}
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";

	$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetchArray($res))
	{
		$Nivel++;$TotNivel++;
		if(!$fila[0]){$fila[0]="-100";}
		$TotCaracteres=$TotCaracteres+$fila[0];
		$Digitos[$Nivel]=$TotCaracteres;
	}


	$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as MovAnio from Contabilidad.Movimiento 
	where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and $ExcluyeComprobantes and
	Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' $CondCC
	Group By Cuenta,MovAnio Order By Cuenta";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$CuentaMad=substr($fila2[2],0,1);
		if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[3]){}
		else{
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$SICuenta[$ParteCuenta]['debitos']=$SICuenta[$ParteCuenta]['debitos']+$fila2[0];
			$SICuenta[$ParteCuenta]['creditos']=$SICuenta[$ParteCuenta]['creditos']+$fila2[1];}
			$ParteAnt=$ParteCuenta;
		}
		}
	}



	$cons="Select PlanCuentas.Cuenta,PlanCuentas.Nombre,Debe,Haber,Comprobante,Numero 
	from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where PlanCuentas.Cuenta=Movimiento.Cuenta and Estado='AC' and Movimiento.Compania='$Compania[0]' 
	and PlanCuentas.Compania='$Compania[0]' and PlanCuentas.Anio=$Anio  and Movimiento.Anio=$Anio
	and Fecha>='$PerIni' and Fecha<='$PerFin' and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$SaldoIni=$SICuenta[$fila[0]]['debitos']-$SICuenta[$fila[0]]['creditos'];

		if($fila[2]>0)
		{
			$cons2="Select Cuenta,Haber from Contabilidad.Movimiento where Comprobante='$fila[4]' and Numero='$fila[5]' and Estado='AC' and Compania='$Compania[0]' and Anio=$Anio
			and Haber>0";

			$res2=ExQuery($cons2);
			while($fila2=ExFetch($res2))
			{
				$Concepto[$fila2[0]][0]=$Concepto[$fila2[0]][0]+$fila2[1];
			}
		}
		else
		{
			$cons2="Select Cuenta,Debe from Contabilidad.Movimiento where Comprobante='$fila[4]' and Numero='$fila[5]' and Estado='AC' and Compania='$Compania[0]' and Anio=$Anio
			and Debe>0";
			$res2=ExQuery($cons2);
			while($fila2=ExFetch($res2))
			{
				$Concepto[$fila2[0]][1]=$Concepto[$fila2[0]][1]+$fila2[1];
			}
		}

	}

?>

<table border="1" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td colspan="4"><strong><?echo $Compania[0]?></td>
<tr><td colspan="4">Informe de Arqueo de Caja</td>
<tr><td colspan="4">
Periodo <?echo $PerIni?> a <?echo $PerFin?></td>
<tr align='right' bgcolor='#e5e5e5'><td colspan='3'><strong>Saldo Inicial:</td><td colspan='2'><strong><?  echo number_format($SaldoIni,2) ?> </td></tr>

<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Cuenta</td><td>Nombre</td><td>Debitos</td><td>Creditos</td>
<?
	while (list($val,$cad) = each ($Concepto)) 
	{
		$cons3="Select Cuenta,Nombre from Contabilidad.PlanCuentas where Cuenta='$val' and Compania='$Compania[0]' and Anio=$Anio";
		$res3=ExQuery($cons3);
		$fila3=ExFetch($res3);
		echo "<tr><td>$fila3[0]<td>$fila3[1]</td><td align='right'>".number_format($cad[0],2)."</td><td align='right'>".number_format($cad[1],2)."</td></tr>";
		$TotDebitos=$TotDebitos+$cad[0];
		$TotCreditos=$TotCreditos+$cad[1];
	}
	$SaldoSig=$SaldoIni+$TotDebitos-$TotCreditos;
	echo "<tr bgcolor='#e5e5e5' align='right' style='font-weight:bold'><td colspan=2>SUMAS</td><td>".number_format($TotDebitos,2)."</td><td>".number_format($TotCreditos,2)."</td></tr>";
	echo "<tr bgcolor='#e5e5e5' align='right' style='font-weight:bold'><td colspan=3>Saldo siguiente</td><td>".number_format($SaldoSig,2)."</td></tr>";
?>
</table>

<br /><br /><br />
<center>
<table border="1" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td style="width:250px;" align="center"><hr />Recaudos</td><td style="width:80px;"></td>
<td style="width:250px;" align="center"><hr />Revisó</td></tr>
</table>
</center>