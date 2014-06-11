<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
require('LibPDF/rotation.php');
$ND=getdate();
$nocodi = explode("|",$AutoId);
if(count($nocodi)==1)
{
    $cons = "Select CodElementos.Codigo,Nombre,Caracteristicas,Marca,Modelo,Serie,
    PrimApe,SegApe,PrimNom,SegNom,CentrosCosto.CentroCostos,CentrosCosto.Codigo,SubUbicacion
    from Infraestructura.Ubicaciones, Infraestructura.CodElementos, Central.Terceros, Central.CentrosCosto
    Where Ubicaciones.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
    and CentrosCosto.Anio = $ND[year] and Ubicaciones.CentroCostos = CentrosCosto.Codigo and Ubicaciones.AutoId = CodElementos.AutoId
    and Ubicaciones.Responsable = Terceros.Identificacion and Ubicaciones.AutoId=$AutoId order by FechaFin desc";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Codigo = $fila[0];
    $Nombre = "$fila[1] $fila[2] $fila[3] $fila[4] $fila[5]";
    $Responsable = "$fila[6] $fila[7] $fila[8] $fila[9]";
    $Ubicacion = "$fila[10] - $fila[11]";
    $SubUbicacion = $fila[12];
}
else
{
    $Codigo="";
    $Nombre=$nocodi[0];
    $cons = "select primape,segape,primnom,segnom from Central.Terceros Where Compania='$Compania[0]' and Identificacion='$nocodi[2]'";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Responsable = "$fila[0] $fila[1] $fila[2] $fila[3]";
    $cons="Select CentroCostos from Central.CentrosCosto Where Compania='$Compania[0]' and Anio=$ND[year] and Codigo='$nocodi[1]'";
    $res = ExQuery($cons);
    $fila=ExFetch($res);
    $Ubicacion="$nocodi[1] - $fila[0]";
    $SubUbicacion = $nocodi[3];
    $AutoId=0;
    $consWhere = " and Descripcion = '$nocodi[0]' and Tercero ='$nocodi[2]' and CC='$nocodi[1]' and SubUbicacion='$nocodi[3]'";
}
$DatosElemento = array($Codigo,$Nombre,$Responsable,$Ubicacion,$SubUbicacion);

