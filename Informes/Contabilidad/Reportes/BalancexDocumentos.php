<?
        if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/fpdf.php');
	if(!$CuentaIni){$CuentaIni=1;}
	if(!$CuentaFin){$CuentaFin=999999999999;}
	$ND=getdate();
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";
        $Corte=$PerFin;
	$Dias=array(1,30,30,30,30,60,180,5000);
	if(!$PDF){
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
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
		<tr><td colspan="10"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>BALANCE DE PRUEBA x DOCUMENTOS<br>Corte a: <?echo $PerFin?></strong></center></td></tr>
		<tr><td colspan="10" align="right">Fecha de Impresion <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		<tr style="font-weight:bold;text-align:center;" bgcolor="#e5e5e5">
		<td rowspan="2">Codigo</td>
		<td rowspan="2" colspan="3">Descripcion</td><td colspan="2">Saldo Anterior</td><td colspan="2">Movimientos del Periodo</td><td colspan="2">Saldo Final</td></tr>
		<tr style="font-weight:bold;text-align:center;" bgcolor="#e5e5e5"><td>Debito</td><td>Credito</td><td>Debito</td><td>Credito</td><td>Debito</td><td>Credito</td></tr>
		
<?	}
	$NumRec=0;$NumPag=1;
	Encabezados();
	}
	$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
	$res=ExQuery($cons,$conex);echo ExError();
	while($fila=ExFetchArray($res))
	{
		$Nivel++;$TotNivel++;
		if(!$fila[0]){$fila[0]="-100";}
		$TotCaracteres=$TotCaracteres+$fila[0];
		$Digitos[$Nivel]=$TotCaracteres;
	}

	$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as Anio from Contabilidad.Movimiento 
	where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
	and $ExcluyeComprobantes Group By Cuenta,Anio,Fecha Order By Cuenta";
	$res2=ExQuery($cons2);echo ExError();
	while($fila2=ExFetch($res2))
	{
		$CuentaMad=substr($fila2[2],0,1);
		if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[3]){}
		else{
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$SICuenta[$ParteCuenta]['Debitos']=$SICuenta[$ParteCuenta]['Debitos']+$fila2[0];
			$SICuenta[$ParteCuenta]['Creditos']=$SICuenta[$ParteCuenta]['Creditos']+$fila2[1];}
			$ParteAnt=$ParteCuenta;
		}}
	}

	$cons3="Select sum(Debe),sum(Haber),Cuenta from Contabilidad.Movimiento 
	where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Cuenta!='0' and Cuenta!='1' and $ExcluyeComprobantes Group By Cuenta Order By Cuenta";
	$res3=ExQuery($cons3);echo ExError();
	while($fila3=ExFetch($res3))
	{
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$MPCuenta[$ParteCuenta]['Debitos']=$MPCuenta[$ParteCuenta]['Debitos']+$fila3[0];
			$MPCuenta[$ParteCuenta]['Creditos']=$MPCuenta[$ParteCuenta]['Creditos']+$fila3[1];}
			$ParteAnt=$ParteCuenta;
		}
	}
	
	
///////////////////////////////BALANCE DE TERCEROS //////////////////////////////////////
//////////////SALDOS INICIALES////////////////////
	$cons200="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as Anio,Identificacion from Contabilidad.Movimiento 
	where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
	and $ExcluyeComprobantes Group By Cuenta,Anio,Fecha,Identificacion Order By Cuenta";

	$res200=ExQuery($cons200);echo ExError();
	while($fila200=ExFetch($res200))
	{
		$CuentaMad=substr($fila200[2],0,1);
		if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila200[3]){}
		else{
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			$ParteCuenta=substr($fila200[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$SITCuenta[$ParteCuenta][$fila200[4]]['Debitos']=$SITCuenta[$ParteCuenta][$fila200[4]]['Debitos']+$fila200[0];
			$SITCuenta[$ParteCuenta][$fila200[4]]['Creditos']=$SITCuenta[$ParteCuenta][$fila200[4]]['Creditos']+$fila200[1];}
			$ParteAnt=$ParteCuenta;
		}}
	}

