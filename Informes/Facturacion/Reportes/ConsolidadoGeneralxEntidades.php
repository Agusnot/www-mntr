<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	//exit;
	if($Mes<10){$Mes="0".$Mes;}	
	$DiaFFin=UltimoDia($Anio,$Mes);
	if($DiaFFin<10){$DiaFFin="0".$DiaFFin;}	
	$cons="Select Numero,Mes from Central.Meses";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Meses[$fila[0]]=array($fila[0],$fila[1]);
	}
	/*$cons="Select Ambito,codigo from Salud.Ambitos where compania='$Compania[0]' order by Ambito" ;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatAmbitos[$fila[0]]=array($fila[0],$fila[1]);	
	}*/
	$cons="Select Ambito,codigo,Hospitalizacion from Salud.Ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by Ambito desc" ;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{		
		$MatAmbitosNorm[$fila[0]]=array($fila[0],$fila[1],$fila[2]);
		if($fila[2]==1)
		{
			$fila[0]="Hospitalizacion";	
		}
		$MatAmbitos[$fila[0]]=array($fila[0],$fila[1]);	
	}	
	$cons="Select TipoAsegurador from central.terceros where Compania='$Compania[0]' and tipo='Asegurador' group by TipoAsegurador order 
	by TipoAsegurador";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{		
		if(!$fila[0]){$fila[0]="Otros";}
		$MatRegimen[$fila[0]]=$fila[0];
	}
	$cons="Select TipoAsegurador,Identificacion,PrimApe from central.terceros where Compania='$Compania[0]' and tipo='Asegurador' group by TipoAsegurador,Identificacion,PrimApe order by TipoAsegurador, PrimApe";
	$res=ExQuery($cons);
	
	while($fila=ExFetch($res))
	{		
		if(!$fila[0]){$fila[0]="Otros";}
		$MatTipoAsegurador[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2]);
	}	
	$cons="Select Entidad,Contrato,NoContrato,nofactura,Subtotal, copago, descuento,total from Facturacion.FacturasCredito where
	Compania='$Compania[0]' and Estado='AC'	and FechaCrea>='$Anio-$Mes-01 00:00:00' and FechaCrea<='$Anio-$Mes-$DiaFFin 23:59:59' 
	order by Entidad,Ambito,Contrato,NoContrato,NoFactura";
	//echo $cons;exit;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		/*if($fila[0]=="999999999"&&$fila[1]=="VERBAL AGUDOS Y URGENCIAS")
		{}
		else
		{				
			$consx="select grupo,tipo,sum(cantidad),vrunidad,almacenppal,codigo,nombre,generico,presentacion
			from facturacion.detallefactura where compania='$Compania[0]' and nofactura=$fila[3] and vrunidad!=0
			group by grupo,tipo,vrunidad,almacenppal,codigo,nombre,generico,presentacion order by grupo";	
			//echo $consx."<br><br>";
			$resx=ExQuery($consx);
			$Subtot=0;$TotF=0;
			while($filax=ExFetch($resx))
			{
				if($fila[0]=='800198972-6'||$fila[0]=='890399029-5'||$fila[0]=='891580016-8'||$fila[0]=='I891280001-0'||$fila[0]=='800103913-4'||$fila[3]=='50320')
				{
					if($filax[0]!="Medicamentos"&&$filax[0]!="Dispositivos medicos")
					{
						$Subtot=$Subtot+(round($filax[2])*round($filax[3]));
						//echo "$Entidad --> $fila[4] -->  si cobra disp";
					}
					else
					{
						//echo "$Entidad --> $fila[4] -->  no cobra disp";
					}				
				}
				else
				{
					$Subtot=$Subtot+(round($filax[2])*round($filax[3]));				
				}
				//echo "Subtot=$Subtot = > vrunidad=$filax[3] cantidad=$filax[2]<br><br>";
			}
			if($fila[3]=='50323'){$Subtot=1937440;}
			if($fila[3]=='50322'){$Subtot=1660000;}
			$Subtot=round($Subtot);$TotF=$Subtot-round($fila[5]);$TotF=round($TotF);		
			$MatFacturas[$fila[0]][$fila[1]][$fila[2]][0]=$fila[0];
			$MatFacturas[$fila[0]][$fila[1]][$fila[2]][1]=$fila[1];
			$MatFacturas[$fila[0]][$fila[1]][$fila[2]][2]=$fila[2];
			//$MatFacturas[$fila[0]][$fila[1]][$fila[2]][3]+=$Subtot;
			$MatFacturas[$fila[0]][$fila[1]][$fila[2]][3]+=$Subtot;
			$MatFacturas[$fila[0]][$fila[1]][$fila[2]][4]+=round($fila[5]);
			$MatFacturas[$fila[0]][$fila[1]][$fila[2]][5]+=round($fila[6]);
			$MatFacturas[$fila[0]][$fila[1]][$fila[2]][6]+=$TotF;	
		}	*/
