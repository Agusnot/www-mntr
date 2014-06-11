<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	//require('LibPDF/fpdf.php');	
	require('LibPDF/rotation.php');
	$ND=getdate();
	
	$cons="select grupo,grupofact from consumo.grupos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GruposMeds[$fila[0]]=$fila[1];
	}
	
	$cons="select grupo,almacenppal from consumo.grupos where compania='$Compania[0]' and anio='$ND[year]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$GrupsMeds[$fila[0]]=array($fila[0],$fila[1]);
	}	
	$cons="select grupo,codigo from contratacionsalud.gruposservicio where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$GrupsCUPs[$fila[1]]=array($fila[0],$fila[1]);
	}
	$cons2="select nota,codigo from facturacion.notaspiepag where compania='$Compania[0]' order by codigo";
	$res2=ExQuery($cons2);
	$Incre=0;
	while($fila2=ExFetch($res2)){
		$Notas[$fila2[1]]=$fila2[0];
		$Incre=$Incre+3;
		//echo $Notas[$fila2[1]]."<br>";
	}
	if($NoFacFin==''){	
		$FacIni=$NoFac;
		$FacFin=$NoFac;
	}
	else{
		$FacIni=$NoFac;
		$FacFin=$NoFacFin; //echo $NoFacFin;
	}
	
	for($i=$FacIni;$i<=$FacFin;$i++){	
		//echo "<br>$i";		
		$NoFac=$i;	
		
		$cons="select entidad,(primnom  || segnom || primape || segape) as eps,facturascredito.contrato,facturascredito.nocontrato,ambito,subtotal,facturascredito.copago,descuento,total
		,individual,direccion,telefono,tipoasegurador,codigosgsss,nofactura,fechaini,fechafin,fechacrea
		from facturacion.facturascredito,central.terceros where facturascredito.compania='$Compania[0]' and terceros.compania='$Compania[0]' and nofactura=$NoFac and 
		entidad=identificacion";
		//echo $cons."<br>";
		$res=ExQuery($cons); 
		$fila=ExFetch($res);
		$consFormat="select formato from facturacion.facturascredito,contratacionsalud.contratos 
		where facturascredito.compania='$Compania[0]' and contratos.compania='$Compania[0]' and 	
		nofactura=$NoFac and contratos.entidad=facturascredito.entidad and facturascredito.contrato=contratos.contrato and facturascredito.nocontrato=contratos.numero";
		$resFormat=ExQuery($consFormat);
		$filaFormat=ExFetch($resFormat);
		if(!$Formato){$Formato=$filaFormat[0];}
		if($filaFormat[0]==$Formato){
			
			$DatosFac[$NoFac]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16],$fila[17]);			
			if($fila[9]==1){				
				$cons2="select (primape || ' ' || segape  || ' ' || primnom || ' ' || segnom   ) as nompac,cedula,liquidacion.nocarnet,liquidacion.tipousu,liquidacion.nivelusu,autorizac1
				,autorizac2,autorizac3,numservicio,noliquidacion,municipio,motivonocopago from facturacion.liquidacion,central.terceros where terceros.compania='$Compania[0]' 
				and liquidacion.compania='$Compania[0]' and nofactura=$fila[14] and identificacion=cedula ";	
				//echo $fila[14];
				//echo $cons2."<br><br>";
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
				
				$consCopoCuota="select porsentajecopago from facturacion.liquidacion where compania='$Compania[0]' and nofactura=$NoFac";				
				$resCopoCuota=ExQuery($consCopoCuota);
				$filaCopoCuota=ExFetch($resCopoCuota);				
				$DatosLiq[$NoFac]=array($fila2[0],$fila2[1],$fila2[2],$fila2[3],$fila2[4],$fila2[5],$fila2[6],$fila2[7],$fila2[8],$fila2[9],$filaCopoCuota[0],$fila2[10],$fila2[11]);				
			}	
			else{
				$NoEsInd=1;
				$consFechasfac="select fechacrea,fechaini,fechafin from facturacion.facturascredito
				where facturascredito.compania='$Compania[0]' and 	nofactura=$NoFac";
				$resFechasfac=ExQuery($consFechasfac); $Fechasfac=ExFetch($resFechasfac);
			}
			
			$cons2="select grupo,tipo,sum(cantidad),vrunidad,almacenppal,codigo,nombre,generico,presentacion,sum(vrtotal),forma
			from facturacion.detallefactura where compania='$Compania[0]' and nofactura=$fila[14] 
			group by grupo,tipo,vrunidad,almacenppal,codigo,nombre,generico,presentacion,forma order by grupo";
			$res2=ExQuery($cons2);
			//echo $cons2."<br>";
			while($fila2=ExFetch($res2)){
				
				$Id="$fila[14]$fila2[5]";
				
				if($GruposMeds[$fila2[0]]){$fila2[0]=$GruposMeds[$fila2[0]];} 
				
				if($fila2[0]==$GrupsCUPs[$fila2[0]][1]){$fila2[0]=$GrupsCUPs[$fila2[0]][0];}
				$DetalleFac[$Id][$NoFac]=array($fila2[0],$fila2[1],$fila2[2],$fila2[3],$fila2[4],$fila[14],$fila2[5],$fila2[6],$fila2[7],$fila2[8],$fila2[9],$fila2[10]);
				//echo $DetalleFac[$Id][$NoFac][5]." ".$DetalleFac[$Id][$NoFac][6]." ".$DetalleFac[$Id][$NoFac][7]."<br>";
				//$Datos[$Nofac]=(grupo,tipo,cantidad,vrunidad,almacenppal,nofac,codigo,nombre,generico,presentacion,vrtotal,forma)
			}	
		}
	}	
	
	/*foreach($DatosLiq as $NF)
	{
		echo "$NF[0] $NF[1] $NF[2] $NF[3] $NF[4] $NF[5] $NF[6] $NF[7] $NF[8] $NF[9] <br><br>";
	}*/
	//exit;
