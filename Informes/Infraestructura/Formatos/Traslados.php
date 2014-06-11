<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/rotation.php');
	$ND=getdate();
	
	$cons = "Select CabeceraTraslado,PiedeTraslado From Infraestructura.Traslados Where Compania='$Compania[0]' and Numero='$Numero'";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$H = $fila[0]; $F = $fila[1];
	
	$cons = "Select Cedula,PrimApe,SegApe,PrimNom,SegNom,CentrosCosto.CentroCostos,CentrosCosto.Codigo,SubUbicacionDestino From Central.Terceros,Central.CentrosCosto,Infraestructura.Traslados
	Where Terceros.Identificacion = Traslados.Cedula and CentrosCosto.Codigo = Traslados.CCDestino and 
	Traslados.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and CentrosCosto.Anio=$ND[year] and Numero = '$Numero'";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$Destino[0]="$fila[0]";
	$Destino[1]="$fila[3] $fila[4] $fila[1] $fila[2]";
	$Destino[2]="$fila[5]";
	$Destino[3]="$fila[6]";
	$Destino[4]="$fila[7]";
	
	$cons = "Select Cedula,PrimApe,SegApe,PrimNom,SegNom,CentrosCosto.CentroCostos,CentrosCosto.Codigo,SubUbicacionResp From Central.Terceros,Central.CentrosCosto,Infraestructura.Traslados
	Where Terceros.Identificacion = Traslados.Responsable and CentrosCosto.Codigo = Traslados.CCResponsable and 
	Traslados.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and CentrosCosto.Anio=$ND[year] and Numero = '$Numero'";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$Origen[0]="$fila[0]";
	$Origen[1]="$fila[3] $fila[4] $fila[1] $fila[2]";
	$Origen[2]="$fila[5]";
	$Origen[3]="$fila[6]";
	$Origen[4]="$fila[7]";
	
	$cons = "Select Traslados.AutoId,CodElementos.Codigo,Nombre,Caracteristicas,Modelo,Serie,Marca,CodElementos.Estado,FechaSolicita,Traslados.Estado 
	From Infraestructura.Traslados,Infraestructura.CodElementos
	Where Traslados.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' and Traslados.AutoId = CodElementos.AutoId and Numero='$Numero'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Fecha = $fila[8];
		$Datos[$NumRec] = array($fila[1],"$fila[2] $fila[3]",$fila[4],$fila[5],$fila[6],$fila[7]);
		$NumRec++;
		if(!$Estado)
		{
			$Estado=$fila[9];	
		}
	}
	
class PDF extends PDF_Rotate
{//192,168.1.110
	function BasicTable($data)
	{
		if(!$data){echo "no data";exit;}
		global $SubTotal; global $IVA; global $Total;
		$Anchos=array(20,85,25,20,25,25,30);
		$fill=false;$this->SetFillColor(248,248,248);
		foreach($data as $row)
		{
			$x = 0;
			foreach($row as $col)
			{
				$this->SetFont('Arial','',7);
				$POSY=$this->GetY();
				if($x==1){$col=substr($col,0,53);}
				if($x>=2){$col=substr($col,0,12);}
				$Lines="LR";
				if($POSY>=250 && $POSY<255){$Lines="LRB";}
				$this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,'L',$fill);
				$x++;	
			}
			$this->Ln();
		}
		$this->Cell(200,5,"",'T',0,'C');
	}

//Cabecera de página
function Header()
{
	global $Compania;global $ND;global $Numero;global $Fecha; global $H; global $F; global $Estado;
	
	if($Estado != "ANULADO")
	{
		if($Estado=="Solicitado"){$Estado="sin aprobar";}
		$this->SetFont('Arial','B',90);
		$this->SetTextColor(215,215,215);
		$this->Rotate(45,25,180);
		$this->Text(35,190,strtoupper($Estado));
		$this->SetTextColor(0,0,0);
		$this->Rotate(0);			
	}
	$Raiz = $_SERVER['DOCUMENT_ROOT'];
    $this->Image("$Raiz/Imgs/Logo.jpg",10,1,25,25);
    $this->SetFont('Arial','B',10);
    $this->Cell(0,5,strtoupper($Compania[0]),0,0,'C');
    $this->SetFont('Arial','',8);
    $this->Ln(4);
    $this->Cell(0,5,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(4);
	$this->Cell(0,5,"$Compania[2] - $Compania[3]",0,0,'C');
	$this->Ln(4);
	$this->Cell(0,5,"ACTA DE TRASLADO DE ELEMENTOS DEVOLUTIVOS",0,0,'C');
	
	$this->Ln(5);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"Fecha",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->Cell(150,5,$Fecha,0,0,'L');
	
	$this->SetFont('Arial','B',10);
	$this->SetFillColor(228,228,228);
	$this->Cell(30,10,"No. $Numero",0,0,'C',1);
	$this->SetFont('Arial','',8);
	$this->Ln(10);
	$this->SetFillColor(255,255,255);
	$this->MultiCell(200,5,utf8_decode($H),0,'J');
	$this->Ln(10);
	$this->SetFillColor(228,228,228);
	$this->SetFont('Arial','B',8);
	$this->Cell(20,5,"Codigo",1,0,'C',1);
	$this->Cell(85,5,"Nombre",1,0,'C',1);
	$this->Cell(25,5,"Modelo",1,0,'C',1);
	$this->Cell(20,5,"Serie",1,0,'C',1);
	$this->Cell(25,5,"Marca",1,0,'C',1);
	$this->Cell(25,5,"Estado",1,0,'C',1);
	//$this->Cell(30,5,"Total",1,0,'C',1);
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
$pdf->Ln(10);
$pdf->SetFillColor(255,255,255);
$pdf->MultiCell(200,5,utf8_decode($F),0,'J');
$pdf->Ln(10);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,"____________________________________",0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,"____________________________________",0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,"Quien Recibe: ",0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,"Quien Entrega: ",0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Ln(3);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,$Destino[1],0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,$Origen[1],0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Ln(3);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,"UBICACION: ".$Destino[2]." - $Destino[3]",0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,"UBICACION: ".$Origen[2]." - $Origen[3]",0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Ln(3);
$pdf->Cell(10,8,"",0,0,'C');
if($Destino[4]){$SubUbD = "Sub Ubicacion: ".$Destino[4];}
if($Origen[4]){$SubUbO = "Sub Ubicacion: ".$Origen[4];}
$pdf->Cell(80,8,$SubUbD,0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,$SubUbO,0,0,'L');
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