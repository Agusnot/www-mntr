<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/rotation.php');
	$ND=getdate();
	
	$consx="Select Tipo,NumeroCompra,FechaCompra,Nombre,Caracteristicas,Marca,Grupo,AutoId,CostoInicial,
	VrIVA,(CostoInicial+VrIVA),UsuarioCrea,IncluyeIVA,PorcIVA,EstadoCompras,Cedula,Modelo,Detalle,VrFactura,NoFactura,Codigo 
	from InfraEstructura.CodElementos
	where CodElementos.Compania='$Compania[0]' and 
	Tipo='Compras' and NumeroCompra='$Numero'";
	$resx=ExQuery($consx);echo ExError();
	$filax=ExFetch($resx);
	$Fecha=$filax[2];$Usuario=$filax[11];
	$Detalle = $filax[17]; $VrFactura = $filax[18]; $NoFactura = $filax[19];
	$consx2="Select PrimApe,SegApe,PrimNom,SegNom,Direccion,Telefono from Central.Terceros where Identificacion='$filax[15]' and Compania='$Compania[0]'";
	$resx2=ExQuery($consx2);echo ExError();
	$filax2=ExFetch($resx2);
	$Identificacion = $filax[15];
	$NomTercero="$filax2[0] $filax2[1] $filax2[2] $filax2[3]";$Direccion=$filax2[4];$Telefono=$filax2[5];
	$Estado = $filax[14];
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
		$Datos[$NumRec] = array($filax[20],"$filax[3] $filax[4]",$filax[16],$filax[5],
		number_format($filax[8],2),number_format($filax[9],2),number_format($filax[10],2));
		$SubTotal=$SubTotal+$filax[8];
		$IVA=$IVA+$filax[9];
		$Total=$Total+$filax[10];
		$NumRec++;
	}
	$cons = "Select Concepto,Valor From Infraestructura.CostosAdicionales Where Compania='$Compania[0]' and Numero='$Numero'";
	$res = ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Datos1[$NumRec] = array($fila[0],$fila[1]);
		$TotalCostoAdicional = $TotalCostoAdicional + $fila[1]; 
		$NumRec++;	
	}
	
	
class PDF extends PDF_Rotate
{//192,168.1.110
	function BasicTable($data,$data1)
	{
		if(!$data){echo "no data";exit;}
		global $SubTotal; global $IVA; global $Total; global $TotalCostoAdicional;
		$Anchos=array(20,70,20,20,25,15,30);
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
		$this->Cell(130,5,"TOTALES",1,0,'R',1);
		$this->Cell(25,5,number_format($SubTotal,2),1,0,'R',1);
		$this->Cell(15,5,number_format($IVA,2),1,0,'R',1);
		$this->Cell(30,5,number_format($Total,2),1,0,'R',1);
		$this->Ln(10);
		if(count($data1)>0)
		{
			$this->SetFont('Arial','B',8);
			$this->SetFillColor(228,228,228);
			$this->Cell('30',5,"",0,0,'C');
			$this->Cell('170','5',"COSTOS ADICIONALES",'LTRB',0,'C',1);
			$this->Ln();
			$this->Cell('30',5,"",0,0,'C');
			$this->Cell('120','5',"Concepto",'LTRB',0,'C',1);
			$this->Cell('50','5',"Valor",'LTRB',0,'C',1);
			$this->Ln();
			foreach($data1 as $row1)
			{
				$x = 0;
				$this->Cell('30',5,"",0,0,'C');
				foreach($row1 as $col1)
				{
					if($x == 0){$col1=substr($col1,0,70);$Alinea = 'L';$Anchox = '120';$Lineas='L';} else{$Anchox='50';$Alinea = 'R';}
					if($x==1){$col1 = number_format($col1,2);$Lineas='LR';}
					$this->SetFont('Arial','',7);
					$this->Cell($Anchox,'5',utf8_decode(strtoupper($col1)),$Lineas,0,$Alinea);
					$x++;
				}
				$this->Ln();	
			}
			$this->Cell(170,0,"",0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->SetFillColor(228,228,228);
			$this->Cell('30',5,"",0,0,'C');
			$this->Cell(120,5,"TOTAL",1,0,'R',1);
			$this->Cell(50,5,number_format($TotalCostoAdicional,2),1,0,'R',1);
			$this->Ln();
			$this->Cell('30',5,"",0,0,'C');
			$this->Cell(120,5,"TOTAL GENERAL",1,0,'R',1);
			$this->Cell(50,5,number_format($TotalCostoAdicional+$Total,2),1,0,'R',1);
			$this->Ln();
		}
		
		$Letras=NumerosxLet(round($Total+$TotalCostoAdicional,0));
		$this->SetFont('Arial','',8);
		$this->SetFillColor(255,255,255);
		$this->Cell(0,5,"SON: $Letras M/CTE",0,0,'L',0);
	}

//Cabecera de página
function Header()
{
	global $Compania;global $Anio;global $MesIni;global $DiaIni;global $MesFin;global $DiaFin;global $ND;global $Numero;global $Fecha;
	global $NomTercero; global $Identificacion; global $Direccion; global $Telefono;
	global $Detalle; global $VrFactura; global $NoFactura; global $Estado;
    $Raiz = $_SERVER['DOCUMENT_ROOT'];
	$this->Image("$Raiz/Imgs/Logo.jpg",10,5,18,18);
	$this->SetFont('Arial','B',10);
    $this->Cell(0,5,strtoupper($Compania[0]),0,0,'C');
    $this->SetFont('Arial','',8);
    $this->Ln(4);
    $this->Cell(0,5,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(4);
	$this->Cell(0,5,"$Compania[2] - $Compania[3]",0,0,'C');
	
	$this->Ln(5);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"Fecha",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->Cell(150,5,$Fecha,0,0,'L',1);
	
	$this->SetFont('Arial','B',10);
	$this->SetFillColor(228,228,228);
	$this->Cell(30,10,"COMPRAS",0,0,'C',1);
	$this->SetFont('Arial','',8);
	
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
	$this->Cell(30,5,$Telefono,0,0,'L');
	
	$this->SetFont('Arial','B',10);
	$this->SetFillColor(255,255,255);
	$this->Cell(30,10,$Numero,0,0,'C',1);
	$this->SetFont('Arial','',8);
	
	$this->Ln(6);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"Detalle",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->Cell(100,5,$Detalle,0,0,'L',1);
	$this->Ln(6);
	$this->SetFillColor(228,228,228);
	$this->Cell(20,5,"No Factura",0,0,'R',1);
	$this->SetFillColor(255,255,255);
	$this->Cell(100,5,"$NoFactura ($ ".number_format($VrFactura,2).")",0,0,'L');
	$this->Ln(10);
	$this->SetFillColor(228,228,228);
	$this->SetFont('Arial','B',8);
	$this->Cell(20,5,"Codigo",1,0,'C',1);
	$this->Cell(70,5,"Nombre",1,0,'C',1);
	$this->Cell(20,5,"Modelo",1,0,'C',1);
	$this->Cell(20,5,"Marca",1,0,'C',1);
	$this->Cell(25,5,"Costo",1,0,'C',1);
	$this->Cell(15,5,"VrIva",1,0,'C',1);
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

$pdf->BasicTable($Datos,$Datos1);
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
if($Estado=="ANULADO")
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