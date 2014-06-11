<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	include("GeneraValoresEjecucion.php");
	require('LibPDF/rotation.php');
	$ND=getdate();
	$Fondo=1;
	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Credito,ContraCredito,DocSoporte,'',Estado,UsuarioCre,Anio
	from Presupuesto.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' Order By Credito Desc";
	$res=ExQuery($cons);echo ExError();
	$fila=ExFetchArray($res);
	$UsuarioCre=$fila[12];
	$MesFin=substr($fila['fecha'],5,2);$NOCDP=$fila['docsoporte'];
	$MesIni=1;
	$Anio=substr($fila['anio'],0,4);
	$Cuenta=$fila['cuenta'];
	$Fecha=$fila['fecha'];$Estado=$fila['estado'];
	$ListaFirms=Firmas($Fecha,$Compania);
	$Detalle=$fila['detalle'];
	$cons1="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion,Direccion,Telefono from Central.Terceros where Identificacion='$fila[4]' and Terceros.Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
	$Tercero="$fila1[0] $fila1[1] $fila1[2] $fila1[3] - Identificacion: $fila1[4]";

	$res=ExQuery($cons);
	while($fila=ExFetchArray($res))
	{
		$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila['cuenta']."' and Anio=". $fila['anio'] . " and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res9=ExQuery($cons9);
		$fila9=ExFetch($res9);
		$NomCuenta=strtoupper($fila9[0]);
		$ListaRubros=$ListaRubros." $fila[6] ($NomCuenta),";
		$TotCre=$TotCre+$fila['credito'];
	}
	$ListaRubros=substr($ListaRubros,0,strlen($ListaRubros)-1);
	
class PDF extends PDF_Rotate
{//192,168.1.110
	function BasicTable($data)
	{
		$Anchos=array(25,170,30,70);
		$fill=false;$this->SetFillColor(248,248,248);
		if(!$data){exit;}
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
				$POSY=$this->GetY();
				if($x>1 && $x<3){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				if($POSY>=251 && $POSY<257){$Lines="LRB";}
				else{$Lines="LR";}
				if($col=="*"){$col="";}
				if(strrpos($col,"*")){$Anchos[1]=70;}
				else{$Anchos[1]=170;}
				if(strrpos($col,"*") && $x==1){if($POSY>=251 && $POSY<257){$Lines="LRB";}else{$Lines="L";}}
				if($x>1){if($POSY>=251 && $POSY<257){$Lines="RB";}else{$Lines="";}}
				if($x==3){if($POSY>=251 && $POSY<257){$Lines="RB";}else{$Lines="R";}}
				$this->Cell($Anchos[$x],5,$col,$Lines,0,$Alinea,$fill);
				$x++;
			}
			$this->Ln();
			$fill=!$fill;
			
		}
	}

//Cabecera de página
function Header()
{
	global $Compania;global $PerFin;global $Comprobante;global $Numero;global $ListaFirms;global $Anio;global $Estado;global $Fecha;global $NombreMes; global $ListaRubros;global $NOCDP;
    //Logo
//    $this->Image('/Imgs/Logo.jpg',10,8,33);
    //Arial bold 15
    //Movernos a la derecha
	$Raiz = $_SERVER['DOCUMENT_ROOT'];
    $this->Ln(4);

	if($Estado == "AN")
	{
		$this->SetFont('Arial','B',90);
		$this->SetTextColor(215,215,215);
		$this->Rotate(45,25,180);
		$this->Text(35,190,strtoupper("ANULADO"));
		$this->SetTextColor(0,0,0);
		$this->Rotate(0);			
	}


	$this->Image("$Raiz/Imgs/Logo.jpg",10,10,25,25);

    //Título
	$this->SetX(35);
    $this->SetFont('Arial','B',12);
    $this->Cell(20,5,strtoupper($Compania[0]),0,0,'L');
    $this->Ln(5);

	$this->SetX(35);
    $this->SetFont('Arial','B',9);
    $this->Cell(20,4,strtoupper(utf8_decode_seguro($Compania[1])),0,0,'L');
    $this->Ln(4);

	$this->SetX(35);
    $this->Cell(20,4,strtoupper($Compania[2]),0,0,'L');
    $this->Ln(4);

	$this->SetX(35);
    $this->Cell(20,4,"TELEFONOS: " . strtoupper($Compania[3]),0,0,'L');


	$this->SetX(125);
    $this->SetFont('Arial','B',10);
	$this->SetFillColor(228,228,228);
    $this->Cell(80,5,"$Comprobante",1,0,'C',1);
    $this->Ln(5);
	$this->SetX(125);
    $this->SetFont('Arial','B',15);
    $this->Cell(80,8,"$Numero",1,0,'C');

    $this->SetFont('Arial','',10);
    $this->Ln(20);
	
	$this->MultiCell(195,6,"El DIA ". strtoupper(substr(NumerosxLet(substr($Fecha,8,2)),0,strlen(NumerosxLet(substr($Fecha,8,2)))-15)) . " (" . substr($Fecha,8,2) .") DE " . strtoupper($NombreMes[(substr($Fecha,5,2))*1]) . " DEL AÑO " . strtoupper(substr(NumerosxLet($Anio),0,strlen(NumerosxLet($Anio))-15)) ." SE REGISTRA LA ORDEN DE PRESTACION DE SERVICIOS NUMERO $Numero, LA CUAL AFECTA EL RUBRO PRESUPUESTAL: $ListaRubros. AMPARADO MEDIANTE CERTIFICADO DE DISPONIBILIDAD PRESUPUESTAL NUMERO $NOCDP",0,'J',0);

	
    $this->Ln(5);

    if($Estado == "AN")
	{
		$this->SetFont('Arial','B',90);
		$this->SetTextColor(215,215,215);
		$this->Rotate(45,25,180);
		$this->Text(35,190,strtoupper("ANULADO"));
		$this->SetTextColor(0,0,0);
		$this->Rotate(0);			
	}

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

	$pdf->SetFillColor(248,248,248);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(195,5,"CONCEPTO DEL GASTO:",0,0,'L',1);
$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(195,5,utf8_decode_seguro($Detalle),0,'J',0);

$pdf->Ln(3);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(195,5,"VALOR:",0,0,'L',1);
$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(195,5,strtoupper(NumerosxLet($TotCre)),0,'J',0);
$pdf->Ln(3);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(195,5,"NOMBRE O RAZON SOCIAL:",0,0,'L',1);
$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(195,5,strtoupper(utf8_decode_seguro($Tercero)),0,'J',0);
$pdf->Ln(3);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(195,5,"FECHA:",0,0,'L',1);
$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(195,5,strtoupper($Fecha),0,'J',0);

$pdf->Ln(20);


$pdf->Cell(195,5,"______________________________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(195,5,strtoupper($ListaFirms['Presupuesto'][0]),0,0,'C');
$pdf->Ln(5);
$pdf->Cell(195,5,strtoupper($ListaFirms['Presupuesto'][1]),0,0,'C');

$pdf->Ln(15);


$pdf->SetFont('Arial','I',8);
$pdf->Cell(195,5,"Elaborado por: $UsuarioCre",0,0,'L');


$pdf->Output();

?>