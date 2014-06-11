<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	ini_set("memory_limit","1024M");
	include("Informes.php");
	require('LibPDF/rotation.php');
	$ND=getdate();
        $cons = "Select AutoId,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos,SubUbicacion,Responsable,PrimApe,SegApe,PrimNom,SegNom
        from Infraestructura.Ubicaciones,Central.CentrosCosto,Central.Terceros
        Where Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
        and CentrosCosto.Anio=$Anio and Ubicaciones.CentroCostos = CentrosCosto.Codigo and Ubicaciones.Responsable = Terceros.Identificacion
        order by AutoId,FechaFin asc";
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            $Ubicacion[$fila[0]] = array("$fila[1] - $fila[2]",$fila[3],"$fila[4] - $fila[5] $fila[6] $fila[7] $fila[8]");
            //echo "$fila[1] - $fila[2]";
        }
	$cons = "Select TextoActa,Bajas.AutoId,CodElementos.Codigo,Nombre,Caracteristicas,Modelo,Serie,Marca,
	CodElementos.Estado,Fecha, Bajas.UsuarioCrea, Bajas.UsuarioAR,Bajas.Estado,NotaBaja
	From Infraestructura.Bajas,Infraestructura.CodElementos
	Where Bajas.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' and Bajas.AutoId = CodElementos.AutoId and Numero='$Numero'";
	$res=ExQuery($cons);
	$contBajas = 0;
	$NumRec = 0;
	while($fila=ExFetch($res))
	{
            $Solicito = $fila[10];
            $Estado=$fila[12];if($Estado=="Aprobado" || $Estado=="Ejecutado"){$Aprobo = $fila[11];}
            $H = $fila[0];
            $Fecha = $fila[9];
            //echo $Ubicacion[$fila[1]][0]."<br>";
            $Datos[$NumRec] = array($fila[2],"$fila[3] $fila[4]",$fila[5],$fila[6],$fila[7],$fila[8],$Ubicacion[$fila[1]][0],$Ubicacion[$fila[1]][1],$Ubicacion[$fila[1]][2]);
            $NumRec++;
            if($fila[13])
            {
                $N[$contBajas] = "$fila[2] : ".utf8_decode($fila[13]);
                $contBajas++;
            }
	}
        
	
class PDF extends PDF_Rotate
{//192,168.1.110
	function BasicTable($data)
	{
		if(!$data){echo "no data";exit;}
		global $SubTotal; global $IVA; global $Total;
                $Anchos=array(20,65,25,20,25,20,25,25,35);
		$fill=false;$this->SetFillColor(248,248,248);
		foreach($data as $row)
		{
			$x = 0;
			foreach($row as $col)
			{
				$this->SetFont('Arial','',7);
				$POSY=$this->GetY();
				if($x==1){$col=substr($col,0,38);}
				if($x>=2&&$x<6){$col=substr($col,0,10);}
                                if($x==6 || $x==7){$col=substr(utf8_decode($col),0,16);}
                                if($x==8){$col=substr(utf8_decode($col),0,23);}
				$Lines="LR";
				if($POSY>=185 && $POSY<190){$Lines="LRB";}
				$this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,'L',$fill);
				$x++;	
			}
			$this->Ln();
		}
		$this->Cell(260,5,"",'T',0,'C');
	}

function Header()
{
    global $Compania;global $ND;global $Numero;global $Fecha; global $H; global $F; global $Estado; global $contBajas; global $N;
    if($Estado!="ANULADO" && $Estado!="Rechazado")
    {
            if($Estado=="Solicitado"){$Estado="sin aprobar";}
            $this->SetFont('Arial','B',90);
            $this->SetTextColor(215,215,215);
            $this->Rotate(45,60,190);
            $this->Text(95,190,strtoupper($Estado));
            $this->SetTextColor(0,0,0);
            $this->Rotate(0);
    }
    $Raiz = $_SERVER['DOCUMENT_ROOT'];
    $this->Image("$Raiz/Imgs/Logo.jpg",10,5,30,30);
    $this->SetFont('Arial','B',10);
    $this->Cell(0,5,strtoupper($Compania[0]),0,0,'C');
    $this->SetFont('Arial','',8);
    $this->Ln(4);
    $this->Cell(0,5,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(4);
    $this->Cell(0,5,"$Compania[2] - $Compania[3]",0,0,'C');
    $this->Ln(4);
    $this->Cell(0,5,"ACTA DE BAJA DE ELEMENTOS DEVOLUTIVOS",0,0,'C');

    $this->Ln(5);
    $this->Cell(230,5,"",0,0,'R');
    $this->SetFont('Arial','B',10);
    $this->SetFillColor(228,228,228);
    $this->Cell(30,10,"Numero",0,0,'C',1);
    $this->Ln(5);
    $this->Cell(230,10,"",0,0,'R');
    $this->Ln(5);
    $this->Cell(230,5,"",0,0,'R');
    $this->SetFont('Arial','B',10);
    $this->SetFillColor(255,255,255);
    $this->Cell(30,10,$Numero,0,0,'C');
    $this->SetFont('Arial','',10);
    $this->Ln(10);
    $this->SetFillColor(255,255,255);
    $this->MultiCell(260,5,utf8_decode($H),0,'J');
    $this->Ln(1);
    for($i=0;$i<$contBajas;$i++)
    {
            $this->MultiCell(260,5,utf8_decode($N[$i]),0,'J');
            $this->Ln(1);
    }
    $this->Ln(9);
    $this->SetFillColor(228,228,228);
    $this->SetFont('Arial','B',8);
    $this->Cell(20,5,"Codigo",1,0,'C',1);
    $this->Cell(65,5,"Nombre",1,0,'C',1);
    $this->Cell(25,5,"Modelo",1,0,'C',1);
    $this->Cell(20,5,"Serie",1,0,'C',1);
    $this->Cell(25,5,"Marca",1,0,'C',1);
    $this->Cell(20,5,"Estado",1,0,'C',1);
    $this->Cell(25,5,"Ubicacion",1,0,'C',1);
    $this->Cell(25,5,"SubUbicacion",1,0,'C',1);
    $this->Cell(35,5,"Responsable",1,0,'C',1);
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
$pdf->BasicTable($Datos);
$pdf->SetFont('Arial','',8);
$pdf->Ln(10);
$pdf->SetFont('Arial','I',10);
$pdf->SetFillColor(255,255,255);
$DiaL = substr(NumerosxLet($ND[mday]),0,-15);
$F = "Para constancia se firma a los ".$DiaL." ($ND[mday]) dias del mes de ".$NombreMes[$ND[mon]]." del $ND[year]";
$pdf->MultiCell(260,5,utf8_decode($F),0,'J');
$pdf->SetFont('Arial','',8);
$pdf->Ln(10);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,"____________________________________",0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,"____________________________________",0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,"Solicito",0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,"Aprobo",0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Ln(3);
$pdf->Cell(10,8,"",0,0,'C');
$pdf->Cell(80,8,$Solicito,0,0,'L');
$pdf->Cell(20,8,"",0,0,'C');
$pdf->Cell(80,8,$Aprobo,0,0,'L');
$pdf->Cell(10,8,"",0,0,'C');
if($Estado=="ANULADO" || $Estado=="Rechazado")
{
	$pdf->SetFont('Arial','B',90);
	$pdf->SetTextColor(0,0,0);
	$pdf->Rotate(45,5,150);
	$pdf->Text(35,190,strtoupper($Estado));
	$pdf->SetTextColor(0,0,0);
	$pdf->Rotate(0);	
}
$pdf->Output();
?>