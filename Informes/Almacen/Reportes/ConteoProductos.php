<? 
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
require('LibPDF/fpdf.php');
include("Consumo/ObtenerSaldos.php");
$FechaIni="$Anio-$MesIni-$DiaIni";
$FechaFin="$Anio-$MesFin-$DiaFin";
$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
$ND=getdate();
if($Bodegas){ $ConBodega = " and Bodega = '$Bodegas'";}
if($Estantes){ $ConEstante = " and Estante = '$Estantes'";}
$cons = "Select Autoid,Codigo1,NombreProd1,UnidadMedida,Presentacion,Bodega,Estante,Nivel
from Consumo.CodProductos Where CodProductos.Compania='$Compania[0]' and 
CodProductos.Anio=$Anio and Codproductos.AlmacenPpal='$AlmacenPpal'$ConBodega$ConEstante order by Bodega,Nivel";
$res = ExQuery($cons);
while($fila=ExFEtch($res))
{
    $Datos[$fila[0]]=array($fila[1],"$fila[2] $fila[3] $fila[4]",$fila[5],$fila[6],$fila[7]);
    //$c++;
}

class PDF extends FPDF
{
	function BasicTable($Datos)
	{
		global $Saldos; global $VrSaldoIni; global $VrEntradas; global $VrSalidas; global $VrDevoluciones;
		if(!$Saldos){$A = "130";$Sub = 75;}
		else{$A = "110"; $Sub = 65;}
		$Anchos = array(10,$A,20,20,20,20,20,20,20);
		$fill=false;$this->SetFillColor(248,248,248);
		while(list($AutoId,$fila)=each($Datos))
		{
			//echo $AutoId;
			$CantidadFinal=$VrSaldoIni[$AutoId][0]+$VrEntradas[$AutoId][0]-$VrSalidas[$AutoId][0]+$VrDevoluciones[$AutoId][0];
			$x=0;
			foreach($fila as $col)
			{
				$POSY=$this->GetY();
				if($x==1){$col=utf8_decode(substr($col,0,$Sub));}
				//if($x>1){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				//if($col=="TOTALES"){$Final=1;$Alinea="R";}
				$this->Cell($Anchos[$x],10,utf8_decode(strtoupper($col)),1,0,$Alinea,$fill);
				$x++;
			}
			$this->Cell($Anchos[$x],10,"",1,0,$Alinea,$fill); $x++;
			$this->Cell($Anchos[$x],10,"",1,0,$Alinea,$fill); $x++;
                        $this->Cell($Anchos[$x],10,"",1,0,$Alinea,$fill); $x++;
			if($Saldos)
			{
				$this->Cell($Anchos[$x],10,number_format($CantidadFinal,2),1,0,'R',$fill); $x++;
			}
			$this->Ln();
			$fill=!$fill;
		}
	}

    function Header()
    {
        global $Compania;global $Anio;global $MesIni;global $DiaIni;global $MesFin;global $DiaFin; global $Saldos;
        $this->SetFont('Arial','B',10);
        $this->Cell(0,8,strtoupper($Compania[0]),0,0,'C');
        $this->Ln(4);
        $this->SetFont('Arial','B',8);
        $this->Cell(0,8,strtoupper($Compania[1]),0,0,'C');
        $this->Ln(4);
        $this->Cell(0,8,"CONTEO DE PRODUCTOS INVENTARIO",0,0,'C');
        $this->Ln(4);
        $this->Cell(0,8,"PERIODO: $Anio-$MesIni-$DiaIni a $Anio-$MesFin-$DiaFin",0,0,'C');
        $this->Ln(10);
        $this->SetFillColor(228,228,228);
        $this->SetFont('Arial','B',8);
        $this->Cell(10,5,"Codigo",1,0,'C',1);
        if(!$Saldos){$A = "130";}
        else{$A = "110";}
        $this->Cell($A,5,"Nombre",1,0,'C',1);
        $this->Cell(20,5,"Bodega",1,0,'C',1);
        $this->Cell(20,5,"Estante",1,0,'C',1);
        $this->Cell(20,5,"Nivel",1,0,'C',1);
        $this->Cell(20,5,"Conteo 1",1,0,'C',1);
        $this->Cell(20,5,"Conteo 2",1,0,'C',1);
        $this->Cell(20,5,"Conteo 3",1,0,'C',1);
        if($Saldos)
        {
                $this->Cell(20,5,"Saldo",1,0,'C',1);
        }
        $this->Ln(5);
    }

    function Footer()
    {
        global $ND;
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
        $this->Ln(3);
        $this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
    }
}

$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$pdf->BasicTable($Datos);
//$pdf->Cell(260,0,"","B",0,'C');
$pdf->Ln(20);
$pdf->Cell(0,8,"____________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(0,8,"RESPONSABLE",0,0,'C');
$pdf->Ln(5);
$pdf->Output();
?>



