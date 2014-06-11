<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	//require('LibPDF/fpdf.php');	
	require('LibPDF/rotation.php');
	$ND=getdate();
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
		,individual,direccion,telefono,tipoasegurador,codigosgsss,nofactura
		from facturacion.facturascredito,central.terceros where facturascredito.compania='$Compania[0]' and terceros.compania='$Compania[0]' and nofactura=$NoFac and 
		entidad=identificacion";
		$res=ExQuery($cons); 
		$fila=ExFetch($res);
		$consFormat="select formato from facturacion.facturascredito,contratacionsalud.contratos where facturascredito.compania='$Compania[0]' and contratos.compania='$Compania[0]' and 	
		nofactura=$NoFac and contratos.entidad=facturascredito.entidad and facturascredito.contrato=contratos.contrato and facturascredito.nocontrato=contratos.numero";
		$resFormat=ExQuery($consFormat);
		$filaFormat=ExFetch($resFormat);
		if(!$Formato){$Formato=$filaFormat[0];}
		if($filaFormat[0]==$Formato){
			$DatosFac[$NoFac]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14]);			
			if($fila[9]==1){
				$cons2="select (primnom || ' ' || segnom || ' ' || primape || ' ' || segape ) as nompac,cedula,liquidacion.nocarnet,liquidacion.tipousu,liquidacion.nivelusu,autorizac1
				,autorizac2,autorizac3,numservicio,noliquidacion from facturacion.liquidacion,central.terceros where terceros.compania='$Compania[0]' 
				and liquidacion.compania='$Compania[0]' and nofactura=$fila[14] and identificacion=cedula";	
				//echo $fila[14];
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
				$DatosLiq[$NoFac]=array($fila2[0],$fila2[1],$fila2[2],$fila2[3],$fila2[4],$fila2[5],$fila[6],$fila2[7],$fila2[8],$fila2[9]);				
			}	
			
			$cons2="select grupo,tipo,sum(cantidad),vrunidad,almacenppal from facturacion.detallefactura where compania='$Compania[0]' and nofactura=$fila[14] and vrunidad!=0
			group by grupo,tipo,vrunidad,almacenppal order by grupo";
			$res2=ExQuery($cons2);
			//echo $cons2."<br>";
			while($fila2=ExFetch($res2)){
				$Id="$fila[14]$fila2[0]";
				if($fila2[0]==$GrupsCUPs[$fila2[0]][1]){$fila2[0]=$GrupsCUPs[$fila2[0]][0];}
				$DetalleFac[$Id][$NoFac]=array($fila2[0],$fila2[1],$fila2[2],$fila2[3],$fila2[4],$fila[14]);
				//echo $DetalleFac[$Id][$NoFac][0]."<br>";
			}	
		}
	}
	