//////////////////MOVIMIENTOS///////////////////////

	$cons3="Select sum(Debe),sum(Haber),Cuenta,Identificacion from Contabilidad.Movimiento 
	where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Cuenta!='0' and Cuenta!='1' and $ExcluyeComprobantes 
	Group By Cuenta,Identificacion Order By Cuenta";

	$res3=ExQuery($cons3);echo ExError();
	while($fila3=ExFetch($res3))
	{
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$MPTCuenta[$ParteCuenta][$fila3[3]]['Debitos']=$MPTCuenta[$ParteCuenta][$fila3[3]]['Debitos']+$fila3[0];
			$MPTCuenta[$ParteCuenta][$fila3[3]]['Creditos']=$MPTCuenta[$ParteCuenta][$fila3[3]]['Creditos']+$fila3[1];}
			$ParteAnt=$ParteCuenta;
		}
	}
////////DATOS DEL TERCERO/////////
	$cons89="Select PrimApe,SegApe,PrimNom,SegNom,Terceros.Identificacion,Cuenta from Central.Terceros,Contabilidad.Movimiento 
	where Movimiento.Identificacion=Terceros.Identificacion and Terceros.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]'
	and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Cuenta!='1' and Cuenta!='0'
	and Movimiento.Estado='AC' Group By Terceros.Identificacion,Cuenta,PrimApe,SegApe,PrimNom,SegNom";
	$res89=ExQuery($cons89);
	while($fila89=ExFetch($res89))
	{
		$MatTerceros[$fila89[5]][$fila89[4]]=array($fila89[0],$fila89[1],$fila89[2],$fila89[3],$fila89[4]);
	}
	
////////MATRIZ DE CARTERA X DOCS CUENTAS CREDITO///////////
        
	$cons2="Select sum(Haber) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
	Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Haber)>0 Order By Fecha Desc";

	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$Date2=$fila2[2];$Date1="$Corte";
		$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
		$fila2[5]=$d;

		$DiasMin=0;$DiasMax=0;$Periodo=0;
		for($i=0;$i<=count($Dias);$i++)
		{
			$DiasMax=$DiasMax+$Dias[$i];
			if($fila2[5]>=$DiasMin and $fila2[5]<=$DiasMax)
			{
				$Periodo=$i;break;
			}
			$DiasMin=$DiasMax;
		}
//		echo "$fila2[3]-->$fila2[4]--->$Periodo--->$fila2[5]--->$fila2[2]--->$fila2[0]<br>";
		$MatDocSoporte[$fila2[3]][$fila2[4]][$fila2[1]]=$Periodo;
		$MatCartera[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]]=array($MatCartera[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]][0]+$fila2[0],$fila2[1],$fila2[2]);

	}

	$cons3="Select sum(Debe) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
	Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Debe)>0";

	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		$Date2=$fila3[2];$Date1="$Corte";
		$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
		$fila3[5]=$d;

		$Periodo=0;
		$Periodo=$MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]];
		if(!count($MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]]))
		{
			$DiasMin=0;$DiasMax=0;
			for($i=0;$i<=count($Dias);$i++)
			{
				$DiasMax=$DiasMax+$Dias[$i];
				if($fila3[5]>=$DiasMin and $fila3[5]<=$DiasMax)
				{
					$Periodo=$i;break;
				}
				$DiasMin=$DiasMax;
			}
                        $VrGestion=floatval($MatCartera[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]])-floatval($fila3[0]);
			$MatCartera[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]]=array($VrGestion,$fila3[1],$fila3[2]);
			//$CarteraSinSoporte[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]]=array($CarteraSinSoporte[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]+$fila3[0],$fila3[1],$fila3[2]);
		}
                else
                {
                    $MatCartera[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]=$MatCartera[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]-$fila3[0];
                }
	}

        
////////MATRIZ DE CARTERA X DOCS CUENTAS DEBITO///////////
        
	$cons2="Select sum(Debe) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
	Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Debe)>0 Order By Fecha Desc";

	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$Date2=$fila2[2];$Date1="$Corte";
		$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
		$fila2[5]=$d;

		$DiasMin=0;$DiasMax=0;$Periodo=0;
		for($i=0;$i<=count($Dias);$i++)
		{
			$DiasMax=$DiasMax+$Dias[$i];
			if($fila2[5]>=$DiasMin and $fila2[5]<=$DiasMax)
			{
				$Periodo=$i;break;
			}
			$DiasMin=$DiasMax;
		}
