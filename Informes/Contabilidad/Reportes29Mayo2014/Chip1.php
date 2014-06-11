<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	if(!$CuentaIni){$CuentaIni=0;}
	if(!$CuentaFin){$CuentaFin=9999999999;}
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";
	$ND=getdate();
	$NoDigitos=6;
?>

<body>
<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<?
	function Encabezados()
	{
		global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;
		?>
		<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
		<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>BALANCE DE PRUEBA<br>Corte a: <?echo $PerFin?></td></tr>
		<tr><td colspan="8" align="right">Fecha de Impresi&oacute;n <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		<tr style="font-weight:bold" bgcolor="#e5e5e5" style="text-align:center;">
		<td rowspan="2">Codigo</td>
		<?
			if($IncluyeCC=="on")
			{
				echo "<td rowspan=2>CC</td>";
			}
		?>
		<td rowspan="2">Descripci&oacute;n</td><td rowspan="2">Saldo Anterior</td><td colspan="2">Movimientos del Periodo</td><td rowspan="2">Saldo Final</td><td rowspan="2">Corriente</td><td rowspan="2">No Corriente</td></tr>
		<tr style="font-weight:bold" bgcolor="#e5e5e5" style="text-align:center;"><td>Debito</td><td>Credito</td></tr>
		
<?	}
	$NumRec=0;$NumPag=1;
	Encabezados();

	$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetchArray($res))
	{
		$Nivel++;$TotNivel++;
		if(!$fila[0]){$fila[0]="-100";}
		$TotCaracteres=$TotCaracteres+$fila[0];
		$Digitos[$Nivel]=$TotCaracteres;
	}

	$cons2="Select sum(Debe),sum(Haber),Movimiento.Cuenta,Corriente from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Fecha<'$PerIni' and Movimiento.Compania='$Compania[0]' and Estado='AC' and Movimiento.Cuenta!='0' and $ExcluyeComprobantes Group By Cuenta Order By Cuenta";
	$res2=ExQuery($cons2);

	while($fila2=ExFetch($res2))
	{
		$fila2[0]=round($fila2[0]/1000,30);
		$fila2[1]=round($fila2[1]/1000,30);
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			if($fila2[3]=="on"){$Corriente="Corriente";}else{$Corriente="NoCorriente";}
			$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$SICuenta[$ParteCuenta]['Debitos']=($SICuenta[$ParteCuenta]['Debitos']+$fila2[0]);
			$SICuenta[$ParteCuenta]['DB'][$Corriente]=($SICuenta[$ParteCuenta]['DB'][$Corriente]+$fila2[0]);
			$SICuenta[$ParteCuenta]['Creditos']=($SICuenta[$ParteCuenta]['Creditos']+$fila2[1]);
			$SICuenta[$ParteCuenta]['CR'][$Corriente]=($SICuenta[$ParteCuenta]['CR'][$Corriente]+$fila2[1]);}
			$ParteAnt=$ParteCuenta;
		}
	}

/*	echo number_format($SICuenta[1]['DB']['Corriente'],2)."<br>";
	echo number_format($SICuenta[1]['DB']['NoCorriente'],2)."<br>";
	
	echo number_format($SICuenta[1]['CR']['Corriente'],2)."<br>";
	echo number_format($SICuenta[1]['CR']['NoCorriente'],2);*/
	
	$cons3="Select sum(Debe),sum(Haber),Movimiento.Cuenta,Corriente from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Fecha>='$PerIni' and Fecha<='$PerFin' and Movimiento.Compania='$Compania[0]' and Estado='AC' and Movimiento.Cuenta!='0' and $ExcluyeComprobantes Group By Cuenta Order By Cuenta";
	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		$fila3[0]=round($fila3[0]/1000,30);
		$fila3[1]=round($fila3[1]/1000,30);

		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			if($fila3[3]=="on"){$Corriente="Corriente";}else{$Corriente="NoCorriente";}
			$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$MPCuenta[$ParteCuenta]['Debitos']=$MPCuenta[$ParteCuenta]['Debitos']+$fila3[0];
			$MPCuenta[$ParteCuenta]['DB'][$Corriente]=$MPCuenta[$ParteCuenta]['DB'][$Corriente]+$fila3[0];

			$MPCuenta[$ParteCuenta]['Creditos']=$MPCuenta[$ParteCuenta]['Creditos']+$fila3[1];
			$MPCuenta[$ParteCuenta]['CR'][$Corriente]=$MPCuenta[$ParteCuenta]['CR'][$Corriente]+$fila3[1];}
			$ParteAnt=$ParteCuenta;
		}
	}
	
