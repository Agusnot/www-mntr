<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($ExcluyeComprobantes=="1"){$ExcluyeComprobantes="1=1";}
	if(!$CuentaIni){$CuentaIni=0;}
	if(!$CuentaFin){$CuentaFin=9999999999;}
	$NoDigitos=6;
	$ND=getdate();
$Separador=";";
?>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>


<?
	function Encabezados()
	{
		global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;global $Anio;global $MesIni;global $MesFin;
		?>
		<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
		<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>S.I.A. Catalogo de Cuentas<br>Periodo: <?echo "$NombreMes[$MesIni] a $NombreMes[$MesFin] de $Anio"?></td></tr>
		<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		<tr style="font-weight:bold" bgcolor="#e5e5e5" style="text-align:center;">
		<td>Codigo</td>
		<td>Nombre Cuenta</td><td>Saldo Anterior</td><td>Debitos</td><td>Creditos</td><td>Saldo Corriente</td><td>Saldo No Corriente</td></tr>
		
<?	}
	$NumRec=0;$NumPag=1;
	Encabezados();

	$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio='$Anio' Order By Nivel";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetchArray($res))
	{
		$Nivel++;
		if(!$fila[0]){$fila[0]="-100";}
		$TotCaracteres=$TotCaracteres+$fila[0];
		$Digitos[$Nivel]=$TotCaracteres;
		$TotNiveles++;
	}

	$Archivo=$Archivo."Codigo $Separador Nombre $Separador  Saldo Inicial $Separador Debitos $Separador Creditos $Separador Saldo Corriente $Separador Saldo NO Corriente\n";

	$cons2="Select sum(Debe),sum(Haber),Movimiento.Cuenta,Corriente,date_part('year',Fecha) as Anio from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Fecha<'$Anio-$MesIni-01' and Movimiento.Compania='$Compania[0]' and Estado='AC' 
	and Movimiento.Cuenta!='0' and PlanCuentas.Anio=$Anio and PlanCuentas.Compania='$Compania[0]' and $ExcluyeComprobantes  
	Group By Movimiento.Cuenta,Corriente,Fecha Order By Cuenta";
	$res2=ExQuery($cons2);echo ExError();

	while($fila2=ExFetch($res2))
	{
		$CuentaMad=substr($fila2[2],0,1);
		if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[4]){}
		else{
		for($Nivel=1;$Nivel<=$TotNiveles;$Nivel++)
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
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-31' and Movimiento.Compania='$Compania[0]' and Estado='AC' 
	and Movimiento.Cuenta!='0' and PlanCuentas.Anio=$Anio and PlanCuentas.Compania='$Compania[0]' and $ExcluyeComprobantes  
	Group By Movimiento.Cuenta,Corriente Order By Cuenta";

	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{

		for($Nivel=1;$Nivel<=6;$Nivel++)
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
	and length(Cuenta)=$NoDigitos Order By Cuenta";

	$resCta=ExQuery($consCta);echo ExError();

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


			echo "<td align='right'>".number_format($SF['Corriente'])."</td><td align='right'>".number_format($SF['NoCorriente'])."</td>";

			if(strlen($filaCta[0])==1)
			{
				$TotDebitosMov=$TotDebitosMov+$Debitos;
				$TotCreditosMov=$TotCreditosMov+$Creditos;
			}
			$filaCta[1]=str_replace(",","-",$filaCta[1]);
			$Archivo=$Archivo."$filaCta[0] $Separador $filaCta[1] $Separador ".round($SaldoI,0)."$Separador".round($Debitos,0)."$Separador".round($Creditos,0)."$Separador".round($SF['Corriente'],0)."$Separador".round($SF['NoCorriente'],0)."\n";


		}
		if($filaCta[0]=="1"){$TotActivo=$SaldoF;}
		if($filaCta[0]=="2"){$TotPasivo=$SaldoF;}
		if($filaCta[0]=="3"){$TotPatrimonio=$SaldoF;}
		$Muestre="N";
		$SaldoI=0;
	}
	$fichero = fopen("SIACatalogoCuentas.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td colspan=7><a href='SIACatalogoCuentas.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";

?>