//		echo "$fila2[3]-->$fila2[4]--->$Periodo--->$fila2[5]--->$fila2[2]--->$fila2[0]<br>";
		$MatDocSoporteDB[$fila2[3]][$fila2[4]][$fila2[1]]=$Periodo;
		$MatCarteraDB[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]]=array($MatCarteraDB[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]][0]+$fila2[0],$fila2[1],$fila2[2]);

	}

	$cons3="Select sum(Haber) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
	Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Haber)>0";

	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		$Date2=$fila3[2];$Date1="$Corte";
		$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
		$fila3[5]=$d;

		$Periodo=0;
		$Periodo=$MatDocSoporteDB[$fila3[3]][$fila3[4]][$fila3[1]];
		if(!count($MatDocSoporteDB[$fila3[3]][$fila3[4]][$fila3[1]]))
		{
			$DiasMin=0;$DiasMax=0;
			for($i=0;$i<=count($Dias);$i++)
			{
				$DiasMax=$DiasMax+$Dias[$i];
				if($fila3[5]>=$DiasMin and $fila3[5]<=$DiasMax)
				{
					$Periodo=$i;break;
				}
				$DiasMin=$DiasMax;
			}
                        $VrGestionDB=floatval($MatCarteraDB[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]])-floatval($fila3[0]);
			$MatCarteraDB[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]]=array($VrGestionDB,$fila3[1],$fila3[2]);
			//$CarteraSinSoporte[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]]=array($CarteraSinSoporte[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]+$fila3[0],$fila3[1],$fila3[2]);
		}
                else
                {
                    $MatCarteraDB[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]=$MatCarteraDB[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]-$fila3[0];
                }
	}        
        
