<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");		
	$ND=getdate();
	if($Mes<10){$Mes="0".$Mes;}	
	$DiaFFin=UltimoDia($Anio,$Mes);
	if($DiaFFin<10){$DiaFFin="0".$DiaFFin;}	
	$cons="Select Numero,Mes from Central.Meses";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Meses[$fila[0]]=array($fila[0],$fila[1]);
	}		
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
	$cons="Select Entidad,ambito,Contrato,NoContrato,nofactura,Subtotal, copago, descuento,total from Facturacion.FacturasCredito where
	Compania='$Compania[0]' and Estado='AC'	and FechaCrea>='$Anio-$Mes-01 00:00:00' and FechaCrea<='$Anio-$Mes-$DiaFFin 23:59:59' 
	order by Entidad,Ambito,Contrato,NoContrato,NoFactura";
	//echo $cons;exit;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($MatAmbitosNorm[$fila[1]][2]==1)
		{
			$fila[1]="Hospitalizacion";	
		}
		
		/*if($fila[0]=="999999999"&&$fila[2]=="VERBAL AGUDOS Y URGENCIAS")
		{}
		else
		{
					
			$consx="select grupo,tipo,sum(cantidad),vrunidad,almacenppal,codigo,nombre,generico,presentacion
			from facturacion.detallefactura where compania='$Compania[0]' and nofactura=$fila[4] and vrunidad!=0
			group by grupo,tipo,vrunidad,almacenppal,codigo,nombre,generico,presentacion order by grupo";	
			//echo $consx."<br><br>";
			$resx=ExQuery($consx);
			$Subtot=0;$TotF=0;
			while($filax=ExFetch($resx))
			{				
				if($fila[0]=='800198972-6'||$fila[0]=='890399029-5'||$fila[0]=='891580016-8'||$fila[0]=='I891280001-0'||$fila[0]=='800103913-4'||$fila[4]=='50320')
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
			if($fila[4]=='50323'){$Subtot=1937440;}
			if($fila[4]=='50322'){$Subtot=1660000;}
			$Subtot=round($Subtot);$TotF=$Subtot-round($fila[6]);$TotF=round($TotF);								
			$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[4]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$SubTot,$fila[6],$fila[7],$TotF);	
		}*/
		//echo "NF: $fila[4] --> Subtot: $fila[5] --> Copago: $fila[6] --> Desc: $fila[7] --> tot: $fila[8]<br>";
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[4]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8]);
	}	
	$cons="Select NoFactura,Cedula,PrimApe,SegApe, PrimNom, SegNom from Facturacion.Liquidacion,Central.Terceros 
	where Liquidacion.Compania='$Compania[0]' and Liquidacion.Compania=Terceros.Compania and Cedula=Identificacion 
	and Estado='AC' and NoFactura is not NULL order by PrimApe,SegApe, PrimNom, SegNom, NoFactura";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatLiqTerc[$fila[0]]=array($fila[0],$fila[1],"$fila[2] $fila[3] $fila[4] $fila[5]");	
	}	
		
	$cons="Select Identificacion,Contrato,Numero,PrimApe,NomRespPago from Central.Terceros,ContratacionSalud.Contratos 
	where Terceros.Compania='$Compania[0]' and Terceros.Compania=Contratos.Compania and Tipo='Asegurador' 
	and Terceros.Identificacion=Contratos.Entidad and Contratos.Estado='AC' group by Identificacion,Contrato,Numero, 
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
if($MatFacturas&&$MatAmbitos&&$MatEntidades)
{		
	?>
    <table width="100%">
    <tr align="center">
    <td><img src="/Imgs/Logo.jpg" width="80px" height="80px"></td>    
    <td rowspan="2">
    <strong><center><font style="font : 15px Tahoma;font-weight:bold">
	<? echo strtoupper($Compania[0]) ?><br /></font>
    <font style="font : 12px Tahoma;">
    <? echo $Compania[1]?><br /><? echo "$Compania[2] $Compania[3]"?><br /><br>
    </font>
    <font style="font : 12px Tahoma;font-weight:bold">	
    Consolidado por servicios prestados<br>    
	<? 	
	$M=number_format($Mes,0);
	$Periodo="Mes de ".$Meses[$M][1]." de $Anio";
	echo $Periodo;
	?>    
   	</font>
    </center></strong> 
    </td>
    <td rowspan="2" width="15%">&nbsp;</td>
    </tr>    
    </tr>
    <tr align="center">
    <td width="15%">         
    </td>        
    </table>
    <br />
	<table border='1' bordercolor='#e5e5e5' style='font : normal normal small-caps 12px Tahoma;' width='100%'>    
	<?
	$Total=0;	
	foreach($MatEntidades as $Ent)
	{
		foreach($Ent as $Contrato)
		{			
			foreach($Contrato as $NumContrato)
			{
				if($MatFacturas[$NumContrato[0]])
				{						
					$TotEntidad=0;$ExEnt=0;
					
					foreach($MatAmbitos as $Amb)
					{
						if($MatFacturas[$NumContrato[0]][$Amb[0]][$NumContrato[1]][$NumContrato[2]])
						{									
							//echo "$NomPag[0] -- $Amb[0]<br>";
							$ExEnt=1;
							?>
							<tr>
							<td colspan="4" bgcolor='#e5e5e5' style='font-weight:bold' align='center'><? echo "Pacientes en $Amb[0] - $NumContrato[4] ";?></td>
							</tr>
                            <tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'>
                            <td>No Factura</td><td>Paciente</td><td>Valor Factura</td><td>Firma</td>
                            </tr>
							<?
							$SubTotPac=0;
							foreach($MatFacturas[$NumContrato[0]][$Amb[0]][$NumContrato[1]][$NumContrato[2]] as $NoFactura)
							{?>
							<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
							<td align="center"><? echo $NoFactura[4]?></td>
							<td><? echo $MatLiqTerc[$NoFactura[4]][2];?></td>
							<td align="right"><? echo number_format($NoFactura[8],0);?></td>
							<td>&nbsp;</td>
							</tr>		
							<?
								$SubTotPac=$SubTotPac+$NoFactura[8];
								$TotEntidad=$TotEntidad+$NoFactura[8];
								$Total=$Total+$NoFactura[8];
							}
							?>
							<tr style='font-weight:bold'>
								<td colspan="3" align="right" >Subtotal Pacientes</td>
								<td align="right" ><? echo number_format($SubTotPac,0);?></td>
							</tr>
							<?	
						}
					}
					if($ExEnt)
					{
						?>
						<tr bgcolor='#e5e5e5' style='font-weight:bold; font-size:16'>
							<td colspan="3" align="right" >Total Entidad</td>
							<td align="right" ><? echo number_format($TotEntidad,0);?></td>
						</tr>
                        <tr>
                        	<td colspan="4">&nbsp;</td>
                        </tr>
						<?					
					}
				}
			}
		}
	}		
	?>       
    <tr bgcolor='#e5e5e5' style='font-weight:bold; font-size:16'>
                <td colspan="3" align="right" >Total</td>
                <td align="right" ><? echo number_format($Total,0);?></td>
            </tr>
    </table>
    <?
}
?>
</body>