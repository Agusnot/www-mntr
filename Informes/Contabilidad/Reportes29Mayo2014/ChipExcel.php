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
		<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
		<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>1</td><td>Cuenta</td><td>Detalle</td><td>Saldo Inicial</td><td>Debitos</td><td>Creditos</td><td>Saldo Final</td><td>Corriente</td><td>No Corriente</td></tr>
<?
	$NumRec=1;$NumPag=1;
	$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";

	$res=ExQuery($cons,$conex);
	while($fila=ExFetchArray($res))
	{
		$Nivel++;$TotNivel++;
		if(!$fila[0]){$fila[0]="-100";}
		$TotCaracteres=$TotCaracteres+$fila[0];
		$Digitos[$Nivel]=$TotCaracteres;
	}

	$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
	$res=ExQuery($cons,$conex);
	for($NivelX=1;$NivelX<=$TotNivel;$NivelX++)
	{
		$fila=ExFetchArray($res);
		if(!$fila[0]){$fila[0]="-100";}
		$DigitosX[$NivelX]=$fila[0];
	}

	$cons2="Select sum(Debe),sum(Haber),Movimiento.Cuenta,Corriente,date_part('year',Fecha) as MovAnio from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Fecha<'$PerIni' and Movimiento.Compania='$Compania[0]' and PlanCuentas.Compania='$Compania[0]' 
	and Estado='AC' and Movimiento.Cuenta!='0' and Movimiento.Cuenta!='1'
	and $ExcluyeComprobantes and PlanCuentas.Anio=$Anio
	Group By Movimiento.Cuenta,MovAnio,Corriente,PlanCuentas.Cuenta Order By Cuenta";

	$res2=ExQuery($cons2);echo ExError();

	while($fila2=ExFetch($res2))
	{
		$CuentaMad=substr($fila2[2],0,1);
		if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[4]){}
		else{
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
		}}
	}