/////////////////////////////////////////        
        
        $consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos,Tercero from Contabilidad.PlanCuentas 
	where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Compania='$Compania[0]' and Anio=$Anio
	and length(Cuenta)<=$NoDigitos Order By Cuenta";
	$resCta=ExQuery($consCta);echo ExError();

	while($filaCta=ExFetchArray($resCta))
	{
		$Debitos=$MPCuenta[$filaCta[0]]['Debitos'];
		$Creditos=$MPCuenta[$filaCta[0]]['Creditos'];
		$DebitosSI=$SICuenta[$filaCta[0]]['Debitos'];
		$CreditosSI=$SICuenta[$filaCta[0]]['Creditos'];
		
		if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
		if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
		elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}

		if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
		elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}

		if($DebitosSI || $CreditosSI || $Debitos || $Creditos){$Muestre=1;}
		if($IncluyeCeros=="on"){$Muestre=1;}
		if($Muestre==1)
		{
			$NumRec++;

			if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
			else{$BG="white";$Fondo=1;}

			if(!$PDF){
				echo "<tr bgcolor='$BG'>";
				echo "<td>";
				echo "$filaCta[0]</td><td colspan=3>$filaCta[1]</td>";
			}

			if($SaldoI<0 && $MovSI=="Debito"){$MovSI="Credito";$SaldoI=abs($SaldoI);}
			if($SaldoI<0 && $MovSI=="Credito"){$MovSI="Debito";$SaldoI=abs($SaldoI);}
			if($MovSI=="Debito"){if(!$PDF){echo "<td align='right'>".number_format($SaldoI,2)."</td><td align='right'>0.00</td>";}$SaldoIDB=$SaldoI;
			if(strlen($filaCta[0])==1){$TotDebitosSI=$TotDebitosSI+$SaldoI;}}
			else{if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoI,2)."</td>";}$SaldoICR=$SaldoI;
			if(strlen($filaCta[0])==1){$TotCreditosSI=$TotCreditosSI+$SaldoI;}}

			if(!$PDF){echo "<td align='right'>".number_format($Debitos,2)."</td><td align='right'>".number_format($Creditos,2)."</td>";}

			if($filaCta[3]=="Debito")
			{
				if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";}$SaldoFCR=$SaldoF;
				if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
				else{if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";}$SaldoFDB=$SaldoF;
				if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
			}
			elseif($filaCta[3]=="Credito")
			{
				if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";}$SaldoFDB=$SaldoF;
				if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
				else{if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";}$SaldoFCR=$SaldoF;
				if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
			}
			if(strlen($filaCta[0])==1)
			{
				$TotDebitosMov=$TotDebitosMov+$Debitos;
				$TotCreditosMov=$TotCreditosMov+$Creditos;
			}
			$Datos[$NumRec]=array($filaCta[0],$filaCta[1],$SaldoIDB,$SaldoICR,$Debitos,$Creditos,$SaldoFDB,$SaldoFCR);
			$SaldoIDB=0;$SaldoICR=0;$Debitos=0;$Creditos=0;$SaldoFDB=0;$SaldoFCR=0;
			if($filaCta[5]==1)
			{
				$NumRec++;
				if(count($MatTerceros[$filaCta[0]])>0){
				foreach($MatTerceros[$filaCta[0]] as $Identificacion)
				{
					$DebitosSI=$SITCuenta[$filaCta[0]][$Identificacion[4]]['Debitos'];
					$CreditosSI=$SITCuenta[$filaCta[0]][$Identificacion[4]]['Creditos'];
					
					$Debitos=$MPTCuenta[$filaCta[0]][$Identificacion[4]]['Debitos'];
					$Creditos=$MPTCuenta[$filaCta[0]][$Identificacion[4]]['Creditos'];

		
					if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
					if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
					elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}

					if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
					elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}

					if($SaldoI || $SaldoF || $Debitos || $Creditos){$Muestre=1;}
					if($IncluyeCeros=="on"){$Muestre=1;}

					if($Muestre)
					{$Muestre=0;$NumRec++;
						if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
						else{$BG="white";$Fondo=1;}
						if(!$PDF){echo "<tr bgcolor='$BG'>";}
						$Identid=explode("-",$Identificacion[4]);
						if(!$PDF){echo "<td>$filaCta[0]</td><td><ul>$Identid[0]</td><td>$Identid[1]</td><td>$Identificacion[0] $Identificacion[1] $Identificacion[2] $Identificacion[3]</td>";}

						if($SaldoI<0 && $MovSI=="Debito"){$MovSI="Credito";$SaldoI=abs($SaldoI);}
						if($SaldoI<0 && $MovSI=="Credito"){$MovSI="Debito";$SaldoI=abs($SaldoI);}
						if($MovSI=="Debito"){if(!$PDF){echo "<td align='right'>".number_format($SaldoI,2)."</td><td align='right'>0.00</td>";}$SaldoIDB=$SaldoI;
						if(strlen($filaCta[0])==1){$TotDebitosSI=$TotDebitosSI+$SaldoI;}}
						else
						{
							if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoI,2)."</td>";}$SaldoICR=$SaldoI;
							if(strlen($filaCta[0])==1){$TotCreditosSI=$TotCreditosSI+$SaldoI;}
						}

						if(!$PDF){echo "<td align='right'>".number_format($Debitos,2)."</td><td align='right'>".number_format($Creditos,2)."</td>";}

						if($filaCta[3]=="Debito")
						{
							if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";}$SaldoFCR=$SaldoF;
							if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
							else{if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";}$SaldoFDB=$SaldoF;
							if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
						}
						elseif($filaCta[3]=="Credito")
						{
							if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";}$SaldoFDB=$SaldoF;
							if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
							else{if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";}$SaldoFCR=$SaldoF;
							if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
						}
						$Datos[$NumRec]=array($filaCta[0],"        $Identid[0]-$Identid[1]	$Identificacion[0] $Identificacion[1] $Identificacion[2] $Identificacion[3]",$SaldoIDB,$SaldoICR,$Debitos,$Creditos,$SaldoFDB,$SaldoFCR);
						$SaldoIDB=0;$SaldoICR=0;$Debitos=0;$Creditos=0;$SaldoFDB=0;$SaldoFCR=0;
/////////////////////DIBUJAR LOS DOCUMENTOS X CADA TERCERO///////////////////
                                                if($filaCta[3]=="Credito")
                                                {
                                                    echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td colspan=8 align='right'>Fecha</td><td>Doc</td><td align='right'>Saldo</td></tr>";
                                                    for($i=0;$i<=count($Dias)-1;$i++)
                                                    {
                                                            if(count($MatCartera[$filaCta[0]][$Identificacion[4]][$i])!=0)
                                                            {
                                                                    foreach($MatCartera[$filaCta[0]][$Identificacion[4]][$i] as $Documento)
                                                                    {
                                                                            $TotPeriodo[$i]=$TotPeriodo[$i]+$Documento[0];
                                                                    }
                                                                    if($TotPeriodo[$i]!=0)
                                                                    {
    //                                                                        echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' $ColorBG align='right' ><td colspan=9>TOTAL PERIODO</td><td>".number_format($TotPeriodo[$i],2)."</td></tr>";
                                                                            foreach($MatCartera[$filaCta[0]][$Identificacion[4]][$i] as $Documento)
                                                                            {
                                                                                    if($Documento[0]!=0)
                                                                                    {
                                                                                            echo "<tr><td colspan=8 align='right'>$Documento[2]</td><td>$Documento[1]</td><td align='right'>".number_format($Documento[0],2)."</td></tr>";
                                                                                            $TotEntidad=$TotEntidad+$Documento[0];$TotPeriodo[$i]=0;
                                                                                    }
                                                                            }
                                                                    }

                                                                    }
                                                     }
                                                     if($TotEntidad!=0)
                                                         {
                                                             if(round($TotEntidad)!=round($SaldoF)){$ColorBG="style='color:red;'";}
                                                             else{$ColorBG="";}
                                                             echo "<tr style='font-weight:bold' $ColorBG align='right'><td colspan=9>TOTAL DOCUMENTOS</td><td>".number_format($TotEntidad,2)."</td></tr>";$TotEntidad=0;

                                                         }
                                                }
                                                if($filaCta[3]=="Debito")
                                                {
                                                    echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td colspan=7 align='right'>Fecha</td><td>Doc</td><td align='right'>Saldo</td></tr>";
                                                    for($i=0;$i<=count($Dias)-1;$i++)
                                                    {
                                                            if(count($MatCarteraDB[$filaCta[0]][$Identificacion[4]][$i])!=0)
                                                            {
                                                                    foreach($MatCarteraDB[$filaCta[0]][$Identificacion[4]][$i] as $Documento)
                                                                    {
                                                                            $TotPeriodo[$i]=$TotPeriodo[$i]+$Documento[0];
                                                                    }
                                                                    if($TotPeriodo[$i]!=0)
                                                                    {
    //                                                                        echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' $ColorBG align='right' ><td colspan=9>TOTAL PERIODO</td><td>".number_format($TotPeriodo[$i],2)."</td></tr>";
                                                                            foreach($MatCarteraDB[$filaCta[0]][$Identificacion[4]][$i] as $Documento)
                                                                            {
                                                                                    if($Documento[0]!=0)
                                                                                    {
                                                                                            echo "<tr><td colspan=7 align='right'>$Documento[2]</td><td>$Documento[1]</td><td align='right'>".number_format($Documento[0],2)."</td></tr>";
                                                                                            $TotEntidad=$TotEntidad+$Documento[0];$TotPeriodo[$i]=0;
                                                                                    }
                                                                            }
                                                                    }

                                                                    }
                                                     }
                                                     if($TotEntidad!=0)
                                                         {
                                                             if(round($TotEntidad)!=round($SaldoF)){$ColorBG="style='color:red;'";}
                                                             else{$ColorBG="";}
                                                             echo "<tr style='font-weight:bold' $ColorBG align='right'><td colspan=8>TOTAL DOCUMENTOS</td><td>".number_format($TotEntidad,2)."</td></tr>";$TotEntidad=0;

                                                         }
                                                }                                                
                                                
                                                }
				}}
			}
		}
		if($filaCta[0]=="1"){$TotActivo=$SaldoF;}
		if($filaCta[0]=="2"){$TotPasivo=$SaldoF;}
		if($filaCta[0]=="3"){$TotPatrimonio=$SaldoF;}
		$Muestre="N";
		$SaldoI=0;
	}
	if(!$PDF){
	echo "<tr bgcolor='#e5e5e5'>";
	echo "<td colspan=4 align='right'>";
	echo "<strong>SUMAS IGUALES</td>";
	echo "<td align='right'><strong>".number_format($TotDebitosSI,2)."</td>";
	echo "<td align='right'><strong>".number_format($TotCreditosSI,2)."</td>";
	echo "<td align='right'><strong>".number_format($TotDebitosMov,2)."</td>";
	echo "<td align='right'><strong>".number_format($TotCreditosMov,2)."</td>";

	echo "<td align='right'><strong>".number_format($TotSFDeb,2)."</td>";
	echo "<td align='right'><strong>".number_format($TotSFCred,2)."</td>";
	echo "</tr>";
?>
</table>
<br><center>

<br><br>
<?	}
	$BuscCargos=array("Representante","Contador");
	foreach($BuscCargos as $GenCargos)
	{
		$cons="Select Nombre,Cargo from Central.CargosxCompania where Compania='$Compania[0]' and FechaIni<='$PerFin' and FechaFin>='$PerFin' and Categoria='$GenCargos'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);

		$DatoCargo[$GenCargos][0]=$fila[0];
		$DatoCargo[$GenCargos][1]=$fila[1];
	}

        