class PDF extends PDF_Rotate
{
	function Header1($NoFac,$Ini)
	{
		$this->AddPage();		
		global $Compania; 
		$Raiz = $_SERVER['DOCUMENT_ROOT'];
		$this->Image("$Raiz/Imgs/Logo.jpg",10,7,20,20);
		$this->SetFont('Arial','B',12);				
		$this->Cell(25,5,"",0,0,'L');				
		$this->Cell(135,5,utf8_decode(substr(strtoupper($Compania[0]),0,50)),0,0,'C');		
		$this->SetFont('Arial','',14);				
		$this->SetFillColor(228,228,228);				
		
		$Tamano=$this->GetStringWidth(" No. $NoFac ");		
		if($this->GetStringWidth(" $NoFac ")<$this->GetStringWidth(" FACTURA ")){
			$Tamano=$this->GetStringWidth(" FACTURA ");
		}
		
		$this->Cell($Tamano+1,5,"FACTURA","LRTB",0,'C',1);
		$this->SetFont('Arial','',8);
		$this->Ln(4);		
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(135,5,strtoupper($Compania[1]),0,0,'C');
		$this->SetFont('Arial','B',16);
		$this->Cell($Tamano+1,10," $NoFac ","LRB",0,'C');
		//$this->Cell($Tamano+1,10," 8589541 ","LRB",0,'C');
		$this->SetFont('Arial','',8);
		$this->Ln(4);
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(130,5,"CODIGO SGSSS ".strtoupper($Compania[17]),0,0,'C');	
		$this->Ln(4);
		$this->SetFont('Arial','',8);
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(130,5,$Compania[2]." - TELEFONOS: ".strtoupper($Compania[3]),0,0,'C');		
		if($Ini==1){
			$this->Ln(12);
		}
		else{
			$this->Ln(12);
		}
	}
	function Header2($Cliente,$Paciente)
	{
		//$this->AddPage();
		//DATOS CLIENTE		
		$this->SetFont('Arial','B',8);				
		$this->Cell(10,5,"",0,0,'L');
		$this->Cell(18,5,"CLIENTE:",0,0,'L');		
		$this->SetFont('Arial','',8);
		$this->Cell(0,5,strtoupper(utf8_decode($Cliente[1])),0,0,'L');		
		$this->Ln(4);		
		$this->SetFont('Arial','B',8);				
		$this->Cell(10,5,"",0,0,'L');
		$this->Cell(18,5,"NIT:",0,0,'L');		
		$this->SetFont('Arial','',8);
		$this->Cell(62,5,$Cliente[0],0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(24,5,"CODIGO SGSSS:",0,0,'L');			
		$this->SetFont('Arial','',8);
		$this->Cell(27,5,$Cliente[13],0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(15,5,"REGIMEN:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(30,5,strtoupper($Cliente[12]),0,0,'L');
		$this->Ln(4);	
		$this->Cell(10,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"CONTRATO:",0,0,'L');
		$this->SetFont('Arial','',8);		
		$this->Cell(62,5,substr(strtoupper($Cliente[2]),0,28),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(24,5,"No CONTRATO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(74,5,strtoupper($Cliente[3]),0,0,'L');
		$this->Ln(4);	
		$this->Cell(10,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"DIRECCION:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(62,5,substr($Cliente[10],0,38),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(24,5,"TELEFONO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(35,5,$Cliente[11],0,0,'L');				
		//$this->Rect(20,35,182,17);	RECTANGULO CLIENTE			
		global $DatosLiq;
		//DATOS PACIENTE
		if($Cliente[9]==1){			
			$this->Ln(10);
			$this->Cell(10,5,"",0,0,'L');	
			$this->SetFont('Arial','B',8);	
			$this->Cell(18,5,"PACIENTE:",0,0,'L');
			$this->SetFont('Arial','',8);
			$NomPac=strtoupper(utf8_decode($Paciente[0]));
			$this->Cell(101,5,substr($NomPac,0,52),0,0,'L');
			$this->SetFont('Arial','B',8);	
			$this->Cell(29,5,"IDENTIFICACION:",0,0,'L');
			$this->SetFont('Arial','',8);
			$this->Cell(26,5,$Paciente[1],0,0,'L');		
			$this->Ln(4);	
			$this->Cell(10,5,"",0,0,'L');	
			$this->SetFont('Arial','B',8);	
			$this->Cell(18,5,"No CARNET:",0,0,'L');
			$this->SetFont('Arial','',8);
			$this->Cell(40,5,$Paciente[2],0,0,'L');
			$this->SetFont('Arial','B',8);	
			$this->Cell(27,5,"TIPO DE USUARIO:",0,0,'L');
			$this->SetFont('Arial','',8);
			$this->Cell(34,5,strtoupper($Paciente[3]),0,0,'L');
			$this->SetFont('Arial','B',8);	
			$this->Cell(29,5,"NIVEL DE USUARIO:",0,0,'L');
			$this->SetFont('Arial','',8);
			$this->Cell(20,5,strtoupper($Paciente[4]),0,0,'L');
			$this->Ln(4);	
			$this->Cell(10,5,"",0,0,'L');	
			$this->SetFont('Arial','B',8);	
			$this->Cell(26,5,"AUTORIZACION 1:",0,0,'L');
			$this->SetFont('Arial','',8);
			$this->Cell(32,5,substr($Paciente[5],0,14),0,0,'L');
			$this->SetFont('Arial','B',8);	
			$this->Cell(27,5,"AUTORIZACION 2:",0,0,'L');
			$this->SetFont('Arial','',8);		
			$this->Cell(34,5,substr($Paciente[6],0,14),0,0,'L');				
			$this->SetFont('Arial','B',8);	
			$this->Cell(29,5,"AUTORIZACION 3:",0,0,'L');
			$this->SetFont('Arial','',8);		
			$this->Cell(32,5,substr($Paciente[7],0,14),0,0,'L');	
		}
	}
	function Titulos($Ini){
		//Titulos
		if($Ini==1){
			$this->Ln(12);		
		}
		$this->SetFillColor(228,228,228);
		$this->SetFont('Arial','B',8);			
		$this->Cell(120,5,"DESCRIPCION",1,0,'C',1);
		$this->Cell(20,5,"CANTIDAD",1,0,'C',1);
		$this->Cell(25,5,"VR UNIDAD",1,0,'C',1);
		$this->Cell(29,5,"VR TOTAL",1,0,'C',1);
		$this->SetFont('Arial','',8);
	}
	function Datos($NoFac)
	{
		global $DetalleFac; global $GrupsMeds; global $GrupsCUPs; global $ND; global $Compania; global $DatosFac; global $Incre; global $Estado;
		
		$this->Titulos(1);
		$NumLineas=0;
		
		foreach($DetalleFac as $Meds)
		{	
			if($Meds[$NoFac][0]!=""){			
			$this->SetFont('Arial','',8);
			$this->Ln(5);										
			$this->Cell(120,5,strtoupper(substr($Meds[$NoFac][0],0,57)),"LR",0,'L');					
			$this->Cell(20,5,substr($Meds[$NoFac][2],0,12),"LR",0,'R');
			$this->Cell(25,5,number_format($Meds[$NoFac][3],2),"LR",0,'R');					
			$this->Cell(29,5,number_format(($Meds[$NoFac][3]*$Meds[$NoFac][2]),2),"LR",0,'R');			
			$SubTot=$SuTot+($Meds[$NoFac][3]*$Meds[$NoFac][2]);
			$GranSubTot=$SubTot+$GranSubTot;	
			$POSY=$this->GetY();
			$NumLineas++;
			if($POSY>=250-$Incre && $POSY<255-$Incre){	
				$this->Ln(5);										
				$this->Cell(194,1,"","T",0,'L');					
				$this->Header1($NoFac,0);
				$this->Titulos(0);
				$NumLineas=0;
			}
			}
		}	
		
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
			$this->Cell(120,5,"","LR",0,'L');
			$this->Cell(20,5,"","LR",0,'L');
			$this->Cell(25,5,"","LR",0,'L');
			$this->Cell(29,5,"","LR",0,'L');
			$POSY=$this->GetY();
		}	
		

		//SUTOTALES,DESCUENTOS,COPAGO,TOTAL
		if($DatosFac[$NoFac][5]!=''){		
						
			$POSY=$this->GetY();
			if($POSY>=250-$Incre && $POSY<255-$Incre){	
				$this->Ln(5);										
				$this->Cell(194,1,"","T",0,'L');					
				$this->Header1($NoFac,0);		
			}
			
			$Total=$GranSubTot;
			$this->Ln(5);
			$this->SetFont('Arial','B',8);	
			$this->Cell(165,5,"SUBTOTAL GENERAL:",1,0,'R');
			$this->SetFont('Arial','',8);
            $this->Cell(29,5,number_format($DatosFac[$NoFac][5],2),1,0,'R');			
			
			
			if($DatosFac[$NoFac][7]!=''&&$DatosFac[$NoFac][7]!="0"){
				$POSY=$this->GetY();
				if($POSY>=250-$Incre && $POSY<255-$Incre){	
					$this->Ln(5);														
					$this->Header1($NoFac,0);					
				}
				$Total=$Total-$DatosFac[$NoFac][7];
				$this->Ln(5);				
				$this->SetFont('Arial','B',8);	
				$this->Cell(165,5,"DESCUENTO:",1,0,'R');
				$this->SetFont('Arial','',8);
				$this->Cell(29,5,number_format($DatosFac[$NoFac][7],2),1,0,'R');							
   			}
			
			$POSY=$this->GetY();
			if($POSY>=250-$Incre && $POSY<255-$Incre){	
				$this->Ln(5);										
				$this->Header1($NoFac,0);				
			}
			//if($DatosFac[$NoFac][6]!=''&&$DatosFac[$NoFac][6]!="0"){
				$Total=$Total-$DatosFac[$NoFac][6];
				$this->Ln(5);			
				$this->SetFont('Arial','B',8);	
				$this->Cell(165,5,"COPAGO:",1,0,'R');
				$this->SetFont('Arial','',8);
				$this->Cell(29,5,number_format($DatosFac[$NoFac][6],2),1,0,'R');	
			//}	
			
			$POSY=$this->GetY();		
			if($POSY>=250-$Incre && $POSY<255-$Incre){	
				$this->Ln(5);														
				$this->Header1($NoFac,0);				
			}
			$this->Ln(5);			
			$this->SetFont('Arial','B',8);	
			//$this->MultiCell(194,3,strtoupper($NotTot),1,'J');
			$this->MultiCell(194,5,strtoupper("SON: ".NumerosxLet($DatosFac[$NoFac][8])),1,'L');
			$this->Cell(165,5,"TOTAL:",1,0,'R');
			$this->SetFont('Arial','',8);
			$this->Cell(29,5,number_format($DatosFac[$NoFac][8],2),1,0,'R');			
			
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
				$this->Header1($NoFac,0);				
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
				$this->Header1($NoFac,0);					
				$this->Ln(25);
			}
			else{
				$this->Ln(30);
			}
			$this->Cell(10,5,"",0,0,'D');
			$this->Cell(80,5,"FIRMA ACEPTADO","T",0,'C');
			$this->Cell(15,5,"",0,0,'D');
			$this->Cell(80,5,"FIRMA RESPONSABLE","T",0,'C');			
		}		
	}
	function BasicTable($DatosFac)
	{
		global $DatosLiq;
		foreach($DatosFac as $Facturas){
			$this->Header1($Facturas[14],1);					
			$this->Header2($DatosFac[$Facturas[14]],$DatosLiq[$Facturas[14]]);
			$this->Datos($Facturas[14]);			
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
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		$this->Ln(3);
		$this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
	}
}	
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
//$pdf->AddPage();//Agrega una paguina en blanco al pdf
$pdf->SetFont('Arial','',8);//Fuente documento,negrilla,tamaño letra
$pdf->BasicTable($DatosFac);
$pdf->Output();

?>
       