/*	echo number_format($SICuenta[1]['DB']['Corriente'],2)."<br>";
	echo number_format($SICuenta[1]['DB']['NoCorriente'],2)."<br>";
	
	echo number_format($SICuenta[1]['CR']['Corriente'],2)."<br>";
	echo number_format($SICuenta[1]['CR']['NoCorriente'],2);*/
	
	$cons3="Select sum(Debe),sum(Haber),Movimiento.Cuenta,Corriente from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Fecha>='$PerIni' and Fecha<='$PerFin' and Movimiento.Compania='$Compania[0]' and PlanCuentas.Compania='$Compania[0]' and Estado='AC' and Movimiento.Cuenta!='0'  and Movimiento.Cuenta!='1'
	and $ExcluyeComprobantes and PlanCuentas.Anio=$Anio
	Group By Movimiento.Cuenta,PlanCuentas.Cuenta,Corriente Order By Cuenta";

	$res3=ExQuery($cons3);echo ExError();
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
	and length(Cuenta)<=$NoDigitos Order By Cuenta";
	$resCta=ExQuery($consCta);echo ExError();

	$cons9="Select Cuenta,Tipo,length(Cuenta) as Digitos from Contabilidad.PlanCuentas where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
	and Compania='$Compania[0]' and Anio=$Anio
	and length(Cuenta)<=$NoDigitos Order By Cuenta";
	$res9=ExQuery($cons9);
	while($fila9=ExFetch($res9))
	{
		$i++;
		$MatrizPUC[$i]=$fila9[0];
	}

	while($filaCta=ExFetchArray($resCta))
	{
		if(strlen($filaCta[0])==1)
		{
			$Naturaleza=$filaCta[3];
		}

		$Debitos=$MPCuenta[$filaCta[0]]['Debitos'];
		$Creditos=$MPCuenta[$filaCta[0]]['Creditos'];
		$DebitosSI=$SICuenta[$filaCta[0]]['Debitos'];
		$CreditosSI=$SICuenta[$filaCta[0]]['Creditos'];
		
		if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
		if($Naturaleza=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;}
		elseif($Naturaleza=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;}

		if($Naturaleza=="Debito")
		{
			$SaldoF=$SaldoI-$Creditos+$Debitos;
			$SF['Corriente']=$SICuenta[$filaCta[0]]['DB']['Corriente']-$SICuenta[$filaCta[0]]['CR']['Corriente']+$MPCuenta[$filaCta[0]]['DB']['Corriente']-$MPCuenta[$filaCta[0]]['CR']['Corriente'];
			$SF['NoCorriente']=$SICuenta[$filaCta[0]]['DB']['NoCorriente']-$SICuenta[$filaCta[0]]['CR']['NoCorriente']+$MPCuenta[$filaCta[0]]['DB']['NoCorriente']-$MPCuenta[$filaCta[0]]['CR']['NoCorriente'];
		}
		elseif($Naturaleza=="Credito")
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

		if($Muestre==1)
		{
						$CondSF="";

			$NumRec++;

			if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
			else{$BG="white";$Fondo=1;}

			echo "<tr bgcolor='$BG'>";
			echo "<td>$NumRec</td><td>";
			$ParteCuenta="";
			$ParteAnt=0;$TotDig=0;
			$NivelAnt=0;

			for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
			{
				$PC1=substr($filaCta[0],$NivelAnt,$DigitosX[$Nivel]);
				if($PC1!=""){$PC=$PC.$PC1.".";$NivelAnt=$NivelAnt+$DigitosX[$Nivel];}
			}
			$PC=substr($PC,0,strlen($PC)-1);
			echo "$PC</td><td>$filaCta[1]</td>";
			$PC="";
			if(strlen($filaCta[0])==6)
			{
				echo "<td align='right'>".number_format($SaldoI,0)."</td>";
				echo "<td align='right'>".number_format($Debitos,0)."</td><td align='right'>".number_format($Creditos,0)."</td>";

				if($Naturaleza=="Debito"){$CondSF=$CondSF."+D$NumRec+E$NumRec-F$NumRec";}
				if($Naturaleza=="Credito"){$CondSF=$CondSF."+D$NumRec-E$NumRec+F$NumRec";}

				echo "<td>".$CondSF."</td>";
				echo "<td align='right'>".number_format($SF['Corriente'])."</td><td align='right'>".number_format($SF['NoCorriente'])."</td>";
				if(strlen($filaCta[0])==1)
				{
					$TotDebitosMov=$TotDebitosMov+$Debitos;
					$TotCreditosMov=$TotCreditosMov+$Creditos;
				}
			}
			else
			{
				$LongAct=strlen($filaCta[0]);

				$cons120="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
				$res120=ExQuery($cons120);
				while($fila120=ExFetch($res120))
				{
					$LongEP=$LongEP+$fila120[0];
					if($Act==1){$LongTotal=$LongEP;$Act=0;}
					if($LongEP==$LongAct){$Act=1;}
				}

				$CondSA="=";$CondMD="=";$CondMC="=";$CondSF="=";$CondCO="=";$CondNCO="=";
				$Cta=1;	

				if($Naturaleza=="Debito"){$CondSF=$CondSF."+D$NumRec+E$NumRec-F$NumRec";}
				if($Naturaleza=="Credito"){$CondSF=$CondSF."+D$NumRec-E$NumRec+F$NumRec";}

				for($NR=0;$NR<=count($MatrizPUC);$NR++)
				{
					$DebitosX=$MPCuenta[$MatrizPUC[$NR]]['Debitos'];
					$CreditosX=$MPCuenta[$MatrizPUC[$NR]]['Creditos'];
					$DebitosSIX=$SICuenta[$MatrizPUC[$NR]]['Debitos'];
					$CreditosSIX=$SICuenta[$MatrizPUC[$NR]]['Creditos'];

					if($DebitosSIX || $CreditosSIX || $DebitosX || $CreditosX)
					{
						$Cta++;
						if(strlen($MatrizPUC[$NR])==$LongTotal)
						{
							$CuentaEvalua=substr($MatrizPUC[$NR],0,strlen($filaCta[0]));
							if($CuentaEvalua==$filaCta[0])
							{
								$CondSA=$CondSA."+D$Cta";
								$CondMD=$CondMD."+E$Cta";
								$CondMC=$CondMC."+F$Cta";
								$CondCO=$CondCO."+H$Cta";
								$CondNCO=$CondNCO."+I$Cta";
							}
						}
					}
				}
				$LongTotal=0;$LongEP=0;
				echo "<td>$CondSA</td><td>$CondMD</td><td>$CondMC</td><td>$CondSF</td><td>$CondCO</td><td>$CondNCO</td>";
			}
		}
		if($filaCta[0]=="1"){$TotActivo=$SaldoF;}
		if($filaCta[0]=="2"){$TotPasivo=$SaldoF;}
		if($filaCta[0]=="3"){$TotPatrimonio=$SaldoF;}
		$Muestre="N";
		$SaldoI=0;
	}
?>
</table>
</div>
</body>