class PDF extends FPDF
{
	function BasicTable($data)
	{
		$Anchos=array(25,90,25,25,25,25,25,25);
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
				if($x==1){$col=substr($col,0,50);}
				if($x>1){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				if($col=="SUMAS"){$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',8);}
				$this->Cell($Anchos[$x],5,$col,1,0,$Alinea,$fill);
				$x++;
			}
			$this->Ln();
		}
	}

//Cabecera de página
function Header()
{
	global $Compania;global $PerFin;
    //Logo
//    $this->Image('/Imgs/Logo.jpg',10,8,33);
    //Arial bold 15
    $this->SetFont('Arial','B',12);
    //Movernos a la derecha

    //Título
    $this->Cell(0,8,strtoupper($Compania[0]),0,0,'C');
    //Salto de línea
    $this->Ln(5);
    $this->SetFont('Arial','B',10);
    $this->Cell(0,8,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(5);
    $this->Cell(0,8,"BALANCE DE PRUEBA x TERCEROS",0,0,'C');
    $this->Ln(5);
    $this->Cell(0,8,"CORTE: $PerFin",0,0,'C');
    $this->Ln(10);
    $this->Cell(25,10,"Codigo",1,0,'C');
    $this->Cell(90,10,"Descripcion",1,0,'C');
    $this->Cell(50,5,"Saldo Anterior",1,0,'C');
    $this->Cell(50,5,"Movimientos del Periodo",1,0,'C');
    $this->Cell(50,5,"Saldo Final",1,0,'C');
    $this->Ln(5);
    $this->Cell(115,5,"",0,0,'C');
    $this->Cell(25,5,"Debitos",1,0,'C');
    $this->Cell(25,5,"Creditos",1,0,'C');

    $this->Cell(25,5,"Debitos",1,0,'C');
    $this->Cell(25,5,"Creditos",1,0,'C');

    $this->Cell(25,5,"Debitos",1,0,'C');
    $this->Cell(25,5,"Creditos",1,0,'C');

    $this->Ln(5);
}

//Pie de página
function Footer()
{
	global $ND;
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    $this->Ln(3);
    $this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
}
}

$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$pdf->BasicTable($Datos);

$pdf->Ln(20);
$pdf->Cell(120,8,"____________________________________",0,0,'C');
$pdf->Cell(120,8,"____________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(120,8,$DatoCargo['Representante'][0],0,0,'C');
$pdf->Cell(120,8,$DatoCargo['Contador'][0],0,0,'C');
$pdf->Ln(5);
$pdf->Cell(120,8,$DatoCargo['Representante'][1],0,0,'C');
$pdf->Cell(120,8,$DatoCargo['Contador'][1],0,0,'C');

if($PDF){$pdf->Output();}

?>