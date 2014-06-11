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
	$MesFin=substr($fila['fecha'],5,2);
	$MesIni=1;
	$Anio=substr($fila['anio'],0,4);
	$Cuenta=$fila['cuenta'];
	$Fecha=$fila['fecha'];$Estado=$fila['estado'];
	$ListaFirms=Firmas($Fecha,$Compania);
	$Detalle=$fila['detalle'];
	$cons1="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion,Direccion,Telefono from Central.Terceros where Identificacion='$fila[4]' and Terceros.Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);

	$res=ExQuery($cons);
	while($fila=ExFetchArray($res))
	{
		$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila['cuenta']."' and Anio=". $fila['anio'] . " and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res9=ExQuery($cons9);
		$fila9=ExFetch($res9);
		$NomCuenta=$fila9[0];
		$consEPUC="Select NoCaracteres from Presupuesto.EstructuraPuc where Compania='$Compania[0]' and Anio=". $fila['anio'] ." Order By Nivel";
		$resEPUC=ExQuery($consEPUC);echo ExError();
		while($filaEPUC=ExFetch($resEPUC))
		{
			$j++;
			$NumCar=$NumCar+$filaEPUC[0];
			$PartCuenta=substr($fila['cuenta'],0,$NumCar);
			$cons10="Select Cuenta,Nombre from Presupuesto.PlanCuentas where Cuenta='$PartCuenta' and Anio=". $fila['anio'] . " and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
			$res10=ExQuery($cons10);
			$fila10=ExFetch($res10);

			$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila10[0]."' and Anio=". $fila['anio'] . " and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
			$res9=ExQuery($cons9);
			$fila9=ExFetch($res9);
			$NomCuenta=$fila9[0];
			if($fila10[0]==$fila['cuenta']){
				$Cuenta=$fila['cuenta'];
				$ApropIni=GeneraApropiacion();
				
				$Adiciones=GeneraValor("Adicion","Ambos",1);
				$Reducciones=GeneraValor("Reduccion","Ambos",1);
				$Creditos=GeneraValor("Traslado","Credito",1);
				$CCreditos=GeneraValor("Traslado","ContraCredito",1);
				$ApropDef=$ApropIni+$Adiciones-$Reducciones+$Creditos-$CCreditos;
				
				$DisponibilidadesAnt=GeneraValor("Disponibilidad","Credito",2);
				$DisminucDispoAnt=GeneraValor("Disminucion a disponibilidad","ContraCredito",2);
				$TotDispoAnt=$DisponibilidadesAnt-$DisminucDispoAnt;
				
				$DispoPeriodo=GeneraValor("Disponibilidad","Credito",3);
				$DismPeriodo=GeneraValor("Disminucion a disponibilidad","ContraCredito",3);
				$TotDispoPeriodo=$DispoPeriodo-$DismPeriodo;
				
				$TotDisponibilidades=$TotDispoAnt+$TotDispoPeriodo;
				$Dispo=$fila['credito'];
				$SaldoDisponible=$ApropDef-$TotDisponibilidades + $Dispo;
				$NewSaldo=$SaldoDisponible-$Dispo;
				}
			$Datos[$j]=array($PartCuenta,$NomCuenta);
			if($fila10[0]==$fila['cuenta'])
			{
				$j++;
				$Datos[$j]=array("*","                                     * Saldo Anterior",$SaldoDisponible,"*");
				$j++;
				$Datos[$j]=array("*","                                     * Disponibilidad Presupuestal",$Dispo,"*");
				$j++;
				$Datos[$j]=array("*","                                     * Saldo Siguiente",$NewSaldo,"*");
				$ValTotal=$ValTotal+$Dispo;
				break;
			}

		}

		$NumCar=0;

	}
	
	$TotCre=0;$TotCCre=0;

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
	global $Compania;global $PerFin;global $Comprobante;global $Numero;global $ListaFirms;global $Anio;global $Estado;
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
    $this->Cell(20,4,strtoupper($Compania[1]),0,0,'L');
    $this->Ln(4);

	$this->SetX(35);
    $this->Cell(20,4,strtoupper(utf8_decode_seguro($Compania[2])),0,0,'L');
    $this->Ln(4);

	$this->SetX(35);
    $this->Cell(20,4,"TELEFONOS: " . strtoupper(utf8_decode_seguro($Compania[3])),0,0,'L');


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
    $this->MultiCell(0,5,"Una vez revisado el libro de control de presupuesto, vigencia fiscal $Anio, existe una asignacion presupuestal disponible, de conformidad con el siguiente detalle: ");
	
    $this->Ln(10);

    $this->SetFont('Arial','B',8);

    $this->Cell(25,5,"Codigo",1,0,'C',1);
    $this->Cell(170,5,"Descripcion",1,0,'C',1);
    $this->Ln(5);	if($Estado == "AN")
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
$pdf->SetFont('Arial','',8);


$pdf->BasicTable($Datos);
$pdf->Cell(195,0,"","B",0,'C');
$pdf->Ln(10);
$pdf->MultiCell(195,5,"CONCEPTO: ". utf8_decode_seguro($Detalle) ." POR EL VALOR DE " . strtoupper(NumerosxLet($ValTotal)) . " ( $ ".number_format($ValTotal,2).") DEL PRESUPUESTO DE GASTOS DE LA VIGENCIA FISCAL $Anio",0,'J',0);

$pdf->Ln(5);
$pdf->MultiCell(195,5,"PARA CONSTANCIA SE FIRMA EN ".strtoupper($Compania[7]) . " A LOS " . strtoupper(substr(NumerosxLet(substr($Fecha,8,2)),0,strlen(NumerosxLet(substr($Fecha,8,2)))-15)) . " (" . substr($Fecha,8,2) .") DIAS DEL MES DE " . strtoupper($NombreMes[(substr($Fecha,5,2))*1]) . " DEL AÑO " . strtoupper(substr(NumerosxLet($Anio),0,strlen(NumerosxLet($Anio))-15)),0,'J',0);
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