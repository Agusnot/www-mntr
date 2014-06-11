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
	$cons="Select TipoAsegurador from central.terceros where Compania='$Compania[0]' and tipo='Asegurador' group by TipoAsegurador order 
	by TipoAsegurador";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{		
		if(!$fila[0]){$fila[0]="Otros";}
		$MatRegimen[$fila[0]]=$fila[0];
	}
	$cons="Select TipoAsegurador,Identificacion,PrimApe from central.terceros where Compania='$Compania[0]' and tipo='Asegurador' group by TipoAsegurador,Identificacion,PrimApe order by TipoAsegurador,PrimApe";
	$res=ExQuery($cons);
	
	while($fila=ExFetch($res))
	{		
		if(!$fila[0]){$fila[0]="Otros";}
		$MatTipoAsegurador[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2]);
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
		$MatTodasEnt[$fila[0]]=$fila[0];
	}
	$cons="select codigo,grupo from contratacionsalud.gruposservicio where compania='$Compania[0]' order by Grupo";
	$res=ExQuery($cons);
	//echo $cons;
	$cc=0;
	while($fila=ExFetch($res))
	{
		$cc++;
		$MatGrupos[$fila[0]]=array($fila[0],$fila[1]);
	}
	$contttt=0;
	$cons="Select Entidad,Contrato,NoContrato,nofactura,Subtotal, copago, descuento,total from Facturacion.FacturasCredito where
	Compania='$Compania[0]' and Estado='AC'	and FechaCrea>='$Anio-$Mes-01 00:00:00' and FechaCrea<='$Anio-$Mes-$DiaFFin 23:59:59' 
	order by Entidad,Ambito,Contrato,NoContrato,NoFactura";
	//echo $cons;
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{
		
		/*if($fila[0]=="999999999"&&$fila[1]=="VERBAL AGUDOS Y URGENCIAS")
		{}
		else
		{*/
			$consx="select grupo,tipo,sum(vrtotal),almacenppal
			from facturacion.detallefactura where compania='$Compania[0]' and nofactura=$fila[3] and vrunidad!=0
			group by grupo,tipo,almacenppal order by grupo";	
			//echo $consx."<br><br>";exit;
			$resx=ExQuery($consx);
			$Subtot=0;$TotF=0;
			while($filax=ExFetch($resx))
			{
				/*if($fila[0]=='800198972-6'||$fila[0]=='890399029-5'||$fila[0]=='891580016-8'||$fila[0]=='I891280001-0'||$fila[0]=='800103913-4'||$fila[3]=='50320')
				{
					if($filax[0]!="Medicamentos"&&$filax[0]!="Dispositivos medicos")
					{
						if($MatGrupos[$filax[0]])
						{
							$MatGrupos[$filax[0]][2]=1;
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]=$fila[0];
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][1]=$fila[1];		
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][2]=$fila[2];
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][3]=$filax[0];
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]+=round(round($filax[2])*round($filax[3]));
							if(empty($GrxEnt[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]))
							{
								$GrxEnt[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]=1;
								$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][7]++;							
							}
							//$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][5]+=round($MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]);
							$Subtot=$Subtot+(round($filax[2])*round($filax[3]));		
						}
						else
						{
							$cc++;
							$MatGrupos[$filax[0]]=array($filax[0],$filax[0],1);
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]=$fila[0];
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][1]=$fila[1];		
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][2]=$fila[2];
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][3]=$filax[0];
							$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]+=round(round($filax[2])*round($filax[3]));						
							if(empty($GrxEnt[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]))
							{
								$GrxEnt[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]=1;
								$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][7]++;						
							}
							//$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][5]+=round($MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]);		
							$Subtot=$Subtot+(round($filax[2])*round($filax[3]));		
						}
					}								
				/*}
				else
				{*/
					if($MatGrupos[$filax[0]])
					{
						$MatGrupos[$filax[0]][2]=1;
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]=$fila[0];
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][1]=$fila[1];		
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][2]=$fila[2];
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][3]=$filax[0];
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]+=$filax[2];					
						if(empty($GrxEnt[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]))
						{
							$GrxEnt[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]=1;
							$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][7]++;
							
						}
						//if($fila[3]=='50322'){$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]=1660000;}			
						//if($fila[3]=='50323'&&$filax[0]=="01"){$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]=1820000;}	
						//$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][5]+=round($MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]);
						$Subtot=$Subtot+(round($filax[2])*round($filax[3]));
								
					}	
					else
					{
						$cc++;
						$MatGrupos[$filax[0]]=array($filax[0],$filax[0],1);
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]=$fila[0];
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][1]=$fila[1];		
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][2]=$fila[2];
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][3]=$filax[0];
						$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]+=$filax[2];	
						if(empty($GrxEnt[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]))
						{
							$GrxEnt[$fila[0]][$fila[1]][$fila[2]][$filax[0]][0]=1;
							$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][7]++;						
						}									
						//$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][5]+=round($MatFacturas[$fila[0]][$fila[1]][$fila[2]][$filax[0]][4]);	
						$Subtot=$Subtot+(round($filax[2])*round($filax[3]));		
					}
				//}
				//echo "Subtot=$Subtot = > vrunidad=$filax[3] cantidad=$filax[2]<br><br>";	
			}							
			$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][0]=$fila[0];
			$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][1]=$fila[1];		
			$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][2]=$fila[2];		
			$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][3]+=round($fila[5]);
			$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][4]+=round($fila[6]);
			$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][5]+=$fila[4]-round($fila[5]);
			$MatFacturasTot[$fila[0]][$fila[1]][$fila[2]][6]=$rowsp;
		}
		
	//}			
