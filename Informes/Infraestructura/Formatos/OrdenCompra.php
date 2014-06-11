<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/rotation.php');
	$ND=getdate();
	
	$consx="Select Tipo,NumeroOrdenCompra,FechaOrdenCompra,Nombre,Caracteristicas,Marca,Grupo,AutoId,CostoInicial,
	VrIVA,(CostoInicial+VrIVA),UsuarioCrea,IncluyeIVA,PorcIVA,EstadoOrdenCompra,Cedula,Modelo,Detalle
	from InfraEstructura.CodElementos
	where CodElementos.Compania='$Compania[0]' and 
	(Tipo='Orden Compra' or Tipo='Compras') and NumeroOrdenCompra='$Numero'";
	$resx=ExQuery($consx);echo ExError();
	$filax=ExFetch($resx);
	$Fecha=$filax[2];$Usuario=$filax[11];
	$consx2="Select PrimApe,SegApe,PrimNom,SegNom,Direccion,Telefono from Central.Terceros where Identificacion='$filax[15]' and Compania='$Compania[0]'";
	$resx2=ExQuery($consx2);echo ExError();
	$filax2=ExFetch($resx2);
	$Estado = $filax[14];
	$Identificacion = $filax[15];
	$NomTercero="$filax2[0] $filax2[1] $filax2[2] $filax2[3]";$Direccion=$filax2[4];$Telefono=$filax2[5];
	$Detalle = $filax[17];
	$resx=ExQuery($consx);
	while($filax=ExFetch($resx))
	{
		if($filax[13] && $filax[13]!=0)
		{
			if($filax[12]==1)
			{
				$filax[9] = $filax[10] * $filax[13]/100;
				$filax[8] = $filax[10] - $filax[9];	
			}	
		}
		
		/////DATOS[NumRec]=(Nombre+Caracteristicas, Modelo, Marca, Costo, VrIva, Total)
		$Datos[$NumRec] = array("$filax[3] $filax[4]",$filax[16],$filax[5],number_format($filax[8],2),number_format($filax[9],2),number_format($filax[10],2));
		$SubTotal=$SubTotal+$filax[8];
		$IVA=$IVA+$filax[9];
		$Total=$Total+$filax[10];
		$NumRec++;
	}
	
class PDF extends PDF_Rotate
{//192,168.1.110
	function BasicTable($data)
	{
		if(!$data){echo "no data";exit;}
		global $SubTotal; global $IVA; global $Total;
		$Anchos=array(80,20,20,25,25,30);
		$fill=false;$this->SetFillColor(248,248,248);
		foreach($data as $row)
		{
			$x = 0;
			foreach($row as $col)
			{
				$this->SetFont('Arial','',7);
				//$POSY=$this->GetY();
				if($x==0){$col=substr($col,0,47);}
				if($x>=3){$Alinea='R';}
				else{$Alinea='L';}
				$Lines="LR";
				//if($POSY>=187 && $POSY<192){$Lines="LRB";}
				$this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,$Alinea,$fill);
				$x++;	
			}
			$this->Ln();
					
		}
		$this->Cell(200,0,"","T",0,'C');
		$this->Ln();
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(228,228,228);
		$this->Cell(120,5,"TOTALES",1,0,'R',1);
		$this->Cell(25,5,number_format($SubTotal,2),1,0,'R',1);
		$this->Cell(25,5,number_format($IVA,2),1,0,'R',1);
		$this->Cell(30,5,number_format($Total,2),1,0,'R',1);
		$this->Ln();
		$Letras=NumerosxLet(round($Total,0));
		$this->SetFont('Arial','',8);
		$this->SetFillColor(255,255,255);
		$this->Cell(0,5,"SON: $Letras M/CTE",0,0,'L',0);
	}

//Cabecera de página
function Header()
{
	global $Compania;global $Anio;global $MesIni;global $DiaIni;global $MesFin;global $DiaFin;global $ND;global $Numero;global $Fecha;
	global $NomTercero; global $Identificacion; global $Direccion; global $Telefono; global $Estado;global $Detalle;
        if($Estado != "ANULADO")
	{
		if($Estado=="Solicitado"){$Estado="sin aprobar";}
		$this->SetFont('Arial','B',90);
		$this->SetTextColor(215,215,215);
		$this->Rotate(45,1,150);
		$this->Text(20,200,strtoupper($Estado));
		$this->SetTextColor(0,0,0);
		$this->Rotate(0);			
	}
	$Raiz = $_SERVER['DOCUMENT_ROOT'];
	$this->Image("$Raiz/Imgs/Logo.jpg",10,5,25,25);
    //Arial bold 15
    $this->SetFont('Arial','B',10);
    //Movernos a la derecha

    //Título
    $this->Cell(0,5,strtoupper($Compania[0]),0,0,'C');
    //Salto de línea
    $this->SetFont('Arial','',8);
    $this->Ln(4);
    $this->Cell(0,5,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(4);
	$this->Cell(0,5,"$Compania[2] - $Compania[3]",0,0,'C');
	$this->Ln(4);
    $this->Cell(0,5,"ORDEN DE COMPRA",0,0,'C');
    $this->Ln(4);
    $this->Cell(0,5,$Numero,0,0,'C');
	$this->Ln(5);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"Fecha",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->Cell(20,5,$Fecha,0,0,'L',1);
	$this->Ln(6);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"Proveedor",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->Cell(100,5,$NomTercero,0,0,'L',1);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"Identificacion",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->Cell(20,5,$Identificacion,0,0,'L');
	$this->Ln(6);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"Direccion",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->Cell(100,5,$Direccion,0,0,'L',1);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"Telefono",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->Cell(100,5,$Telefono,0,0,'L');
        $this->Ln(6);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"Detalle",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->MultiCell(180,5,$Detalle,0,'J');
        $this->Ln(10);
	$this->Cell(0,5,"De acuerdo a su cotizacion, sirvase enviar con destino a Hospital San Rafael de Pasto los siguientes articulos:",0,0,'C');
	$this->Ln(10);
	$this->SetFillColor(228,228,228);
	$this->SetFont('Arial','B',8);
	$this->Cell(80,5,"Nombre",1,0,'C',1);
	$this->Cell(20,5,"Modelo",1,0,'C',1);
	$this->Cell(20,5,"Marca",1,0,'C',1);
	$this->Cell(25,5,"Costo",1,0,'C',1);
	$this->Cell(25,5,"VrIva",1,0,'C',1);
	$this->Cell(30,5,"Total",1,0,'C',1);
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
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    $this->Ln(3);
    $this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
}
}

$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$pdf->BasicTable($Datos);
//$pdf->Cell(263,0,"","B",0,'C');

$pdf->Ln(20);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,"____________________________________",0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,"____________________________________",0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,"Elaboro",0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,"Aprobo",0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Ln(3);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,$usuario[0],0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,"Director General",0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
if($Estado == "ANULADO")
{
	$pdf->SetFont('Arial','B',90);
	$pdf->SetTextColor(215,215,215);
	$pdf->Rotate(45,1,150);
	$pdf->Text(35,190,strtoupper($Estado));
	$pdf->SetTextColor(0,0,0);
	$pdf->Rotate(0);			
}
$pdf->Output();
?>
