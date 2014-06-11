<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	ini_set("memory_limit","512M");
	include("Informes.php");	
	require('LibPDF/fpdf.php');
	$ND=getdate();
	if($MesIni<10){$MesIni="0".$MesIni;}
	if($MesFin<10){$MesFin="0".$MesFin;}
	$DiaFFin=UltimoDia($Anio,$MesFin);
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
	$cons="Select date_Part('mon',FechaCrea) as Mes,ambito,nofactura,Subtotal, copago, descuento,total from Facturacion.FacturasCredito where
	Compania='$Compania[0]' and Entidad='$Entidad' and Contrato='$Contrato' and NoContrato='$NumContrato' and Estado='AC' 
	and FechaCrea>='$Anio-$MesIni-01 00:00:00' and FechaCrea<='$Anio-$MesFin-$DiaFFin 23:59:59' order by Mes";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		//echo $fila[1]."<br>";
		/*if(substr($fila[1],0,8)=="Hospital"||substr($fila[1],0,8)=="Rehabili")
		{
			$fila[1]="Hospitalizacion";	
		}*/	
		if($MatAmbitosNorm[$fila[1]][2]==1)
		{
			$fila[1]="Hospitalizacion";	
		}
		/*if($fila[0]=="999999999"&&$fila[1]=="VERBAL AGUDOS Y URGENCIAS")
		{}
		else
		{
			if(substr($fila[1],0,8)=="Hospital"||substr($fila[1],0,8)=="Rehabili")
			{
				$fila[1]="Hospitalizacion";	
			}		
			$consx="select grupo,tipo,sum(cantidad),vrunidad,almacenppal,codigo,nombre,generico,presentacion
			from facturacion.detallefactura where compania='$Compania[0]' and nofactura=$fila[2] and vrunidad!=0
			group by grupo,tipo,vrunidad,almacenppal,codigo,nombre,generico,presentacion order by grupo";	
			//echo $consx."<br><br>";
			$resx=ExQuery($consx);
			$Subtot=0;$TotF=0;
			while($filax=ExFetch($resx))
			{
				if($Entidad=='800198972-6'||$Entidad=='890399029-5'||$Entidad=='891580016-8'||$Entidad=='I891280001-0'||$Entidad=='800103913-4'||$Entidad=='50320')
				{
					if($filax[0]!="Medicamentos"&&$filax[0]!="Dispositivos medicos")
					{
						$Subtot=$Subtot+(round($filax[2])*round($filax[3]));	
					}				
				}
				else
				{
					$Subtot=$Subtot+(round($filax[2])*round($filax[3]));				
				}
				//echo "Subtot=$Subtot = > vrunidad=$filax[3] cantidad=$filax[2]<br><br>";
			}
			$Subtot=round($Subtot);$TotF=$Subtot-round($fila[4]);$TotF=round($TotF);
			if($fila[2]=='50323'){$Subtot=1937440;$TotF=1937440;}
			if($fila[2]=='50322'){$Subtot=1660000;$TotF=1660000;}
			$MatFacturas[$fila[0]][$fila[1]][$fila[2]]=array($fila[0],$fila[1],$fila[2],$Subtot,$fila[4],$fila[5],$TotF);	
		}*/
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);	
	}
	
	$cons="Select NoFactura,Cedula,NumServicio,PrimApe,SegApe, PrimNom, SegNom from Facturacion.Liquidacion,Central.Terceros 
	where Liquidacion.Compania='$Compania[0]' and Liquidacion.Compania=Terceros.Compania and Cedula=Identificacion and Pagador='$Entidad'
	and Contrato='$Contrato' and NoContrato='$NumContrato' and Estado='AC' and NoFactura is not NULL 
	order by PrimApe,SegApe, PrimNom, SegNom, NoFactura";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{
		//if($fila[0]=='50320'){$nserv=$fila[2]; echo $nserv;}		
		$MatLiqTerc[$fila[0]]=array($fila[0],$fila[1],$fila[2],"$fila[3] $fila[4] $fila[5] $fila[6]");	
	}
	if($TipoPaciente){$constp="and Servicios.TipoUsuNarino='$TipoPaciente'";}
	$cons="Select Cedula,Servicios.NumServicio,FechaIng,FechaEgr,FechaIni,FechaFin from Salud.PagadorxServicios,Salud.Servicios 
	where PagadorxServicios.Compania='$Compania[0]' and PagadorxServicios.Compania=Servicios.Compania and 
	PagadorxServicios.NumServicio=Servicios.NumServicio and Entidad='$Entidad' and Contrato='$Contrato' and NoContrato='$NumContrato' $constp";
	$res=ExQuery($cons);	
	//echo $cons;
	while($fila=ExFetch($res))
	{
		//if($nserv==$fila[1]){echo $fila[1];}
		if($fila[5])
		{
			//$Dias=NumDias($fila[4],$fila[5]);	
		}	
		elseif($fila[3])
		{
			//$Dias=NumDias($fila[4],$fila[3]);	
		}
		else
		{
			//$Dias=NumDias($fila[4],"$ND[year]-$ND[mon]-$ND[mday]");	
		}
		$MatServiciosFech[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3],substr($fila[2],0,10),substr($fila[3],0,10),$Dias);			
	}			
