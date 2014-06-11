<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$ND[mon]="0".$ND[mon];}
	if($ND[mday]<10){$ND[mday]="0".$ND[mday];}
	$FechaHoy=$ND[year]."-".$ND[mon]."-".$ND[mday];
	if($ND[hours]<10){$ND[hours]="0".$ND[hours];}
	if($ND[minutes]<10){$ND[minutes]="0".$ND[minutes];}
	if($ND[seconds]<10){$ND[seconds]="0".$ND[seconds];}
	$HoraHoy=$ND[hours].":".$ND[minutes].":".$ND[seconds];
	//--
	$AutoId=8;
	$FechaIni="2011-05-31 00:00:00";
	$FechaFin="2011-05-31 23:59:59";
	$Pagador="891280001-0";
	$cons="select cup, valor from contratacionsalud.cupsxplanes where autoid=$AutoId";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatTarifa[$fila[0]]=array($fila[0],$fila[1]);	
	}
	$cont=0;
	$cons="select noliquidacion,total,nofactura from facturacion.liquidacion where ambito!='Consulta Externa' and pagador='$Pagador' and fechacrea>='$FechaIni' and FechaCrea<='$FechaFin' order by nofactura";
	$res=ExQuery($cons);
	echo $cons."<br>";
	while($fila=ExFetch($res))
	{
		$cons1="Select codigo,cantidad from facturacion.detalleliquidacion where noliquidacion=$fila[0]";
		$res1=ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			if($MatTarifa[$fila1[0]])
			{
				$cont++;
				echo "$cont --> NL: $fila[0] --> codigo: $fila1[0] --> cantidad: $fila1[1] totalliq: $fila[1] --> valornew: ". $MatTarifa[$fila1[0]][1]."<br>";
				$ValorUni=$MatTarifa[$fila1[0]][1];
				$Cantidad=$fila1[1];
				$VrTotalLiq=($Cantidad*$ValorUni);
				echo "$VrTotalLiq<br>";
				$TotLiq[$fila[0]][0]=$fila[0];
				$TotLiq[$fila[0]][1]=$fila[2];				
				$TotLiq[$fila[0]][2]+=$VrTotalLiq;
				//update detalle-liquidacion
				$consxx="Update facturacion.detalleliquidacion set vrunidad=$ValorUni, vrtotal=$VrTotalLiq where
				noliquidacion=$fila[0] and codigo='$fila1[0]'";
				$resxx=ExQuery($consxx);
				//echo $consxx."<br>";
				$consxx="Update facturacion.detallefactura set vrunidad=$ValorUni, vrtotal=$VrTotalLiq where
				nofactura=$fila[2] and codigo='$fila1[0]'";
				$resxx=ExQuery($consxx);
				//echo $consxx."<br>";
			}	
		}
		//$Liquidaciones[$fila[0]]=array($fila[0],$fila[1]);	
	}	
	echo "<br><br>--- otrs valores liquidacion subtotal ---<br><br>";
	$cont=0;
	foreach($TotLiq as $Liquidacion)
	{
		//uñdate liquidacion
		$cont++;
		echo "$cont --> NL: ".$Liquidacion[0]." --> NoFac: $Liquidacion[1] --> SubTotal: ".$Liquidacion[2]."<br>";	
		$consxx="Update facturacion.liquidacion set subtotal=$Liquidacion[2], total=$Liquidacion[2] where
		noliquidacion=$Liquidacion[0]";
		//echo $consxx."<br>";
		$resxx=ExQuery($consxx);
		$consxx="Update facturacion.facturascredito set subtotal=$Liquidacion[2], total=$Liquidacion[2] where
		nofactura=$Liquidacion[1]";
		$resxx=ExQuery($consxx);
		//echo $consxx."<br>";
	}
?>