/*	echo number_format($MPCuenta[1]['DB']['Corriente'],2)."<br>";
	echo number_format($MPCuenta[1]['DB']['NoCorriente'],2)."<br>";
	
	echo number_format($MPCuenta[1]['CR']['Corriente'],2)."<br>";
	echo number_format($MPCuenta[1]['CR']['NoCorriente'],2);*/



	$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos from Contabilidad.PlanCuentas 
	where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Compania='$Compania[0]' and Anio=$Anio
	having Digitos<=$NoDigitos Order By Cuenta";
	$resCta=ExQuery($consCta);

	while($filaCta=ExFetchArray($resCta))
	{
		if($NumRec>=$Encabezados)
		{
			echo "</table><P>&nbsp;</P>";
			$NumPag++;
			Encabezados();
			$NumRec=0;
		}


		$Debitos=$MPCuenta[$filaCta[0]]['Debitos'];
		$Creditos=$MPCuenta[$filaCta[0]]['Creditos'];
		$DebitosSI=$SICuenta[$filaCta[0]]['Debitos'];
		$CreditosSI=$SICuenta[$filaCta[0]]['Creditos'];
		
		if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
		if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;}
		elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;}

		if($filaCta[3]=="Debito")
		{
			$SaldoF=$SaldoI-$Creditos+$Debitos;
			$SF['Corriente']=$SICuenta[$filaCta[0]]['DB']['Corriente']-$SICuenta[$filaCta[0]]['CR']['Corriente']+$MPCuenta[$filaCta[0]]['DB']['Corriente']-$MPCuenta[$filaCta[0]]['CR']['Corriente'];
			$SF['NoCorriente']=$SICuenta[$filaCta[0]]['DB']['NoCorriente']-$SICuenta[$filaCta[0]]['CR']['NoCorriente']+$MPCuenta[$filaCta[0]]['DB']['NoCorriente']-$MPCuenta[$filaCta[0]]['CR']['NoCorriente'];
		}
		elseif($filaCta[3]=="Credito")
		{
			$SaldoF=$SaldoI+$Creditos-$Debitos;
			$SF['Corriente']=$SICuenta[$filaCta[0]]['CR']['Corriente']-$SICuenta[$filaCta[0]]['DB']['Corriente']+$MPCuenta[$filaCta[0]]['CR']['Corriente']-$MPCuenta[$filaCta[0]]['DB']['Corriente'];
			$SF['NoCorriente']=$SICuenta[$filaCta[0]]['CR']['NoCorriente']-$SICuenta[$filaCta[0]]['DB']['NoCorriente']+$MPCuenta[$filaCta[0]]['CR']['NoCorriente']-$MPCuenta[$filaCta[0]]['DB']['NoCorriente'];
		}

		$SF['Corriente']=$SF['Corriente'];
		$SF['NoCorriente']=$SF['NoCorriente'];

		$SaldoI=$SaldoI;
		$SaldoF=$SaldoF;
		$Debitos=$Debitos;
		$Creditos=$Creditos;

		if($DebitosSI || $CreditosSI || $Debitos || $Creditos){$Muestre=1;}
		if($IncluyeCeros=="on"){$Muestre=1;}
		if($Muestre==1)
		{
			$NumRec++;

			if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
			else{$BG="white";$Fondo=1;}

			echo "<tr bgcolor='$BG'>";
			if($IncluyeCC=="on"){echo "<td colspan=2>";}
			else{echo "<td>";}
			echo "$filaCta[0]</td><td>$filaCta[1]</td>";

			echo "<td align='right'>".number_format($SaldoI,0)."</td>";

			echo "<td align='right'>".number_format($Debitos,0)."</td><td align='right'>".number_format($Creditos,0)."</td>";

			echo "<td align='right'>".number_format($SaldoF,0)."</td>";

			echo "<td align='right'>".number_format($SF['Corriente'])."</td><td align='right'>".number_format($SF['NoCorriente'])."</td>";

			if(strlen($filaCta[0])==1)
			{
				$TotDebitosMov=$TotDebitosMov+$Debitos;
				$TotCreditosMov=$TotCreditosMov+$Creditos;
			}



		}
		if($filaCta[0]=="1"){$TotActivo=$SaldoF;}
		if($filaCta[0]=="2"){$TotPasivo=$SaldoF;}
		if($filaCta[0]=="3"){$TotPatrimonio=$SaldoF;}
		$Muestre="N";
		$SaldoI=0;
	}

	echo "<tr bgcolor='#e5e5e5'>";
	if($IncluyeCC=="on"){echo "<td colspan=3 align='right'>";}
	else{echo "<td colspan=2 align='right'>";}
	echo "<strong>SUMAS IGUALES</td>";
	echo "<td align='right'><strong>".number_format($TotDebitosSI,0)."</td>";
	echo "<td align='right'><strong>".number_format($TotCreditosSI,0)."</td>";
	echo "<td align='right'><strong>".number_format($TotDebitosMov,0)."</td>";
	echo "<td align='right'><strong>".number_format($TotCreditosMov,0)."</td>";

	echo "<td align='right'><strong>".number_format($TotSFDeb,0)."</td>";
	echo "<td align='right'><strong>".number_format($TotSFCred,0)."</td>";
	echo "</tr>";
?>
</table>
<br><center>

<br><br>
<?
	$cons="Select Director,Contador,Revisor,TPContador,TPRevisor from Central.FirmasInformes where Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Director=$fila[0];$Contador=$fila[1];$Revisor=$fila[2];$TPContador=$fila[3];$TPRevisor=$fila[4];
?>

<table border="0">
<tr><td>______________________________</td><td style="width:130px;"></td><td>______________________________</td><td style="width:130px;"></td></tr>
<tr style="font-weight:bold;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<td><?echo $Director?></td><td></td><td><?echo $Contador?></td><td></td></tr>
<tr style="font-weight:bold;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<td>Alcalde Municipal</td><td></td><td>Contador Publico T.P. <?echo $TPContador?></td></tr>
</table>
</div>
</body>