if(!$PDF)
{
?>
<head></head>
<body background="/Imgs/Fondo.jpg">
<?
}
$cont=0; $tab=0; $AmbitoAnt="";
if($MatFacturas&&$MatAmbitos)
{
	if(!$PDF)
	{
	?>
	<table width="100%">
    <tr align="center">
    <td><? if(empty($Pri)){?><img src="/Imgs/Logo.jpg" width="80px" height="80px"><? }?></td>    
    <td rowspan="2">
    <strong><center><font style="font : 15px Tahoma;font-weight:bold">
	<? echo strtoupper($Compania[0]) ?><br /></font>
    <font style="font : 12px Tahoma;">    
    <? echo $Compania[1]?><br />
    <? echo "CODIGO SGSSS ".$Compania[17]?><br />
	<? echo "$Compania[2]  Telefonos: $Compania[3]"?><br /><br>
    </font>
    <font style="font : 12px Tahoma;font-weight:bold">	
    <? 
		$consx="Select nomresppago from ContratacionSalud.Contratos where Compania='$Compania[0]' and Entidad='$Entidad' and Contrato='$Contrato'
		and Numero='$NumContrato'";
		$resx=ExQuery($consx);
		$filax=ExFetch($resx);$NomEntidadPaga=$filax[0];
	?>
    Entidad Responsable del Pago: <? echo $NomEntidadPaga;?>
    <br>Nit <? if($Entidad=="D891280001-0-NaN"||$Entidad=="I891280001-0"){echo "891280001-0";
;}else{echo $Entidad;}?>
    <br><br>
    Consolidado por servicios prestados<br>    
	<? 
	$UltD=UltimoDia($Anio,$MesFin);
	$MI=number_format($MesIni,0);$MF=number_format($MesFin,0);
	if($MI==$MF){$Periodo="1 al $UltD de ".$Meses[$MF][1]." de $Anio";}
	else{$Periodo="1 de ".$Meses[$MI][1]." al $UltD de ".$Meses[$MF][1]." de $Anio";}?>   
    Periodo Comprendido entre el <? echo $Periodo?> 
    <br><br>
    <? if($TipoPaciente){ echo "Tipo Paciente: $TipoPaciente";}?>        
   	</font>
    </center></strong> 
    </td>
    <td rowspan="2" width="15%">&nbsp;</td>
    </tr>    
    </tr>
    <tr align="center">
    <td width="15%">
        <? if(empty($Pri)){?>
        <table border="1">
        <tr align="center"><td style='font-weight:bold'>Consecutivo Int</td></tr>
        <tr align="center"><td style='font-weight:bold'><? echo number_format($MesIni,0)." - ".$Anio;?></td></tr>    
        </table>
        <? }?>
    </td>        
    </table>
    <br>
	<table border='1' bordercolor='#e5e5e5' style='font : normal normal small-caps 10px Tahoma;' width='100%'>
    <tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'>
    <td>No</td><td>Factura</td><td>Identificación</td><td>Paciente</td><td>Ingreso</td><td>Egreso</td><td>Dias</td><td>Vr Factura</td><td>Vr Copago</td><td>Vr Total</td>
    </tr>
    <?
	}
	$cont=0;$ID=0;$cc=0;
	foreach($Meses as $Mesesito)	
	{
		if($MatFacturas[$Mesesito[0]])
		{			
			foreach($MatAmbitos as $Amb)
			{				
				unset($aamm);
				if($MatFacturas[$Mesesito[0]][$Amb[0]])
				{					
					$aamm=$Amb[0];						
					if($AmbitoAnt!=$aamm)
					{
						if($cont>0)
						{
							//SubTotales($Dias,$VrFactura,$VrCopago,$VrDescuento,$VrTotal);
							if(!$PDF)
							{
							?>
                            <tr bgcolor='#e5e5e5' style='font-weight:bold;font-size:12px'>
                            <td colspan="6"  align="right">Sub Total</td>
                            <td align="right"><? echo $Dias?></td>
                            <td align="right"><? echo number_format($VrFactura,0);?></td>
                            <td align="right"><? echo number_format($VrCopago,0);?></td>
                            <!--<td align="right"><? //echo number_format($VrDescuento,0);?></td>-->
                            <td align="right"><? echo number_format($VrTotal,0);?></td>
                            </tr>
                            <?
							}
							else
							{
								$SubTotalM="SUBTOTAL";
								$Datos[$ID]=array($cont,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][2],$NF[1],$NF[3],$FecIng,$FecEgr,$NDias,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][3],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][4],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][6],"",$SubTotalM,$Dias,$VrFactura,$VrCopago,$VrTotal);								
								$ID++;	
								$SubTotalM="";		
							}
							
						}
						$Dias=0;$VrFactura=0;$VrCopago=0;$VrDescuento=0;$VrTotal=0;
					if(!$PDF)
					{
					?>
					<tr bgcolor="#e5e5e5" style="font-weight:bold">
					<td colspan="10" align="center" style="font-size:12px"><? echo "Pacientes En $Amb[0]";?></td>
					</tr>		
					<?	
					}
					else
					{
						$AmbitoM="Pacientes En $Amb[0]";
						$Datos[$ID]=array($cont,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][2],$NF[1],$NF[3],$FecIng,$FecEgr,$NDias,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][3],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][4],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][6],$AmbitoM,"","","","","");						
						$ID++;	
						$AmbitoM="";
					}
					$AmbitoAnt=$aamm;				
					}
				}
				elseif($MatFacturas[$Mesesito[0]][$Amb[1]])
				{
					$aamm=$Amb[0];
					if($AmbitoAnt!=$aamm)
					{
						if($cont>0)
						{							
							if(!$PDF)
							{
							?>
                            <tr bgcolor='#e5e5e5' style='font-weight:bold;font-size:12px'>
                            <td colspan="6"  align="right">Sub Total</td>
                            <td align="right"><? echo $Dias?></td>
                            <td align="right"><? echo number_format($VrFactura,0);?></td>
                            <td align="right"><? echo number_format($VrCopago,0);?></td>                            
                            <td align="right"><? echo number_format($VrTotal,0);?></td>
                            </tr>
                            <?
							}
							else
							{
								$SubTotalM="SUBTOTAL";
								$Datos[$ID]=array($cont,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][2],$NF[1],$NF[3],$FecIng,$FecEgr,$NDias,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][3],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][4],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][6],"",$SubTotalM,$Dias,$VrFactura,$VrCopago,$VrTotal);								
								$ID++;	
								$SubTotalM="";	
							}
                        }
						$Dias=0;$VrFactura=0;$VrCopago=0;$VrDescuento=0;$VrTotal=0;
					if(!$PDF)
					{
					?>
					<tr bgcolor="#e5e5e5" style="font-weight:bold">
					<td colspan="9" align="center"><? echo "Pacientes En $Amb[0]";?></td>
					</tr>		
					<?
					}
					$AmbitoAnt=$aamm;				
					}
				}
				if(!empty($aamm))
				{				
					foreach($MatLiqTerc as $NF)
					{
					//foreach($MatFacturas[$Mesesito[0]][$aamm] as $NoFactura)
					//{
						if($MatFacturas[$Mesesito[0]][$aamm][$NF[0]])
						{					
							if($MatServiciosFech[$NF[1]][$NF[2]])
							{								
								$consc="select sum(cantidad) from facturacion.detallefactura where Compania='$Compania[0]' and NoFactura=".$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][2]." and grupo='01'";
								$resc=ExQuery($consc);
								$filac=ExFetch($resc);$NDias=$filac[0];
								$cont++;
								$tab++;
								//$Dias+=$MatServiciosFech[$MatLiqTerc[$NoFactura[2]][1]][$MatLiqTerc[$NoFactura[2]][2]][6];
								$Dias+=$NDias;
								/*$VrFactura+=$NoFactura[3];	
								$VrCopago+=$NoFactura[4];
								$VrDescuento+=$NoFactura[5];
								$VrTotal+=$NoFactura[6];
								$TotDias+=$NDias;;
								$TotVrFactura+=$NoFactura[3];	
								$TotVrCopago+=$NoFactura[4];
								$TotVrDescuento+=$NoFactura[5];
								$TotVrTotal+=$NoFactura[6];						*/
								$VrFactura+=$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][3];	
								$VrCopago+=$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][4];
								$VrDescuento+=$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][5];
								$VrTotal+=$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][6];
								$TotDias+=$NDias;;
								$TotVrFactura+=$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][3];	
								$TotVrCopago+=$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][4];
								$TotVrDescuento+=$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][5];
								$TotVrTotal+=$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][6];	
								if(!$PDF)
								{	
								?>
								<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
								<td align="right"><? echo $cont?></td>
								<td align="right"><? echo $MatFacturas[$Mesesito[0]][$aamm][$NF[0]][2]?></td>
								<td align="left"><? echo $NF[1]?></td>
								<td align="left"><? echo $NF[3]?></td>                    
								<td align="center"><? if($MatServiciosFech[$NF[1]][$NF[2]][4]){echo $MatServiciosFech[$NF[1]][$NF[2]][4];}else{echo "0000-00-00";}?></td>
								<td align="center"><? if($MatServiciosFech[$NF[1]][$NF[2]][5]){echo $MatServiciosFech[$NF[1]][$NF[2]][5];}else{echo "0000-00-00";}?></td>
								<td align="right"><? echo number_format($NDias,0);?></td>
								<td align="right"><? echo number_format($MatFacturas[$Mesesito[0]][$aamm][$NF[0]][3],0);?></td>
								<td align="right"><? echo number_format($MatFacturas[$Mesesito[0]][$aamm][$NF[0]][4],0);?></td>
								<!--<td align="right"><? //echo number_format($NoFactura[5],0);?></td>-->
								<td align="right"><? echo number_format($MatFacturas[$Mesesito[0]][$aamm][$NF[0]][6],0);?></td>                  
								</tr>		
								<?					
								}
								else
								{
									$AmbitoM="";$SubTotalM="";
									if($MatServiciosFech[$NF[1]][$NF[2]][4]){$FecIng=$MatServiciosFech[$NF[1]][$NF[2]][4];}else{$FecIng="0000-00-00";}
									if($MatServiciosFech[$NF[1]][$NF[2]][5]){$FecEgr=$MatServiciosFech[$NF[1]][$NF[2]][5];}else{$FecEgr="0000-00-00";}
																
									$Datos[$ID]=array($cont,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][2],$NF[1],$NF[3],$FecIng,$FecEgr,$NDias,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][3],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][4],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][6],"","","","","","");
									$ID++;
								}
							}
							else
							{
								if($aamm=="Consulta Externa")
								{
									$cc++;
									//echo "no ta: $cc --> Fact: $NF[0] --> Cedula: $NF[1] --> NumServ: $NF[2] --> $Entidad --> $Contrato --> $NumContrato<br>";
								}		
							}
						}
                    }
                }		
			}						
		}			
	}
	if($AmbitoAnt!="")
	{
		//SubTotales($Dias,$VrFactura,$VrCopago,$VrDescuento,$VrTotal);		
		if(!$PDF)
		{
		?>
        <tr bgcolor='#e5e5e5' style='font-weight:bold;font-size:12px'>
        <td colspan="6"  align="right">Sub Total</td>
        <td align="right"><? echo $Dias?></td>
        <td align="right"><? echo number_format($VrFactura,0);?></td>
        <td align="right"><? echo number_format($VrCopago,0);?></td>
        <!--<td align="right"><? //echo number_format($VrDescuento,0);?></td>-->
        <td align="right"><? echo number_format($VrTotal,0);?></td>
        </tr>
        <?
		}
		else
		{
			$SubTotalM="SUBTOTAL";
			$Datos[$ID]=array($cont,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][2],$NF[1],$NF[3],$FecIng,$FecEgr,$NDias,$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][3],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][4],$MatFacturas[$Mesesito[0]][$aamm][$NF[0]][6],"",$SubTotalM,$Dias,$VrFactura,$VrCopago,$VrTotal);			
			$ID++;	
			$SubTotalM="";		
		}
	}
	if(!$PDF)
	{
	?>
    <tr >
    <td >&nbsp;</td><td ></td><td ></td><td ></td><td ></td><td ></td>
    <td ></td><td ></td><td ></td><td ></td>
    </tr>
	<tr bgcolor='#e5e5e5' style='font-weight:bold; font-size:12px' >
    <td colspan="6"  align="right">Total</td>
    <td align="right"><? echo $TotDias?></td>
    <td align="right"><? echo number_format($TotVrFactura,0);?></td>
    <td align="right"><? echo number_format($TotVrCopago,0);?></td>
    <!--<td align="right"><? echo number_format($TotVrDescuento,0);?></td>-->
    <td align="right"><? echo number_format($TotVrTotal,0);?></td>
    </tr>
    </table>
    <?
	}
	else
	{
		class PDF extends FPDF
		{//192,168.1.110
			function BasicTable($data)
			{				
				global $TotDias; global $TotVrFactura; global $TotVrCopago; global $TotVrTotal;
				$Anchos=array(7,11,20,67,16,16,8,20,17,20); 					
				$fill=false;$this->SetFillColor(248,248,248);		
				if(!$data){exit;}
				$am="";
				foreach($data as $row)
				{
					$POSY=$this->GetY();
					if(!empty($row[10]))
					{
						$this->SetFillColor(228,228,228);
						$fill=true;
						$this->SetFont('Arial','B',10);
						$this->Cell(202,5,strtoupper(utf8_decode_seguro($row[10])),1,0,'C',$fill);
						$this->Ln();
						$this->SetFont('Arial','',10);
						$fill=false;
						$this->SetTextColor(0,0,0);	
						$am=1;					
					}
					elseif(!empty($row[11]))
					{
						$this->SetFillColor(228,228,228);
						$fill=true;
						$this->SetFont('Arial','B',9);
						$this->Cell(137,5,strtoupper(utf8_decode_seguro($row[11])),1,0,'R',$fill);
						$this->Cell(8,5,round($row[12],0),1,0,'R',$fill);
						$this->Cell(20,5,number_format($row[13],0),1,0,'R',$fill);
						$this->Cell(17,5,number_format($row[14],0),1,0,'R',$fill);
						$this->Cell(20,5,number_format($row[15],0),1,0,'R',$fill);
						$this->Ln();
						$this->SetFont('Arial','',10);
						$fill=false;
						$this->SetTextColor(0,0,0);
					}						
					else
					{
						$x=0;
						$this->SetTextColor(0,0,0);	
						$this->SetFont('Arial','',8);				
						foreach($row as $col)
						{				
							if($x>5){$Alinea='R';$col=number_format($col,0);}elseif($x==2||$x==3){$col=utf8_decode_seguro($col);$Alinea="L";}else{$Alinea="C";}
							if($col=="TOTAL"){$Final=1;$Alinea="R";}
							if($Final)
							{
								$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',7);$Lines="LRBT";
							}
							else
							{
								if($POSY>=250 && $POSY<255){$Lines="LRB";}
								else{$Lines="LR";}
							}
							if($x==0||$x==1){$Alinea='R';}
							if($x==2){$col=substr($col,0,52);$Alinea='L';}
							$this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,$Alinea,$fill);
							$x++;
							if($x>9){break;}
						}
						$this->Ln();
						$fill=!$fill;
					}
				}
				$POSYY=$POSY;
				if($POSY<200)
				{
					for($i=$POSY;$i<=200;$i+=5)
					{
						$this->Cell(137,5,"","LR",0,'C',0);
						$this->Cell(8,5,"","LR",0,'R',0);
						$this->Cell(20,5,"","LR",0,'R',0);
						$this->Cell(17,5,"","LR",0,'R',0);
						$this->Cell(20,5,"","LR",0,'R',0);
						$this->Ln();
					}
				}
				$this->SetFillColor(228,228,228);
				$fill=true;
				$this->SetFont('Arial','B',8);
				$this->Cell(137,5,"TOTALES",1,0,'R',$fill);
				$this->Cell(8,5,round($TotDias,0),1,0,'R',$fill);
				$this->Cell(20,5,number_format($TotVrFactura,0),1,0,'R',$fill);
				$this->Cell(17,5,number_format($TotVrCopago,0),1,0,'R',$fill);
				$this->Cell(20,5,number_format($TotVrTotal,0),1,0,'R',$fill);
				$this->Ln(8);
				$this->SetFont('Arial','',10);					
				if($this->GetY()>=223){$this->AddPage();}
				$ruta=$_SERVER['DOCUMENT_ROOT'];
				$this->Image("$ruta/Firmas/12990830.PNG",90,223,40,15);			
				$this->Ln(20);
				$this->SetXY(80,237);	
				$this->Cell(60,5,"FIRMA RESPONSABLE","T",0,'C');				
			}
		
			//Cabecera de página
			function Header()
			{
				global $Compania; global $ND; global $Entidad; global $Contrato; global $NumContrato; global $Anio; global $MesIni; global $MesFin;				
				global $Meses; global $TipoPaciente;
				$consx="Select nomresppago from ContratacionSalud.Contratos where Compania='$Compania[0]' and Entidad='$Entidad' 
				and Contrato='$Contrato' and Numero='$NumContrato'";
				$resx=ExQuery($consx);
				$filax=ExFetch($resx);$NomEntidadPaga=$filax[0];
				//Logo
				$Raiz = $_SERVER['DOCUMENT_ROOT'];
    			$this->Image("$Raiz/Imgs/Logo.jpg",10,5,20,20);
				//Arial bold 15		
				$this->SetFont('Arial','B',12);		
				$Y=$this->GetY();
				$this->SetXY(7,38);
				$this->Cell(35,5,"Consecutivo Int",1,0,'C');
				$this->Ln();
				$this->SetX(7);
				$this->Cell(35,5,number_format($MesIni,0)." - $Anio",1,0,'C');
				
				$this->SetFont('Arial','I',8);
				//Número de página
				$this->SetXY(193,45);
				$this->Cell(17,5,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
				//Movernos a la derecha			
				//Título
				$this->SetFont('Arial','B',14);
				$this->SetY($Y);
				$this->Cell(0,8,strtoupper($Compania[0]),0,0,'C');
				//Salto de línea
				$this->Ln(4);	
				$this->SetFont('Arial','',10);							
				$this->Cell(0,8,strtoupper($Compania[1]),0,0,'C');
				$this->Ln(4);
				$this->Cell(0,8,"CODIGO SGSSS ".strtoupper($Compania[17]),0,0,'C');
				$this->Ln(4);
				$this->Cell(0,8,"$Compania[2] Telefonos: $Compania[3]",0,0,'C');
				$this->Ln(10);								
				$this->SetFont('Arial','',11);
				$this->Cell(0,8,"Entidad Responsable del Pago:",0,0,'C');
				$this->Ln(5); 				
				$this->SetFont('Arial','B',11);
				$this->Cell(0,8,strtoupper(utf8_decode_Seguro($NomEntidadPaga)),0,0,'C'); 				
				if($Entidad=="D891280001-0-NaN"||$Entidad=="I891280001-0"){$NitE="891280001-0";
;}else{$NitE=$Entidad;}
				$this->Ln(4);
				$this->SetFont('Arial','',10);
				$this->Cell(0,8,"NIT ".$NitE,0,0,'C'); 
				$this->Ln(8);
				$this->SetFont('Arial','B',10);
				$this->Cell(0,8,"CONSOLIDADO POR SERVICIOS PRESTADOS",0,0,'C');  
				$this->Ln(4);				
				$UltD=UltimoDia($Anio,$MesFin);
				$MI=number_format($MesIni,0);$MF=number_format($MesFin,0);
				if($MI==$MF){$Periodo="1 al $UltD de ".$Meses[$MF][1]." de $Anio";}
				else{$Periodo="1 de ".$Meses[$MI][1]." al $UltD de ".$Meses[$MF][1]." de $Anio";}			   
				$this->Cell(0,8,"Periodo Comprendido Entre el: $Periodo",0,0,'C'); 
				if($TipoPaciente)
				{
					$this->Ln();
					$this->Cell(0,8,"Tipo Paciente: $TipoPaciente",0,0,'C'); 
				}          	
				$this->Ln(13);				
				$this->SetFillColor(228,228,228);
				$this->SetFont('Arial','B',8);			
				$this->Cell(7,5,"No",1,0,'C',1);
				$this->Cell(11,5,"Factura",1,0,'C',1);
				$this->Cell(20,5,"Identificacion",1,0,'C',1);
				$this->Cell(67,5,"Paciente",1,0,'C',1);
				$this->Cell(16,5,"Ingreso",1,0,'C',1);
				$this->Cell(16,5,"Egreso",1,0,'C',1);    
				$this->Cell(8,5,"Dias",1,0,'C',1); 
				$this->Cell(20,5,"Vr Factura",1,0,'C',1);       
				$this->Cell(17,5,"Vr Copago",1,0,'C',1);    
				$this->Cell(20,5,"Vr Total",1,0,'C',1);    
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
				//$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
				$this->Ln(3);
				//$this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
			}
		}
		//--
		$pdf=new PDF('P','mm','Letter'); //P horizontal L vertical
		$pdf->SetMargins(7, 8);
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);
		$pdf->BasicTable($Datos);		
		$pdf->Output();	
	}
}
?>
</body>