//$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[4]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$SubTot,$fila[6],$fila[7],$TotF);	
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][0]=$fila[0];
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][1]=$fila[1];
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][2]=$fila[2];		
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][3]+=$fila[4];
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][4]+=round($fila[5]);
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][5]+=round($fila[6]);
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][6]+=$fila[7];	
	}		
	
	$cons="Select Identificacion,Contrato,Numero,PrimApe,NomRespPago from Central.Terceros,ContratacionSalud.Contratos 
	where Terceros.Compania='$Compania[0]' and Terceros.Compania=Contratos.Compania and Tipo='Asegurador' 
	and Terceros.Identificacion=Contratos.Entidad group by Identificacion,Contrato,Numero, 
	PrimApe, NomRespPago order by PrimApe,NomRespPago";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{
		if(!$fila[4]){$fila[4]=$fila[3];}
		$MatEntidades[$fila[0]][$fila[1]][$fila[2]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);	
	}	
?>
<head></head>
<body background="/Imgs/Fondo.jpg">
<?
$cont=0; $tab=0; $AmbitoAnt="";
if($MatFacturas)
{
	?>
	<table width="100%">
    <tr align="center">       
    <td rowspan="2">
    <strong><center><font style="font : 15px Tahoma;font-weight:bold">
	<? echo strtoupper($Compania[0]) ?><br /></font>
    <font style="font : 12px Tahoma;">
    <? echo $Compania[1]?><br /><? echo "$Compania[2] $Compania[3]"?><br /><br>
    </font>
    <font style="font : 12px Tahoma;font-weight:bold">	
    Consolidado General por Entidades<br>    
	<? 
	$UltD=UltimoDia($Anio,$Mes);
	$MI=number_format($Mes,0);$MF=number_format($Mes,0);
	if($MI==$MF){$Periodo="1 al $UltD de ".$Meses[$MF][1]." de $Anio";}
	else{$Periodo="1 de ".$Meses[$MI][1]." al $UltD de ".$Meses[$MF][1]." de $Anio";}?>   
    Periodo Comprendido entre el <? echo $Periodo?>    
   	</font>
    </center></strong> 
    </td> 
    <tr align="center">     
    </table>
    <br>
	<table align="center" border='1' bordercolor='#e5e5e5' style='font : normal normal small-caps 12px Tahoma;' >
    <tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'>
    <td>Entidad</td><td>Vr Facturación</td><td>Vr Copago</td><td>Vr Descuento</td><td>Vr Neto</td>
    </tr>
    <?	
	if($MatFacturas)
	{	
		$Totf=0;	$Totc=0;	$Totd=0;	$Totn=0;			
		foreach($MatRegimen as $Regimen)
		{
			if($MatTipoAsegurador[$Regimen])			
			{	
				$subtotf=0;	$subtotc=0;	$subtotd=0;	$subtotn=0;	
				?><tr bgcolor='#e5e5e5' style='font-weight:bold'><td colspan="5"><? echo "REGIMEN: ".strtoupper($Regimen);?></td></tr><?
				foreach($MatTipoAsegurador[$Regimen] as $Entidad)
				{					
					if($MatFacturas[$Entidad[1]])
					{
						foreach($MatFacturas[$Entidad[1]] as $Contrato)
						{
							foreach($Contrato as $NumContrato)
							{
								?>
								<tr>
								<td><? echo $MatEntidades[$NumContrato[0]][$NumContrato[1]][$NumContrato[2]][4]?></td>
								<td align="right"><? echo number_format($NumContrato[3],0);?></td>
								<td align="right"><? echo number_format($NumContrato[4],0);?></td>
								<td align="right"><? echo number_format($NumContrato[5],0);?></td>
								<td align="right"><? echo number_format($NumContrato[6],0);?></td>                            
								</tr>
								<?
								$subtotf+=$NumContrato[3];	$subtotc+=$NumContrato[4];	$subtotd+=$NumContrato[5];	$subtotn+=$NumContrato[6];	
								$Totf+=$NumContrato[3];	$Totc+=$NumContrato[4];	$Totd+=$NumContrato[5];	$Totn+=$NumContrato[6];
							}	
						}
					}
				}
				?><tr bgcolor='#e5e5e5' style='font-weight:bold' align="right"><td>Subtotal x Regimen</td>
                	<td><? echo number_format($subtotf,0);?></td>
                    <td><? echo number_format($subtotc,0);?></td>
                    <td><? echo number_format($subtotd,0);?></td>
                    <td><? echo number_format($subtotn,0);?></td>
                  </tr><?				
			}
		}
		?>
		<tr><td colspan="4">&nbsp;</td></tr>        
        <tr bgcolor='#e5e5e5' style='font-weight:bold' align="right"><td>Totales</td>
            <td><? echo number_format($Totf,0);?></td>
            <td><? echo number_format($Totc,0);?></td>
            <td><? echo number_format($Totd,0);?></td>
            <td><? echo number_format($Totn,0);?></td>
         </tr><?
	}				
	?>
    </table>
    <?
}
?>
</body>