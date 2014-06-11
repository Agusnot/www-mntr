<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	ini_set("memory_limit","512M");
	include("Informes.php");	
	require('LibPDF/fpdf.php');
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
	while($fila=ExFetch($res))
	{
		$MatGrupos[$fila[0]]=array($fila[0],$fila[1]);
	}
	$cons="Select Entidad,Contrato,NoContrato,nofactura,Subtotal, copago, descuento,total from Facturacion.FacturasCredito where
	Compania='$Compania[0]' and Estado='AC'	and FechaCrea>='$Anio-$Mes-01 00:00:00' and FechaCrea<='$Anio-$Mes-$DiaFFin 23:59:59' 
	order by Entidad,Ambito,Contrato,NoContrato,NoFactura";
	//echo $cons;exit;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if(substr($fila[1],0,8)=="Hospital"||substr($fila[1],0,8)=="Rehabili")
		{
			$fila[1]="Hospitalizacion";	
		}		
		$consx="select grupo,tipo,sum(cantidad),vrunidad,almacenppal,codigo,nombre,generico,presentacion
		from facturacion.detallefactura where compania='$Compania[0]' and nofactura=$fila[3] and vrunidad!=0
		group by grupo,tipo,vrunidad,almacenppal,codigo,nombre,generico,presentacion order by grupo";	
		//echo $consx."<br><br>";
		$resx=ExQuery($consx);
		$SubtotPsico=0;$SubtotPsiqu=0;$SubtotEst=0;$SubtotFarm=0;$SubtotLab=0;$SubtotUrg=0;$TotF=0;
		while($filax=ExFetch($resx))
		{
			if($fila[0]=='800198972-6'||$fila[0]=='890399029-5'||$fila[0]=='891580016-8'||$fila[0]=='I891280001-0'||$fila[0]=='800103913-4')
			{
				if($filax[0]!="Medicamentos"&&$filax[0]!="Dispositivos medicos")
				{
					if($filax[1]=="00004")
					{
						if($filax[5]=="890202"||$filax[5]=="890302")
						{
							$SubtotPsiqu=$SubtotPsiqu+(round($filax[2])*round($filax[3]));
							if($SubtotPsiqu>0){$MatSerPrestados[1]="Consulta Externa Psiquiatra";}													
						}
						elseif($filax[5]=="890208"||$filax[5]=="890308")
						{
							$SubtotPsico=$SubtotPsico+(round($filax[2])*round($filax[3]));
							if($SubtotPsico>0){$MatSerPrestados[0]="Consulta Externa Psicologo";}
						}
						elseif($filax[5]=="890702")
						{
							$SubtotUrg=$SubtotUrg+(round($filax[2])*round($filax[3]));
							if($SubtotUrg>0){$MatSerPrestados[5]="Urgencias";}													
						}
					}
					elseif($filax[1]=="00001")
					{
						$SubtotEst=$SubtotEst+(round($filax[2])*round($filax[3]));
						if($SubtotEst>0){$MatSerPrestados[2]="Estancia";}										
					}
					elseif($filax[1]=="Medicamentos")
					{
						$SubtotFarm=$SubtotFarm+(round($filax[2])*round($filax[3]));
						if($SubtotFarm>0){$MatSerPrestados[3]="Farmacia";}			
					}
					elseif($filax[1]=="00005")
					{
						$SubtotLab=$SubtotLab+(round($filax[2])*round($filax[3]));
						if($SubtotLab>0){$MatSerPrestados[4]="Laboratorios";}			
					}
				}								
			}
			else
			{
				if($filax[1]=="00004")
				{					
					$SubtotPsiqu=$SubtotPsiqu+(round($filax[2])*round($filax[3]));
					if($SubtotPsiqu>0){$MatSerPrestados[1]="Consulta Externa Psiquiatra";}																		
					/*if($filax[5]=="890202"||$filax[5]=="890302")
					{
						$SubtotPsiqu=$SubtotPsiqu+(round($filax[2])*round($filax[3]));
						if($SubtotPsiqu>0){$MatSerPrestados[1]="Consulta Externa Psiquiatra";}													
					}
					elseif($filax[5]=="890208"||$filax[5]=="890308"||$filax[5]=="943102")
					{
						$SubtotPsico=$SubtotPsico+(round($filax[2])*round($filax[3]));
						if($SubtotPsico>0){$MatSerPrestados[0]="Consulta Externa Psicologo";}
					}
					elseif($filax[5]=="890702")
					{
						$SubtotUrg=$SubtotUrg+(round($filax[2])*round($filax[3]));
						if($SubtotUrg>0){$MatSerPrestados[5]="Urgencias";}													
					}*/
				}
				elseif($filax[1]=="00001")
				{
					$SubtotEst=$SubtotEst+(round($filax[2])*round($filax[3]));
					if($SubtotEst>0){$MatSerPrestados[2]="Estancia";}										
				}
				elseif($filax[1]=="Medicamentos")
				{
					$SubtotFarm=$SubtotFarm+(round($filax[2])*round($filax[3]));
					if($SubtotFarm>0){$MatSerPrestados[3]="Farmacia";}			
				}
				elseif($filax[1]=="00005")
				{
					$SubtotLab=$SubtotLab+(round($filax[2])*round($filax[3]));
					if($SubtotLab>0){$MatSerPrestados[4]="Laboratorios";}			
				}			
			}
			//echo "Subtot=$Subtot = > vrunidad=$filax[3] cantidad=$filax[2]<br><br>";
		}		
		$SubtotPsico=round($SubtotPsico);
		$SubtotPsiqu=round($SubtotPsiqu);
		$SubtotEst=round($SubtotEst);
		$SubtotFarm=round($SubtotFarm);
		$SubtotLab=round($SubtotLab);
		$SubtotUrg=round($SubtotUrg);	
		$TotF=($SubtotPsico+$SubtotPsiqu+$SubtotEst+$SubtotFarm+$SubtotLab+$SubtotUrg)-round($fila[5]);$TotF=round($TotF);
		echo "$fila[0] --> $fila[3] --> $SubtotPsico --> $SubtotPsiqu --> $SubtotEst --> $SubtotFarm --> $SubtotLab --> $SubtotUrg --> $TotF<br>";		
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][0]=$fila[0];
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][1]=$fila[1];
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][2]=$fila[2];
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][3]+=$SubtotPsico;
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][4]+=$SubtotPsiqu;
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][5]+=$SubtotEst;
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][6]+=$SubtotFarm;
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][7]+=$SubtotLab;
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][8]+=$SubtotUrg;
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][9]+=round($fila[5]);
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][10]+=round($fila[6]);
		$MatFacturas[$fila[0]][$fila[1]][$fila[2]][11]+=$TotF;
		//$MatFacturas[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[4]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$SubTot,$fila[6],$fila[7],$TotF);	
	}			
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
    Consolidado Cruzado por Servicios<br>    
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
	<table border='1' bordercolor='#e5e5e5' style='font : normal normal small-caps 10px Tahoma;' width='100%'>
    <tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'>
    <td>Entidades/Servicios</td>
    <?
	$colsp=4;
    for($i=0;$i<6;$i++)
	{
		if($MatSerPrestados[$i])
		{
			$colsp++;			
		?>
		<td align="center"><? echo $MatSerPrestados[$i]?></td>	
		<?
		}
    }?>
    <td>Copagos</td><td>Descto</td><td>Vr Total</td>
    </tr>
    <?
	}
	$cont=0;$ID=0;
	$TotPsico=0;$TotPsiqui=0;$TotEstancia=0;$TotFarmacia=0;$TotLabs=0;$TotUrgencias=0;$TotDesc=0;$TotCopagos=0;$Total=0;
	foreach($MatTodasEnt as $Ent)
	{
		if($MatFacturas[$Ent])
		{
			foreach($MatFacturas[$Ent] as $Contrato)
			{
				foreach($Contrato as $NoContrato)
				{					
					?>
                    <tr>
                    <td><? echo utf8_decode_seguro($MatEntidades[$NoContrato[0]][$NoContrato[1]][$NoContrato[2]][4]);?></td>	
                    <?
					if($MatSerPrestados[0]){?><td align="right"><? echo number_format($NoContrato[3],0);?></td><? $TotPsico+=$NoContrato[3];}					
					if($MatSerPrestados[1]){?><td align="right"><? echo number_format($NoContrato[4],0);?></td><? $TotPsiqui+=$NoContrato[4];}					
					if($MatSerPrestados[2]){?><td align="right"><? echo number_format($NoContrato[5],0);?></td><? $TotEstancia+=$NoContrato[5];}					
					if($MatSerPrestados[3]){?><td align="right"><? echo number_format($NoContrato[6],0);?></td><? $TotFarmacia+=$NoContrato[6];}					
					if($MatSerPrestados[4]){?><td align="right"><? echo number_format($NoContrato[7],0);?></td><? $TotTotLabs+=$NoContrato[7];}					
					if($MatSerPrestados[5]){?><td align="right"><? echo number_format($NoContrato[8],0);?></td><? $TotUrgencias+=$NoContrato[8];}
					$TotCopagosc+=$NoContrato[9];
					$TotDesc+=$NoContrato[10];
					$Total+=$NoContrato[11];
					?>
                    <td align="right"><? echo number_format($NoContrato[9],0);?></td>
                    <td align="right"><? echo number_format($NoContrato[10],0);?></td>
                    <td align="right"><? echo number_format($NoContrato[11],0);?></td>
                    </tr>
                    <?
				}	
			}	
		}	
	}
	?>
    <tr><td colspan="<? echo $colsp?>"></td></tr> 
    <tr bgcolor='#e5e5e5' style='font-weight:bold'  align="right">
    <td>Totales</td>
    <?
    if($MatSerPrestados[0]){?><td align="right"><? echo number_format($TotPsico,0);?></td><? }					
	if($MatSerPrestados[1]){?><td align="right"><? echo number_format($TotPsiqui,0);?></td><? }					
	if($MatSerPrestados[2]){?><td align="right"><? echo number_format($TotEstancia,0);?></td><? }					
	if($MatSerPrestados[3]){?><td align="right"><? echo number_format($TotFarmacia,0);?></td><? }					
	if($MatSerPrestados[4]){?><td align="right"><? echo number_format($TotTotLabs,0);?></td><? }					
	if($MatSerPrestados[5]){?><td align="right"><? echo number_format($TotUrgencias,0);?></td><? }
	?>
	<td align="right"><? echo number_format($TotCopagos,0);?></td>
	<td align="right"><? echo number_format($TotDesc,0);?></td>
	<td align="right"><? echo number_format($Total,0);?></td>	
    </tr>  
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