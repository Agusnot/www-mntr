<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
//require('LibPDF/fpdf.php');	
require('LibPDF/rotation.php');
//Datos Orden
$cons="select detalle,tipoorden,idescritura,numorden,posologia from salud.ordenesmedicas where cedula='$Paciente[1]' and estado='AC' and numservicio=$NumServ and idescritura=$IdEscritura
and (tipoorden='Medicamento Urgente' or tipoorden='Medicamento Programado' or tipoorden='Medicamento No Programado') order by fecha";	
$res=ExQuery($cons);
//echo $cons;
while($fila=ExFetch($res)){
	$Datos[$fila[3]]=array($fila[0],$fila[4]);
}
//Datos Paciete
$cons="select cedula,(primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom ),terceros.nocarnet,terceros.tipousu,terceros.nivelusu,autorizac1,autorizac2,autorizac3
from salud.servicios,central.terceros
where servicios.compania='$Compania[0]' and numservicio=$NumServ and terceros.compania='$Compania[0]' and identificacion=cedula";
$res=ExQuery($cons);
$DatPaciente=ExFetch($res);
//Datos Cliente
$cons="select (primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom ),pagadorxservicios.entidad,pagadorxservicios.contrato,pagadorxservicios.nocontrato,direccion,telefono,tipoasegurador,codigosgsss,planbeneficios,planservmeds
from salud.pagadorxservicios,central.terceros,contratacionsalud.contratos
where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and numservicio=$NumServ and pagadorxservicios.entidad=identificacion
and contratos.compania='$Compania[0]' and contratos.entidad=pagadorxservicios.entidad and pagadorxservicios.contrato=contratos.contrato and numero=nocontrato";
//echo $cons;
$res=ExQuery($cons);
$Cliente=ExFetch($res);
$cons="select nombreplan from contratacionsalud.planeservicios where compania='$Compania[0]' and autoid=$Cliente[9] and clase='Medicamentos'";
$res=ExQuery($cons);
$PlanMeds=ExFetch($res);
$cons="select nombreplan from contratacionsalud.planeservicios where compania='$Compania[0]' and autoid=$Cliente[8] and clase!='Medicamentos'";
$res=ExQuery($cons);
$PlanServ=ExFetch($res);
class PDF extends PDF_Rotate
{
	function Header1($Ini)
	{
		$this->AddPage();		
		global $Compania; global $IdEscritura;
		$Raiz = $_SERVER['DOCUMENT_ROOT'];
		$this->Image("$Raiz/Imgs/Logo.jpg",10,7,20,20);
		$this->SetFont('Arial','B',12);				
		$this->Cell(25,5,"",0,0,'L');				
		$this->Cell(135,5,utf8_decode(substr(strtoupper($Compania[0]),0,50)),0,0,'L');		
		$this->SetFont('Arial','',14);				
		$this->SetFillColor(228,228,228);				
		
		$Tamano=$this->GetStringWidth(" $IdEscritura ");		
		if($this->GetStringWidth(" $IdEscritura ")<$this->GetStringWidth(" No. ORDEN ")){
			$Tamano=$this->GetStringWidth(" No. ORDEN ");
		}
		
		$this->Cell($Tamano+1,5,"ORDEN","LRTB",0,'C',1);
		$this->SetFont('Arial','',8);
		$this->Ln(4);		
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(135,5,strtoupper($Compania[1]),0,0,'L');
		$this->SetFont('Arial','B',16);
		$this->Cell($Tamano+1,10," $IdEscritura ","LRB",0,'C');
		//$this->Cell($Tamano+1,10," 8589541 ","LRB",0,'C');
		$this->SetFont('Arial','',8);
		$this->Ln(4);
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(130,5,"CODIGO SGSSS ".strtoupper($Compania[17]),0,0,'L');	
		$this->Ln(4);
		$this->SetFont('Arial','',8);
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(130,5,$Compania[2]." - TELEFONOS: ".strtoupper($Compania[3]),0,0,'L');		
		if($Ini==1){
			$this->Ln(8);
		}
		else{
			$this->Ln(12);
		}
	}
	function Header2()
	{
		//$this->AddPage();
		//DATOS CLIENTE		
		global $Cliente; global $PlanMeds; global $PlanServ;
		$this->SetFont('Arial','B',8);				
		$this->Cell(5,5,"",0,0,'L');
		$this->Cell(18,5,"CLIENTE:",0,0,'L');		
		$this->SetFont('Arial','',8);
		$this->Cell(0,5,strtoupper(utf8_decode($Cliente[0])),0,0,'L');		
		$this->Ln(4);		
		$this->SetFont('Arial','B',8);				
		$this->Cell(5,5,"",0,0,'L');
		$this->Cell(18,5,"NIT:",0,0,'L');		
		$this->SetFont('Arial','',8);
		$this->Cell(62,5,$Cliente[1],0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(24,5,"CODIGO SGSSS:",0,0,'L');			
		$this->SetFont('Arial','',8);
		$this->Cell(27,5,$Cliente[7],0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(19,5,"REGIMEN:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(30,5,strtoupper($Cliente[6]),0,0,'L');
		$this->Ln(4);	
		$this->Cell(5,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"CONTRATO:",0,0,'L');
		$this->SetFont('Arial','',8);		
		$this->Cell(62,5,substr(strtoupper($Cliente[2]),0,28),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(24,5,"No CONTRATO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(27,5,strtoupper($Cliente[3]),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(19,5,"PLAN MEDS:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(27,5,strtoupper($PlanMeds[0]),0,0,'L');
		$this->Ln(4);	
		$this->Cell(5,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"DIRECCION:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(62,5,substr($Cliente[4],0,38),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(24,5,"TELEFONO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(27,5,$Cliente[5],0,0,'L');				
		$this->SetFont('Arial','B',8);	
		$this->Cell(19,5,"PLAN SERVS:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(27,5,strtoupper($PlanServ[0]),0,0,'L');
		//$this->Rect(20,35,182,17);	//RECTANGULO CLIENTE	
		$this->Ln(8);
		
		//DATOS DatPaciente
		global $DatPaciente;
		$this->Cell(5,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"DatPaciente:",0,0,'L');
		$this->SetFont('Arial','',8);
		$NomPac=strtoupper(utf8_decode($DatPaciente[1]));		
		$this->Cell(113,5,substr($NomPac,0,52),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(29,5,"IDENTIFICACION:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(26,5,$DatPaciente[0],0,0,'L');		
		$this->Ln(4);	
		$this->Cell(5,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"No CARNET:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(46,5,$DatPaciente[2],0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(27,5,"TIPO DE USUARIO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(40,5,strtoupper($DatPaciente[3]),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(29,5,"NIVEL DE USUARIO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(20,5,strtoupper($DatPaciente[4]),0,0,'L');
		$this->Ln(4);	
		$this->Cell(5,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(26,5,"AUTORIZACION 1:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(38,5,substr($DatPaciente[5],0,14),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(27,5,"AUTORIZACION 2:",0,0,'L');
		$this->SetFont('Arial','',8);		
		$this->Cell(40,5,substr($DatPaciente[6],0,14),0,0,'L');				
		$this->SetFont('Arial','B',8);	
		$this->Cell(29,5,"AUTORIZACION 3:",0,0,'L');
		$this->SetFont('Arial','',8);		
		$this->Cell(32,5,substr($DatPaciente[7],0,14),0,0,'L');		
	}
	function Titulos($Ini){
		//Titulos
		if($Ini==1){
			$this->Ln(8);		
		}
		$this->SetFillColor(228,228,228);
		$this->SetFont('Arial','B',8);	
		$this->Cell(94,5,"DESCRIPCION",1,0,'C',1);
		$this->Cell(100,5,"POSOLOGIA",1,0,'C',1);		
		$this->SetFont('Arial','',8);
	}
	function BasicTable($Datos){
		$this->Header1(1);
		$this->Header2();
		$this->Titulos(1);
		if($Datos){
			foreach($Datos as $Meds){
				$this->Ln(5);
				$POSY=$this->GetY();
				if($POSY>=106.00125&&$POSY<=111.00125){
					$this->Header1(0);
					$this->Titulos(0);
				}				
				$this->SetFont('Arial','',8);	
				$this->Cell(94,5,$Meds[0],'LR',0,'L');				
				$this->Cell(100,5,substr($Meds[1],0,60),'LR',0,'L');				
				//$this->MultiCell(94,5,$Meds[0],'LR','C');				
				//$this->MultiCell(100,5,$Meds[1],'LR','C',0);
			}				
		}
		if($POSY<=101.00125){
			while($POSY<96.00125){			
				$this->Ln(5);
				$this->Cell(94,5,"","LR",0,'L');
				$this->Cell(100,5,"","LR",0,'L');				
				$POSY=$this->GetY();
			}
			$this->Ln(5);
			$this->Cell(194,5,'','T',0,'C');
		}
		else{
			$this->Ln(5);
			$this->Cell(194,5,'','T',0,'C');
		}
		$this->Ln(10);
		if($POSY>=106.00125-$Incre && $POSY<111.00125-$Incre){				
			$this->Header1(0);					
		}
		else{		
			$this->Cell(10,5,"",0,0,'D');
			$this->Cell(80,5,"FIRMA ACEPTADO","T",0,'C');
			$this->Cell(15,5,"",0,0,'D');
			$this->Cell(80,5,"FIRMA RESPONSABLE","T",0,'C');	
		}
	/*	for($i=0;$i<13;$i++){
			$this->Ln(5);
			$POSY=$this->GetY();
			if($POSY>=106.00125&&$POSY<=111.00125){
					$this->Header1(0);
					$this->Titulos(0);
			}
			$this->SetFont('Arial','',8);	
			$this->Cell(94,5,$POSY,'LR',0,'C');				
			$this->Cell(100,5,substr($Meds[1],0,60),'LR',0,'C');	
		}*/
	}	
}
$Hoja=array('216','140');
$pdf=new PDF('P','mm',$Hoja);
$pdf->AliasNbPages();
//$pdf->AddPage();//Agrega una paguina en blanco al pdf
$pdf->SetFont('Arial','',8);//Fuente documento,negrilla,tamaño letra
$pdf->BasicTable($Datos);
$pdf->Output();	
?>	