class PDF extends PDF_Rotate
{
	function Header1($NoFac,$Ini,$Entidad)
	{
		global $Compania; global $Impresion;
		
		$cons="Select NomRespPago from ContratacionSalud.Contratos where Compania='$Compania[0]' and Entidad='$Entidad[0]' and Contrato='$Entidad[2]' and Numero='$Entidad[3]'";
		$res=ExQuery($cons);$fila=ExFetch($res);
		if($fila[0]){$NombreMuestraEntidad=$fila[0];}else{$NombreMuestraEntidad=$Entidad[1];}
		$this->AddPage();				
		$Raiz = $_SERVER['DOCUMENT_ROOT'];
		$this->Image("$Raiz/Imgs/Logo.jpg",10,7,20,20);
		$this->SetFont('Arial','B',12);				
		$this->Cell(25,5,"",0,0,'L');				
		$this->Cell(140,5,utf8_decode(substr(strtoupper($Compania[0]),0,50)),0,0,'C');		
		$this->SetFont('Arial','',14);				
		$this->SetFillColor(228,228,228);				
		
		$Tamano=$this->GetStringWidth(" No. $NoFac ");		
		if($this->GetStringWidth(" $NoFac ")<$this->GetStringWidth(" DE VENTA ")){
			$Tamano=$this->GetStringWidth(" DE VENTA ");
		}
		
		$this->Cell($Tamano+1,5,"FACTURA","LRT",0,'C',1);
		$this->SetFont('Arial','',8);
		$this->Ln(4);		
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(140,5,strtoupper($Compania[1]),0,0,'C');
		$this->SetFillColor(228,228,228);	
		$this->SetFont('Arial','',14);
		$this->Cell($Tamano+1,5,"DE VENTA","LRB",0,'C',1);
		//$this->Cell($Tamano+1,10," 8589541 ","LRB",0,'C');
		$this->SetFont('Arial','',8);
		$this->Ln(4);
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(140,5,"CODIGO SGSSS ".strtoupper($Compania[17]),0,0,'C');	
		$this->SetFont('Arial','B',16);
		$this->Cell($Tamano+1,10," $NoFac ","LRB",0,'C');
		$this->Ln(4);
		$this->SetFont('Arial','',8);
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(140,5,$Compania[2]." - TELEFONOS: ".strtoupper($Compania[3]),0,0,'C');	
		$this->Ln(6);
		$this->Cell(165,5,"",0,0,'L');
		$this->SetFont('Arial','',14);
		if($Impresion){$this->Cell($Tamano+1,5,$Impresion,"LRB",0,'C',0);}			
		$this->Ln(5);		
		$this->SetFont('Arial','B',10);	
		$this->Cell(194,5,"ENTIDAD RESPONSABLE DE PAGO: ".utf8_decode_seguro(strtoupper($NombreMuestraEntidad)),0,0,'C');		
		$this->Ln();
		$this->SetFont('Arial','',8);
		if($Entidad[0]=="D891280001-0-NaN"||$Entidad[0]=="I891280001-0"){$Entt="891280001-0";
;}else{$Entt=$Entidad[0];}
		$this->Cell(194,5,"NIT $Entt",0,0,'C');
		if($Ini==1){
			$this->Ln(12);
		}
		else{
			$this->Ln(12);
		}
	}
	function Header2($Cliente,$Paciente,$Fac,$Liq)
	{
		//$this->AddPage();			
		global $DatosLiq; global $Compania; global $NoEsInd; global $Fechasfac;
		$FechaPeriodoIni=$Fac[15]; $FechaPeriodoFin=$Fac[16];		
		
		if(!$Liq[$Fac[14]][8]){$Liq[$Fac[14]][8]="-1";$Cliente[9]=0;}
		$cons="select fechaini,fechafin from salud.pagadorxservicios where Compania='$Compania[0]' and numservicio=".$Liq[$Fac[14]][8]."
		and entidad='$Fac[0]' and contrato='$Fac[2]' and nocontrato='$Fac[3]'";
		//echo "$cons<br><br>";
		$res=ExQuery($cons);$fila=ExFetch($res);		
		$FechaIngreso=$fila[0];$FechaEgreso=$fila[1];
		//echo "Fecha Ing : $FechaIngreso  --> Fecha Egr: $FechaEgreso";
		if($FechaIngreso>=$FechaPeriodoIni){$Fac[15]=$FechaIngreso;}
		if($FechaIngreso<$FechaPeriodoIni){$Fac[15]=$FechaPeriodoIni;}		
		if($FechaEgreso<$FechaPeriodoFin){$Fac[16]=$FechaEgreso;}
		//if(!$Fac[15]){$Fac[15]=$FechaPeriodoIni;}		
		if(!$Fac[16]){$Fac[16]=$FechaPeriodoFin;}		
		//if($FechaIngreso<$Fac[15]){$Fac;}		
		//$Fac[15]="2011-03-19";$Fac[16]="2011-04-18"; //cambiar fechas periodo de facturas manual
		$Ambito=$Fac[4];
		if($Ambito=="Consulta Externa")
		{
			if($Fac[15]==$Fac[16]){$PeriodoCuenta=$Fac[15];}else{$PeriodoCuenta="$Fac[15] a $Fac[16]";}
		}
		else
		{
			$PeriodoCuenta="$Fac[15] a $Fac[16]";	
		}
		if(!$Liq[$Fac[14]][8])
		{
			$Liq[$Fac[14]][8]="-1";
			$Cliente[9]=0;
		}
		$cons="Select Detalle from salud.ordenesmedicas where Compania='$Compania[0]' and Cedula='$Paciente[1]' and NumServicio=".$Liq[$Fac[14]][8]." and TipoOrden='Orden Egreso'";
		//echo "$cons<br><br>";
		if(ExError()){echo "<font color='#BB0000'>Existe inconvenientes con la Factura Numero ".$Fac[14]."</font><br><br>";}
		$res=ExQuery($cons);$fila=ExFetch($res);		
		$AuxCusaS=explode(":",$fila[0]);
		$AuxCS=explode("-",$AuxCusaS[1]);
		$CausaSalida=$AuxCS[0];
		$Procedencia=$Liq[$Fac[14]][11];
		//DATOS PACIENTE
		if($Cliente[9]==1){						
						
			$this->Cell(10,5,"",0,0,'L');
			$this->SetFont('Arial','B',8);	
			$this->Cell(33,5,"PERIODO DE CUENTA:",0,0,'L');
			$this->SetFont('Arial','',8);
			$this->Cell(86,5,$PeriodoCuenta,0,0,'L');			
			
			$this->Ln(4);
			$this->Cell(10,5,"",0,0,'L');	
			$this->SetFont('Arial','B',8);				
			$this->Cell(33,5,"FECHA EXPEDICION:",0,0,'L');		
			$this->SetFont('Arial','',8);
			if($Ambito=="Consulta Externa")
			{
				$this->Cell(86,5,substr($Fac[17],0,10),0,0,'L');
			}
			else
			{
				$this->Cell(86,5,substr($Fac[17],0,10),0,0,'L');///
			}
			$this->SetFont('Arial','B',8);	
			$this->Cell(33,5,"FECHA VENCIMIENTO:",0,0,'L');
			$this->SetFont('Arial','',8);
			$AnioE=substr($Fac[17],0,4);
			$MesE=substr($Fac[17],5,2);
			$DiaE=substr($Fac[17],8,2);
			//$AnioE=2011;$MesE=12;$DiaE=2;
			$UltDia=UltimoDia($AnioE,$MesE);
			$Dias=$UltDia-$DiaE;
			if($Dias<30)
			{
				$MesE++;
				if($MesE>12){$AnioE++;$MesE=1;}				
				$UltDia=UltimoDia($AnioE,$MesE);
				$DiaE=30-$Dias;
			}
			else
			{
				$DiaE=$UltDia;	
			}
			if($MesE<10){"0".$MesE;}
			//echo "$AnioE $MesE $DiaE $UltDia";
			//Ojo la fecha de vencimiento no es esaaaaaa
			$this->Cell(32,5,"$AnioE-$MesE-$DiaE",0,0,'L');
			//$this->Cell(32,5,"2011-05-30",0,0,'L');
			
		}
		else{
			if($NoEsInd){
				//echo "Fechasfac $Fechasfac[0] $Fechasfac[1] $Fechasfac[2]";
				$this->Cell(10,5,"",0,0,'L');
				$this->SetFont('Arial','B',8);				
				$this->Cell(33,5,"FECHA EXPEDICION:",0,0,'L');		
				$this->SetFont('Arial','',8);
				$this->Cell(86,5,substr($Fac[17],0,10),0,0,'L');
				$this->SetFont('Arial','B',8);				
				$this->Cell(33,5,"FECHA VENCIMIENTO:",0,0,'L');
				$this->SetFont('Arial','',8);
				$AnioE=substr($Fac[17],0,4);
				$MesE=substr($Fac[17],5,2);
				$DiaE=substr($Fac[17],8,2);
				//$AnioE=2011;$MesE=12;$DiaE=2;
				$UltDia=UltimoDia($AnioE,$MesE);
				$Dias=$UltDia-$DiaE;
				if($Dias<30)
				{
					$MesE++;
					if($MesE>12){$AnioE++;$MesE=1;}				
					$UltDia=UltimoDia($AnioE,$MesE);
					$DiaE=30-$Dias;
				}
				else
				{
					$DiaE=$UltDia;	
				}
				if($MesE<10){"0".$MesE;}
				//echo "$AnioE $MesE $DiaE $UltDia";
				//Ojo la fecha de vencimiento no es esaaaaaa
				if($MesE<10){$C1="0";} if($DiaE<10){$C2="0";}
				$this->Cell(32,5,"$AnioE-$C1$MesE-$C2$DiaE",0,0,'L');
				$this->Ln(4);
				$this->Cell(10,5,"",0,0,'L');
				$this->SetFont('Arial','B',8);	
				$this->Cell(33,5,"PERIODO DE CUENTA:",0,0,'L');
				$this->SetFont('Arial','',8);
				$PeriodoCuenta="$Fechasfac[1] - $Fechasfac[2]";
				$this->Cell(86,5,$PeriodoCuenta,0,0,'L');		
			}
		}
	}
	function Titulos($Ini){
		//Titulos
		if($Ini==1){
			$this->Ln(12);		
		}
		$this->SetFillColor(228,228,228);
		$this->SetFont('Arial','B',8);			
		$this->Cell(143,5,"DESCRIPCION",1,0,'C',1);
		$this->Cell(11,5,"CANT",1,0,'C',1);
		$this->Cell(20,5,"VR UNIDAD",1,0,'C',1);
		$this->Cell(20,5,"VR TOTAL",1,0,'C',1);
		$this->SetFont('Arial','',8);
	}
	function Datos($NoFac,$Entidad)
	{		
		global $DetalleFac; global $GrupsMeds; global $GrupsCUPs; global $ND; global $Compania; global $DatosFac; global $Incre; global $Estado; global $DatosLiq;
		$Ambito=$DatosFac[$NoFac][4];
		$Ent=$Entidad[0];
		$this->Titulos(1);
		$NumLineas=0;	
		
		//CUPS
		foreach($GrupsCUPs as $GC)
		{
			$ban=0;			
			$SubTotGrup=0;			
			foreach($DetalleFac as $CUPs){
				//echo $CUPS[$NoFac][0]."<br>";
				if($CUPs[$NoFac][0]==$GC[0]){
					$SubTot=0;	
				//$Datos[$Nofac]=(grupo,tipo,cantidad,vrunidad,almacenppal,nofac,codigo,nombre,generico,presentacion,vrtotal)
					//echo $CUPS[$NoFac][5]."=".$CUPS[$NoFac][6]."-".$CUPS[$NoFac][7]."<br>";
					$POSY=$this->GetY();
					if($POSY>=250 && $POSY<255){
						$this->Header1($NoLiquidacion,0,$Entidad);
						$this->Titulos(0);
					}
					$ban=1;
					$this->Ln(5);				
					$this->SetFont('Arial','',8);
					$this->Cell(20,5,substr($CUPs[$NoFac][6],0,12),"LR",0,'C');
					$this->SetFont('Arial','',7);
					$this->Cell(123,5,strtoupper(substr($CUPs[$NoFac][7]." ".$CUPs[$NoFac][9]." ".$CUPs[$NoFac][11],0,80)),"LR",0,'L');					
					$this->SetFont('Arial','',8);
					$this->Cell(11,5,substr(round($CUPs[$NoFac][2],0),0,12),"LR",0,'R');
					$this->Cell(20,5,number_format(round($CUPs[$NoFac][3],0),2),"LR",0,'R');
					//$this->Cell(29,5,substr("968588493493499349934141234134",0,17),"LRTB",0,'R');
					$this->Cell(20,5,number_format(round($CUPs[$NoFac][10],0),2),"LR",0,'R');
					$SubTot=round($SuTot,0)+round($CUPs[$NoFac][10],0);
					$SubTot=round($SubTot,0);
					//echo $SubTot." <br>";
					$SubTotGrup=round($SubTotGrup)+round($SubTot,0);
					$GranSubTot=round($SubTot,0)+round($GranSubTot,0);	
					$GranSubTot=round($GranSubTot,0);
					$POSY=$this->GetY();
					if($POSY>=250 && $POSY<255){						
						$this->Ln(5);										
						$this->Cell(194,1,"","T",0,'L');
						$this->Header1($NoFac,0,$Entidad);
						$this->Titulos(0);
					}
				}
			}
			if($ban==1){
				$POSY=$this->GetY();
					if($POSY>=250 && $POSY<255){						
						$this->Header1($NoFac,0,$Entidad);
						$this->Titulos(0);
					}
				$this->Ln(5);
				$this->SetFillColor(240,240,240);	
				$this->SetFont('Arial','B',8);	
				$this->Cell(174,5,strtoupper($GC[0]),1,0,'R',1);
				$this->Cell(20,5,number_format(round($SubTotGrup,0),2),1,0,'R',1);
				$POSY=$this->GetY();
					if($POSY>=250 && $POSY<255){						
						$this->Header1($NoFac,0,$Entidad);
						$this->Titulos(0);
					}
			}
		}
					
		//Medicamentos
/*
		foreach($GrupsMeds as $GM)
		{
			$ban=0;
			$SubTotGrup=0;			
			foreach($DetalleFac as $Meds)
			{				
				
				//echo $Meds[$NoFac][0]." --> ".$GM[0]." --> ".$Meds[$NoFac][1]." --> Medicamentos "." ".$Meds[$NoFac][4]." --> ".$GM[1]."<br>";
				if($Meds[$NoFac][0]==$GM[0]&&$Meds[$NoFac][1]=="Medicamentos"&&$Meds[$NoFac][4]==$GM[1])
				{
					//echo $Ent." ";
					$SubTot=0;
					$ban=1;
					$this->SetFont('Arial','',8);
					$this->Ln(5);					
					$this->Cell(20,5,substr($Meds[$NoFac][6],0,12),"LR",0,'C');					
					$this->SetFont('Arial','',7);
					if(!$Meds[$NoFac][8]){$Meds[$NoFac][8]=$Meds[$NoFac][7];}
					$this->Cell(123,5,strtoupper(substr($Meds[$NoFac][8]." ".$Meds[$NoFac][9],0,90)),"LR",0,'L');					
					$this->SetFont('Arial','',8);
					$this->Cell(11,5,substr(round($Meds[$NoFac][2],0),0,12),"LR",0,'R');
					$this->Cell(20,5,number_format(round($Meds[$NoFac][3],0),2),"LR",0,'R');								
					$this->Cell(20,5,number_format(round($Meds[$NoFac][10],0),2),"LR",0,'R');
					$SubTot+=round($Meds[$NoFac][10],0);					
					$SubTot=round($SubTot,0);
					$SubTotGrup=round($SubTotGrup,0)+round($SubTot,0);
					$GranSubTot=round($SubTot,0)+round($GranSubTot,0);	
					$GranSubTot=round($GranSubTot);
	//echo "SubTot = > ".round($Meds[$NoFac][2],0)."*".round($Meds[$NoFac][3],0)." = ".round($SubTot,0)." Entonces = >".round($GranSubTot)." SubTotGrup".round($SubTotGrup,0)."<br>";
					$POSY=$this->GetY();
					if($POSY>=250 && $POSY<255){		
						$this->Ln(5);										
						$this->Cell(194,1,"","T",0,'L');				
						$this->Header1($NoFac,0,$Entidad);
						$this->Titulos(0);						
					}	
				}
			}
			
			if($ban==1){
				$POSY=$this->GetY();
					if($POSY>=250 && $POSY<255){						
						$this->Header1($NoFac,0,$Entidad);
						$this->Titulos(0);
					}
				$this->Ln(5);
				$this->SetFillColor(240,240,240);	
				$this->SetFont('Arial','B',8);	
				$this->Cell(174,5,strtoupper($GM[0]),1,0,'R',1);
				$this->Cell(20,5,number_format(round($SubTotGrup,0),2),1,0,'R',1);
					$POSY=$this->GetY();
					if($POSY>=250 && $POSY<255){						
						$this->Header1($NoFac,0,$Entidad);
						$this->Titulos(0);
					}
			}
		}*/
		//echo $GranSubTot;
		//for($i=0;$i<21;$i++){	
		if($DatosFac[$NoFac][7]!=''&&$DatosFac[$NoFac][7]!="0"&&$DatosFac[$NoFac][5]!=''){
			$Limite=181.001259;
		}
		else{
			$Limite=186.001259;
		}
		$POSY=$this->GetY();	
		
		while($POSY<$Limite){			
			$this->Ln(5);
			$this->Cell(143,5,"","LR",0,'L');
			$this->Cell(11,5,"","LR",0,'L');
			$this->Cell(20,5,"","LR",0,'L');
			$this->Cell(20,5,"","LR",0,'L');
			$POSY=$this->GetY();
		}	
		

		//SUTOTALES,DESCUENTOS,COPAGO,TOTAL
		$Total=$GranSubTot;
		//$cons="update facturacion.facturascredito set subtotal=$Total where compania='$Compania[0]' and nofactura=$NoFac";
		//echo $cons;
		if($DatosFac[$NoFac][5]!=''){		
						
			$POSY=$this->GetY();
			if($POSY>=250-$Incre && $POSY<255-$Incre){	
				$this->Ln(5);										
				$this->Cell(194,1,"","T",0,'L');					
				$this->Header1($NoFac,0,$Entidad);		
			}
			
			
			$this->Ln(5);
			$this->SetFont('Arial','B',8);	
			$this->Cell(174,5,"SUBTOTAL GENERAL:",1,0,'R');
			$this->SetFont('Arial','',8);
            $this->Cell(20,5,number_format(round($Total,0),2),1,0,'R');			
			$AuxSubtotal=$Total;			
			
			if($DatosFac[$NoFac][7]!=''&&$DatosFac[$NoFac][7]!="0"){
				$POSY=$this->GetY();
				if($POSY>=250-$Incre && $POSY<255-$Incre){	
					$this->Ln(5);														
					$this->Header1($NoFac,0,$Entidad);					
				}
				$Total=$Total-$DatosFac[$NoFac][7];
				$this->Ln(5);				
				$this->SetFont('Arial','B',8);	
				$this->Cell(174,5,"DESCUENTO:",1,0,'R');
				$this->SetFont('Arial','',8);
				$this->Cell(20,5,number_format($DatosFac[$NoFac][7],2),1,0,'R');							
   			}
			
			$POSY=$this->GetY();
			if($POSY>=250-$Incre && $POSY<255-$Incre){	
				$this->Ln(5);										
				$this->Header1($NoFac,0,$Entidad);				
			}
			//if($DatosFac[$NoFac][6]!=''&&$DatosFac[$NoFac][6]!="0"){
			$Total=$Total-$DatosFac[$NoFac][6];
			$this->Ln(5);			
			$this->SetFont('Arial','B',7);	
			if($DatosLiq[$NoFac][12])
			{
				//echo $DatosLiq[$NoFac][12];
				$this->Cell(154,5,$DatosLiq[$NoFac][12],1,0,'L');
				$this->SetFont('Arial','B',8);
				$this->Cell(20,5,"CUOTA MOD:",1,0,'R');				
				$this->Cell(20,5,number_format($DatosFac[$NoFac][6],2),1,0,'R');
			}
			else
			{
				if($Ambito=="Consulta Externa"){
					$this->SetFont('Arial','B',8);
					$this->Cell(174,5,"CUOTA MODERADORA:",1,0,'R');
				}
				else{	
					$this->SetFont('Arial','B',8);
					$this->Cell(174,5,"COPAGO:",1,0,'R');
				}
				$this->SetFont('Arial','',8);
				$this->Cell(20,5,number_format($DatosFac[$NoFac][6],2),1,0,'R');	
			}
			//}	
			
			$POSY=$this->GetY();		
			if($POSY>=250-$Incre && $POSY<255-$Incre){	
				$this->Ln(5);														
				$this->Header1($NoFac,0,$Entidad);				
			}
			$this->Ln(5);			
			$this->SetFont('Arial','B',8);	
			//$this->MultiCell(194,3,strtoupper($NotTot),1,'J');
			$this->MultiCell(194,5,strtoupper("SON: ".NumerosxLet($Total)),1,'L');
			$this->Cell(174,5,"TOTAL:",1,0,'R');
			$this->SetFont('Arial','',8);
			$this->Cell(20,5,number_format(round($Total,0),2),1,0,'R');			
			$cons="update facturacion.facturascredito set subtotal=$AuxSubtotal,total=$Total where compania='$Compania[0]' and nofactura=$NoFac";			
			$res=Exquery($cons);
			$cons="update facturacion.liquidacion set subtotal=$AuxSubtotal,total=$Total where compania='$Compania[0]' and nofactura=$NoFac";			
			$res=Exquery($cons);
			if($Estado == "AN")
			{
				$this->SetFont('Arial','B',90);
				$this->SetTextColor(215,215,215);
				$this->Rotate(45,10,200);
				$this->Text(35,220,'ANULADO');
				$this->SetTextColor(0,0,0);
				$this->Rotate(0);
				$this->SetFont('Arial','',8);			
			}
			
			$POSY=$this->GetY();
			if($POSY>=250-$Incre && $POSY<255-$Incre){				
				$this->Header1($NoFac,0,$Entidad);				
			}	
			
			/*for($j=0;$j<5;$j++){
				$this->Ln(5);
				$POSY=$this->GetY();
				if($POSY>=250-$Incre && $POSY<255-$Incre){				
					$this->Header1($NoFac,0);	
					$Salto=(5-($i+1))*5;
					$this->Ln($Salto);			
				}
			}*/
			
			$POSY=$this->GetY();
			if($POSY>=225-$Incre && $POSY<255-$Incre){				
				$this->Header1($NoFac,0,$Entidad);					
				$this->Ln(25);
			}
			else{
				$this->Ln(30);
			}
			//$this->Cell(10,5,"",0,0,'D');
			//$this->Cell(80,5,"FIRMA ACEPTADO","T",0,'C');
			$ruta=$_SERVER['DOCUMENT_ROOT'];						
			$this->Cell(60,5,"",0,0,'D');
			$this->Cell(80,5,"FIRMA RESPONSABLE","T",0,'C');			
			$POSYFirma=$this->GetY();
			$this->Image("$ruta/Firmas/12990830.PNG",90,$POSYFirma-18,40,15);			
		}		
	}
	function BasicTable($DatosFac)
	{
		global $DatosLiq;
		foreach($DatosFac as $Facturas){
			$this->Header1($Facturas[14],1,$Facturas);					
			$this->Header2($DatosFac[$Facturas[14]],$DatosLiq[$Facturas[14]],$Facturas,$DatosLiq);
			$this->Datos($Facturas[14],$Facturas);			
		}
	}
	function Footer()
	{
		global $ND; global $Notas;
		$Salto=-15;
		if($Notas){
			$this->SetFont('Arial','B',5);		
			foreach($Notas as $NotF){					
				if(!$ban55){
					$NotTot=$NotF;
					$ban55=1;
				}
				else{
					$NotTot=$NotTot."\n".$NotF;
				}
				$Salto=$Salto-3;
			}
			
		}
		$this->SetY($Salto);
		if($NotTot){
			$this->MultiCell(194,3,strtoupper($NotTot),1,'J');
		}
		//$this->SetFont('Arial','I',8);
		//$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		//$this->Ln(3);
		//$this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
	}
}	
//$pdf=new PDF('P','mm',array(220,140));
$pdf=new PDF('P','mm','letter');
$pdf->AliasNbPages();
//$pdf->AddPage();//Agrega una paguina en blanco al pdf
$pdf->SetFont('Arial','',8);//Fuente documento,negrilla,tamaño letra
$pdf->BasicTable($DatosFac);
$pdf->Output();

?>
       