if(!$PDF)
{
?>
<head></head>
<body background="/Imgs/Fondo.jpg">
<?
}
$cont=0; $tab=0; $AmbitoAnt="";
if($MatFacturas)
{
	if(!$PDF)
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
    Consolidado por Servicios Prestados<br>    
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
	<table border='1' bordercolor='#e5e5e5' style='font : normal normal small-caps 11px Tahoma;' align="center" >
    <?
	}
	$cont=0;$ID=0;	
	foreach($MatRegimen as $Reg)
	{
		?><tr><td bgcolor='#e5e5e5' style='font-weight:bold'  align="center" colspan="3"><? echo "Regimen: $Reg"?></td></tr><?
		if($MatTipoAsegurador[$Reg])
		{			
			foreach($MatTipoAsegurador[$Reg] as $Entidad)
			{
				if($MatFacturasTot[$Entidad[1]])
				{					
					foreach($MatFacturasTot[$Entidad[1]] as $Contrato)
					{
						foreach($Contrato as $NoContrato)
						{
							if($NoContrato[3])
							{$rowp=$NoContrato[7]+2;}else{$rowp=$NoContrato[7]+1;}
							?>
                            <tr style='font-weight:bold'><td id="<? echo $NoContrato[0].$NoContrato[1].$NoContrato[2]?>"  rowspan="<? echo $rowp?>"><? echo $MatEntidades[$NoContrato[0]][$NoContrato[1]][$NoContrato[2]][4]?></td></tr>                            
							<?														
							foreach($MatGrupos as $Grup)
							{
								if($MatFacturas[$NoContrato[0]][$NoContrato[1]][$NoContrato[2]][$Grup[0]])
								{?>
									<tr onMouseOver="this.bgColor='#AAD4FF';document.getElementById('<? echo $NoContrato[0].$NoContrato[1].$NoContrato[2]?>').bgColor='#AAD4FF';" onMouseOut="this.bgColor='';document.getElementById('<? echo $NoContrato[0].$NoContrato[1].$NoContrato[2]?>').bgColor='';"><td ><? echo $Grup[1]?></td>
                                    <td align="right"><? echo number_format($MatFacturas[$NoContrato[0]][$NoContrato[1]][$NoContrato[2]][$Grup[0]][4],0);?></td>
                                    </tr>                            
								<?	
								}
							}
							if($NoContrato[3])
							{
								?>
								<tr onMouseOver="this.bgColor='#AAD4FF';document.getElementById('<? echo $NoContrato[0].$NoContrato[1].$NoContrato[2]?>').bgColor='#AAD4FF';" onMouseOut="this.bgColor='';document.getElementById('<? echo $NoContrato[0].$NoContrato[1].$NoContrato[2]?>').bgColor='';"><td >Copagos</td>
                                <td align="right"><? echo number_format($NoContrato[3],0);?></td>
                                </tr>
                                <?
							}
							$Total+=round($NoContrato[5]);
							?>                            
							<tr bgcolor='#e5e5e5' style='font-weight:bold' align="right" >
                            <td colspan="2">Subtotal x Entidad</td>
                            <td><? echo number_format($NoContrato[5],0);?></td>
                            </tr>
							<?								
						}	
					}					
				}
			}	
		}	
	}
	?>
    <tr><td colspan="3">&nbsp;</td></tr>
	<tr bgcolor='#e5e5e5' style='font-weight:bold' align="right">
    <td colspan="2">Gran Total</td>
    <td><? echo number_format($Total,0);?></td>
    </tr>
	<?
	/*
	foreach($MatTodasEnt as $Ent)
	{
		if($MatFacturasTot[$Ent])
		{
						
			foreach($MatFacturasTot[$Ent] as $Contrato)
			{
				foreach($Contrato as $NoContrato)
				{								
					?>                       
                    <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
					<td style='font-weight:bold'><? echo utf8_decode_seguro($MatEntidades[$NoContrato[0]][$NoContrato[1]][$NoContrato[2]][4]);?></td>	
					<?
					foreach($MatGrupos as $Grup)
					{
						if($Grup[2])
						{
							?><td align="right"><? echo number_format($MatFacturas[$NoContrato[0]][$NoContrato[1]][$NoContrato[2]][$Grup[0]][4],0);?></td><?
							$MatGrupos[$Grup[0]][3]+=round($MatFacturas[$NoContrato[0]][$NoContrato[1]][$NoContrato[2]][$Grup[0]][4]);
						}
					}
					$TotCop+=round($NoContrato[3]);
					$TotDes+=round($NoContrato[4]);
					$Total+=round($NoContrato[5]);
					?>
					<td align="right"><? echo number_format($NoContrato[3],0);?></td>
					<td align="right"><? echo number_format($NoContrato[4],0);?></td>
					<td align="right" style='font-weight:bold'><? echo number_format($NoContrato[5],0);?></td>                        
                 	</tr>
					<?
				}	
			}	
		}	
	}*/
	?>
	</table>
    <?
	if(!$PDF)
	{
		
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

				$ruta=$_SERVER['DOCUMENT_ROOT'];
				$this->Image("$ruta/Firmas/12990830.PNG",90,223,40,15);			
				$this->Ln(20);
				$this->SetX(80);	
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
				$this->Cell(0,8,"$Compania[2] $Compania[3]",0,0,'C');
				$this->Ln(10);								
				$this->SetFont('Arial','B',11);
				$this->Cell(0,8,"ENTIDAD RESPONSABLE DEL PAGO: ".strtoupper(utf8_decode_Seguro($NomEntidadPaga)),0,0,'C'); 				
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