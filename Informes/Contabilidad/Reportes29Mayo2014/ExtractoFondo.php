<?
	if($DatNameSID){session_name("$DatNameSID");}
	include("Informes.php");
	session_start();
	$cons="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$Tercero'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NomAsociado="$fila[0] $fila[1] $fila[2] $fila[3]";	
	$GrpCuenta="141205";
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";
	$CuentaIni=$GrpCuenta;$CuentaFin=$GrpCuenta."99999999999";
	$CuentaAhorroVol="21050501";
	$CuentaAhorroProg="21300501";
	$AportesAsoc="31300501";
//////////////////EXTRACCION TAL COMO EN UN BALANCE DE PRUEBA ///////////////////////////////////////////

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
	where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and
	Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin'  and Identificacion='$Tercero'
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

	$cons3="Select sum(Debe),sum(Haber),Cuenta from Contabilidad.Movimiento 
	where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and 
	Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin'  and Identificacion='$Tercero'
	Group By Cuenta Order By Cuenta";
	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
				$cons99="Select Cuenta from Contabilidad.Plancuentas where Cuenta='$ParteCuenta' and Anio=$Anio and Compania='$Compania[0]'";
				$res99=ExQuery($cons99);
				if(ExNumRows($res99)==0)
				{
					echo "CUENTA NO EXISTE . " . $ParteCuenta."<br>";
				}
			$MPCuenta[$ParteCuenta]['debitos']=$MPCuenta[$ParteCuenta]['debitos']+$fila3[0];
			$MPCuenta[$ParteCuenta]['creditos']=$MPCuenta[$ParteCuenta]['creditos']+$fila3[1];}
			$ParteAnt=$ParteCuenta;
		}
	}


?>
<style type="text/css">
<!--
    div.zone { border: none; border-radius: 6mm; background: #FFFFFF; border-collapse: collapse; padding:3mm; font-size: 2.7mm;}
    h1 { padding: 0; margin: 0; color: #DD0000; font-size: 7mm; }
    h2 { padding: 0; margin: 0; color: #222222; font-size: 5mm; position: relative; }
-->
</style>
<page format="115x200" orientation="L" style="font: normal normal small-caps 9px Tahoma;;">

<table border="0">
<tr><td><img src="<? echo $_SERVER['DOCUMENT_ROOT'];?>/Imgs/Logo.jpg" style="width:60px;"></td>
<td>
<strong><? echo strtoupper($Compania[0])?></strong>
<br>Extracto de Movimientos <br> Periodo : <? echo "$PerIni"?> hasta  <? echo $PerFin?><br>
Asociado: <? echo "$Tercero - $NomAsociado"?>
</td></tr></table>
<br><br>
<table align="center" border=0  style='font : normal normal small-caps 11px Tahoma;' bordercolor="#e5e5e5">
<tr bgcolor="#e5e5e5"><td>Codigo</td><td>Detalle</td><td>Saldo Anterior</td><td>Abonos</td><td>Creditos</td><td>Nuevo Saldo</td></tr>
<?


	$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta like '$GrpCuenta%' and Tipo='Detalle'";
	$res=ExQuery($cons);
	while($filaCta=ExFetch($res))
	{
		$Muestre=0;
		$Debitos=$MPCuenta[$filaCta[0]]['debitos'];
		$Creditos=$MPCuenta[$filaCta[0]]['creditos'];
		$DebitosSI=$SICuenta[$filaCta[0]]['debitos'];
		$CreditosSI=$SICuenta[$filaCta[0]]['creditos'];
		
		if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
		if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
		elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}

		if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
		elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}

		if($DebitosSI || $CreditosSI || $Debitos || $Creditos){$Muestre=1;}
		if($Muestre==1)
		{
			echo "<tr><td>$filaCta[0]</td><td>$filaCta[1]</td><td align='right'>".number_format($SaldoI)."</td><td align='right'>".number_format($Creditos)."</td><td align='right'>".number_format($Debitos)."</td><td align='right'>".number_format($SaldoF)."</td></tr>";
		}
	}

?>
<tr><td colspan="6" align="center"><strong>RELACION DE AHORRO</strong></td></tr>
<tr bgcolor="#e5e5e5"><td >Codigo</td><td>Detalle</td><td>Saldo Anterior</td><td>Abonos</td><td>Retiros</td><td>Nuevo Saldo</td></tr>
<?
//////////////////////CALCULO DEL AHORRO//////////////////////////

	$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as MovAnio from Contabilidad.Movimiento 
	where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and
	(Cuenta='$CuentaAhorroProg' Or Cuenta='$CuentaAhorroVol' Or Cuenta='$AportesAsoc') and Identificacion='$Tercero'
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

	$cons3="Select sum(Debe),sum(Haber),Cuenta from Contabilidad.Movimiento 
	where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and 
	(Cuenta='$CuentaAhorroProg' Or Cuenta='$CuentaAhorroVol' Or Cuenta='$AportesAsoc')  and Identificacion='$Tercero'
	Group By Cuenta Order By Cuenta";
	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
				$cons99="Select Cuenta from Contabilidad.Plancuentas where Cuenta='$ParteCuenta' and Anio=$Anio and Compania='$Compania[0]'";
				$res99=ExQuery($cons99);
				if(ExNumRows($res99)==0)
				{
					echo "CUENTA NO EXISTE . " . $ParteCuenta."<br>";
				}
			$MPCuenta[$ParteCuenta]['debitos']=$MPCuenta[$ParteCuenta]['debitos']+$fila3[0];
			$MPCuenta[$ParteCuenta]['creditos']=$MPCuenta[$ParteCuenta]['creditos']+$fila3[1];}
			$ParteAnt=$ParteCuenta;
		}
	}

	$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where (Cuenta = '$CuentaAhorroProg' Or cuenta='$CuentaAhorroVol' Or Cuenta='$AportesAsoc') and Tipo='Detalle'";
	$res=ExQuery($cons);
	while($filaCta=ExFetch($res))
	{
		$Muestre=0;
		$Debitos=$MPCuenta[$filaCta[0]]['debitos'];
		$Creditos=$MPCuenta[$filaCta[0]]['creditos'];
		$DebitosSI=$SICuenta[$filaCta[0]]['debitos'];
		$CreditosSI=$SICuenta[$filaCta[0]]['creditos'];
		
		if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
		if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
		elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}

		if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
		elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}

		if($DebitosSI || $CreditosSI || $Debitos || $Creditos){$Muestre=1;}
		if($Muestre==1)
		{
			echo "<tr><td>$filaCta[0]</td><td>$filaCta[1]</td><td align='right'>".number_format($SaldoI)."</td><td align='right'>".number_format($Creditos)."</td><td align='right'>".number_format($Debitos)."</td><td align='right'>".number_format($SaldoF)."</td></tr>";
			$SumAhorros=$SumAhorros+$Creditos;
		}
	}

?>
<tr style="font-weight:bold"><td colspan="3" align="right" >SUMAS</td><td align="right"><? echo number_format($SumAhorros,0)?></td></tr>
</table>
<br><br><br>
<table  align="center" border=0 style='font : normal normal small-caps 11px Tahoma;'>
<tr><td style="width:400px;"><hr></td></tr>
<tr><td align="center"><strong>Firma y Sello Fondo de Empleados</strong></td></tr>
</table>

</page>