$cons = "Select EstadoSolicitud,FechaSolicitud,DetalleSolicitud,Encargado,FechUltRev,ClaseMantenimiento,TipoMantenimiento,
RepTecnico,TraRealizado,Observaciones,Repuestos,TotCosto,HoraIni,Duracion,Evaluacion,FechEvaluacion,FechaCierreCaso,NotaCierre,UsuarioSolicitud from
Infraestructura.Mantenimiento Where Mantenimiento.Compania='$Compania[0]' 
and FechaSolicitud='$FechaSolicitud' and AutoId=$AutoId $consWhere";
$res = ExQuery($cons);
$fila = ExFetch($res);
$DatosMantenimiento = array($fila[0],$fila[1],$fila[2],$fila[3], $fila[4], $fila[5], $fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],
$fila[13],$fila[14],$fila[15],$fila[16],$fila[17]);
$Estado=$fila[0];
$USS = $fila[18];
if($DatosMantenimiento[3])
{
    $Encargado = 1;
    $cons = "select primape,segape,primnom,segnom from Central.Terceros Where Compania='$Compania[0]' and Identificacion='$DatosMantenimiento[3]'";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $DatosMantenimiento[3] = "$fila[0] $fila[1] $fila[2] $fila[3]";
}
class PDF extends PDF_Rotate
{
    function BasicTable($data)
    {
        $this->SetFont('Arial','B',8);
        $this->Cell(30,5,"Fecha solicitud:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(30,5,utf8_decode($data[1]),0,0,'L');
        $this->Ln();
        $this->SetFont('Arial','B',8);
        $this->Cell(30,5,"Detalle solicitud:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(165,5,utf8_decode($data[2]),0,0,'L');
        $this->Ln();
        if($data[3])
        {
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,"Encargado Mant.:",0,0,'L');
            $this->SetFont('Arial','',8);
            $this->Cell(70,5,utf8_decode($data[3]),0,0,'L');
            if(!$data[4]){$borde=1;}else{$borde = 0;}
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,"Ultima revision:",0,0,'L');
            $this->SetFont('Arial','',8);
            $this->Cell(65,5,utf8_decode($data[4]),$borde,'L');
            $this->Ln();
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,"Clase mantenimiento:",0,0,'L');
            $this->SetFont('Arial','',8);
            $this->Cell(70,5,utf8_decode($data[5]),$borde,0,'L');
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,"Tipo mantenimiento:",0,0,'L');
            $this->SetFont('Arial','',8);
            $this->Cell(65,5,utf8_decode($data[6]),$borde,0,'L');
            $this->Ln(10);
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,"Reporte Tecnico:",0,0,'L');
            $this->SetFont('Arial','',8);
            $this->Ln();
            $this->MultiCell(0, 10, $data[7], $borde, 'J',0);
            $this->Ln(1);
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,"Trabajo realizado:",0,0,'L');
            $this->SetFont('Arial','B',8);
            $this->Ln();
            $this->MultiCell(0, 10, $data[8], $borde, 'J',0);
            $this->Ln(1);
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,"Observaciones:",0,0,'L');
            $this->SetFont('Arial','',8);
            $this->Ln();
            $this->MultiCell(0, 10, $data[9], $borde, 'J',0);
            $this->Ln(1);
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,"Repuestos:",0,0,'L');
            $this->SetFont('Arial','',8);
            $this->Ln();
            $this->MultiCell(0, 10, $data[10], $borde, 'J',0);
            $this->Ln(1);
            $this->SetFont('Arial','B',8);
            $this->Cell(65,5,"TIEMPO",0,0,'C');
            $this->SetFont('Arial','',8);
            if($data[0]=="Evaluado" || $data[0]=="Cerrado")
            {
                $this->SetFont('Arial','B',8);
                $this->Cell(65,5,"EVALUACION",0,0,'C');
                $this->SetFont('Arial','',8);
                if($data[0]=="Cerrado")
                {
                    $this->SetFont('Arial','B',8);
                    $this->Cell(65,5,"NOTA CIERRE",0,0,'C');
                    $this->SetFont('Arial','',8);
                    $this->Ln();
                }
                else{$this->Ln();}
            }
            else{$this->Ln();}
            $this->SetFont('Arial','B',8);
            $this->Cell(65,5,"Hora Inicial",0,0,'C');
            $this->SetFont('Arial','',8);
            if($data[0]=="Evaluado" || $data[0]=="Cerrado")
            {
                $this->Cell(10,5,"",0,0,'L');
                $this->Cell(50,5,"Excelente",0,0,'L');
                if($data[14]=="Excelente"){$this->Cell(5,5,"X",1,0,'C');}
                else{$this->Cell(5,5,"",1,0,'C');}
                if($data[0]=="Cerrado")
                {
                    $this->Cell(5,5,"",0,0,'L');
                    $this->MultiCell(55, 20, $data[17], $borde, 'J',0);
                    $this->Cell(5,5,"",0,0,'L');
                    $data[0]="Evaluado";
                    $this->setY(($this->getY()) - 15);
                    $this->Ln(0);
                }
                else{$this->Ln();}
            }
            else{$this->Ln();}
            if($data[12])
            {
                $Hora=explode(":",$data[12]);
                if($Hora[0]>12){$ampm="PM";$HoraIni=$Hora[0] - 12;}else{$HoraIni=$Hora[0];$ampm="AM";}
            }
            $this->Cell(29,5,$HoraIni,$borde,0,'R');
            $this->Cell(2,5,":",0,0,'R');
            $this->Cell(24,5,$Hora[1],$borde,0,'L');
            $this->Cell(10,5,$ampm,$borde,0,'L');
            if($data[0]=="Evaluado" || $data[0]=="Cerrado")
            {
                $this->Cell(10,5,"",0,0,'L');
                $this->Cell(50,5,"Bueno",0,0,'L');
                if($data[14]=="Bueno"){$this->Cell(5,5,"X",1,0,'C');}
                else{$this->Cell(5,5,"",1,0,'C');}
                if($data[0]=="Cerrado")
                {
                    $this->Cell(65,5,"X",0,0,'C');
                    $this->Ln();
                }
                else{$this->Ln();}
            }
            else{$this->Ln();}
            $this->SetFont('Arial','B',8);
            $this->Cell(65,5,"DURACION",0,0,'C');
            $this->SetFont('Arial','',8);
            if($data[0]=="Evaluado" || $data[0]=="Cerrado")
            {
                $this->Cell(10,5,"",0,0,'L');
                $this->Cell(50,5,"Regular",0,0,'L');
                if($data[14]=="Regular"){$this->Cell(5,5,"X",1,0,'C');}
                else{$this->Cell(5,5,"",1,0,'C');}
                if($data[0]=="Cerrado")
                {
                    $this->Cell(65,5,"X",0,0,'C');
                    $this->Ln();
                }
                else{$this->Ln();}
            }
            else{$this->Ln();}
            $this->Cell(55,5,$data[13],$borde,0,'C');
            $this->Cell(10,5,"Mins.",0,0,'R');
            if($data[0]=="Evaluado" || $data[0]=="Cerrado")
            {
                $this->Cell(10,5,"",0,0,'L');
                $this->Cell(50,5,"Malo",0,0,'L');
                if($data[14]=="Malo"){$this->Cell(5,5,"X",1,0,'C');}
                else{$this->Cell(5,5,"",1,0,'C');}
                if($data[0]=="Cerrado")
                {
                    $this->Cell(65,5,"X",0,0,'C');
                    $this->Ln();
                }
                else{$this->Ln();}
            }
            else{$this->Ln();}
        }

    }

    function Header()
    {
        global $Compania;global $Anio;global $ND;global $FechaSolicitud;
        global $DatosElemento; global $Estado;
        if($Estado != "Rechazado")
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
        $this->SetFont('Arial','B',10);
        $this->Cell(0,5,strtoupper($Compania[0]),0,0,'C');
        $this->SetFont('Arial','',8);
        $this->Ln(4);
        $this->Cell(0,5,strtoupper($Compania[1]),0,0,'C');
        $this->Ln(4);
        $this->Cell(0,5,"$Compania[2] - $Compania[3]",0,0,'C');
        $this->Ln(4);
        $this->Cell(0,5,"FORMATO DE MANTENIMIENTO",0,0,'C');
        $this->Ln(15);
        $this->SetFillColor(228,228,228);
        $this->SetFont('Arial','B',8);
        $this->Cell(45,5,"Codigo",1,0,'C',1);
        $this->Cell(150,5,"Elemento",1,0,'C',1);
        $this->Ln();
        //$this->SetFillColor(255,255,255);
        $this->SetFont('Arial','',8);
        $this->Cell(45,5,utf8_decode($DatosElemento[0]),1,0,'C');
        $this->Cell(150,5,utf8_decode($DatosElemento[1]),1,0,'C');
        $this->Ln();
        $this->SetFillColor(228,228,228);
        $this->SetFont('Arial','B',8);
        $this->Cell(90,5,"Responsable",1,0,'C',1);
        $this->Cell(50,5,"Ubicacion",1,0,'C',1);
        $this->Cell(55,5,"Sub Ubicacion",1,0,'C',1);
        $this->Ln();
        //$this->SetFillColor(255,255,255);
        $this->SetFont('Arial','',8);
        $this->Cell(90,5,utf8_decode($DatosElemento[2]),1,0,'C');
        $this->Cell(50,5,utf8_decode($DatosElemento[3]),1,0,'C');
        $this->Cell(55,5,utf8_decode(substr($DatosElemento[4],0,30)),1,0,'C');
        $this->Ln(10);
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
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
if($Estado == "Rechazado")
{
	$pdf->SetFont('Arial','B',90);
	$pdf->SetTextColor(0,0,0);
	$pdf->Rotate(45,1,150);
	$pdf->Text(20,200,strtoupper($Estado));
	$pdf->SetTextColor(0,0,0);
	$pdf->Rotate(0);
}
$pdf->SetFont('Arial','',8);
$pdf->BasicTable($DatosMantenimiento);
$pdf->Ln(20);
$pdf->Cell(65,8,"____________________________________",0,0,'C');
if($Encargado)
{
    $valor="____________________________________";
    $valor1 = "Encargado";
}
else
{
    $valor=""; $valor1="";
}
$pdf->Cell(65,8,$valor,0,0,'C');
$pdf->Cell(65,8,"____________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(65,8,"Solicita",0,0,'C');
$pdf->Cell(65,8,$valor1,0,0,'C');
$pdf->Cell(65,8,"Coord.Mantenimiento",0,0,'C');
$pdf->Ln(4);
$pdf->Cell(65,8,$USS,0,0,'C');
$pdf->Cell(65,8,$DatosMantenimiento[3],0,0,'C');
//$pdf->Cell(65,8,"Coord.Mantenimiento",0,0,'C');
$pdf->Output();
?>
