<?
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

?>
<table border="1" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td><strong><?echo $Compania[0]?></td>
<tr><td>Informe Detallado de Recaudos</td>
<tr><td>
Periodo <?echo $PerIni?> a <?echo $PerFin?></td>
<?

	$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as MovAnio from Contabilidad.Movimiento 
	where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and $ExcluyeComprobantes and
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


	$cons="Select PlanCuentas.Cuenta,PlanCuentas.Nombre from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where PlanCuentas.Cuenta=Movimiento.Cuenta and Estado='AC' and Movimiento.Compania='$Compania[0]' and PlanCuentas.Compania='$Compania[0]' 
	and Movimiento.Anio=$Anio  and PlanCuentas.Anio=$Anio
	and Fecha>='$PerIni' and Fecha<='$PerFin' and PlanCuentas.Cuenta>='$CuentaIni' and PlanCuentas.Cuenta<='$CuentaFin'
	Group By PlanCuentas.Cuenta,PlanCuentas.Nombre";

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$SaldoIni=$SICuenta[$fila[0]]['debitos']-$SICuenta[$fila[0]]['creditos'];
		echo "<tr><td colspan='5' align='Center' bgcolor='#e5e5e5'><strong>$fila[0] $fila[1]</td></tr>";	
		echo "<tr align='right' bgcolor='#e5e5e5'><td colspan='3'><strong>Saldo Inicial:</td><td colspan='2'><strong>" . number_format($SaldoIni,2) . " </td></tr>";	

		echo "<tr  bgcolor='#e5e5e5' style='font-weight;bold'><td>Fecha</td><td>Comprobante</td><td>Numero</td><td>Debe</td><td>Haber</td></tr>";
		$cons2="Select Comprobante,Numero,Debe,Haber,Fecha from Contabilidad.Movimiento where Movimiento.Compania='$Compania[0]' and Compania='$Compania[0]' and Cuenta='$fila[0]'
		and Estado='AC' and Fecha>='$PerIni' and Fecha<='$PerFin' Order By Fecha,Numero";
		$res2=ExQuery($cons2);echo ExError();
		while($fila2=ExFetch($res2))
		{
			echo "<tr><td>$fila2[4]</td><td>$fila2[0]</td><td align='right'>$fila2[1]</td><td align='right'>".number_format($fila2[2],2)."</td><td align='right'>".number_format($fila2[3],2)."</td></tr>";
			$SumDebe=$SumDebe+$fila2[2];
			$SumHaber=$SumHaber+$fila2[3];
		}
		echo "<tr align='right' style='font-weight:bold'><td colspan=3>SUMAS</td><td>" . number_format($SumDebe,2)."</td><td>" . number_format($SumHaber,2)."</td></tr>";
		$Total=$Total+$SubTotal;
		$SubTotal=0;
	}
	$SaldoSig=$SaldoIni+$SumDebe-$SumHaber;
	echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td colspan='4'>SALDO SIGUIENTE</td><td>".number_format($SaldoSig,2)."</td></tr>";

?>
		
</table>