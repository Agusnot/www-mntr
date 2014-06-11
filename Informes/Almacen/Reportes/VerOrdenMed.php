<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
//require('LibPDF/fpdf.php');	
require('LibPDF/rotation.php');
//Datos Orden
$cons="select detalle,tipoorden,idescritura,numorden,posologia from salud.ordenesmedicas where estado='AC' and  Cedula='$Cedula'
and (tipoorden='Medicamento Urgente' or tipoorden='Medicamento Programado' or tipoorden='Medicamento No Programado') order by Detalle";
//echo $cons;
$res=ExQuery($cons);
while($fila=ExFetch($res)){
	$Datos[$fila[3]]=array($fila[0],$fila[4]);
}
//Datos Paciete
$cons="select cedula,(primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom ),terceros.nocarnet,terceros.tipousu,terceros.nivelusu,autorizac1,autorizac2,autorizac3
from salud.servicios,central.terceros
where servicios.compania='$Compania[0]' and Cedula='$Cedula' and terceros.compania='$Compania[0]' and identificacion=cedula";
$res=ExQuery($cons);
$DatPaciente=ExFetch($res);
//Datos Cliente
$cons="select (primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom ),pagadorxservicios.entidad,pagadorxservicios.contrato,pagadorxservicios.nocontrato,direccion,telefono,tipoasegurador,codigosgsss,planbeneficios,planservmeds
from salud.pagadorxservicios,central.terceros,contratacionsalud.contratos
where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and Identificacion='$Cedula' and pagadorxservicios.entidad=identificacion
and contratos.compania='$Compania[0]' and contratos.entidad=pagadorxservicios.entidad and pagadorxservicios.contrato=contratos.contrato and numero=nocontrato";
//echo $cons;
$res=ExQuery($cons);
$Cliente=ExFetch($res);
$PlanMeds=ExFetch($res);
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
		
		$this->SetFont('Arial','',8);
		$this->Ln(4);		
		$this->Cell(49,5,strtoupper($Compania[1]),0,0,'R');
		$this->SetFont('Arial','B',16);
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
                        if(!$xyz){$this->Ln(5);$xyz=1;}
                        $POSY=$this->GetY();
                        if($POSY>=106.00125&&$POSY<=111.00125){
                                $this->Header1(0);
                                $this->Titulos(0);
                        }
                        $this->SetFont('Arial','',8);
                        //$this->Cell(94,5,$Meds[0],'LR',0,'L');
                        //$this->Cell(100,5,substr($Meds[1],0,60),'LR',0,'L');
                        $setx = $this->GetX();$sety = $this->GetY();
                        $this->MultiCell(94,5,utf8_decode($Meds[0]),'LR','J');
                        $this->SetXY($setx+94, $sety);
                        $this->MultiCell(100,5,$Meds[1],'LR','J',0);
                    }
		}
		if($POSY<=101.00125){
			while($POSY<96.00125){			
				$this->Ln(1);
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

