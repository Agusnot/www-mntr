<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
require('LibPDF/rotation.php');
$ND=getdate();
$nocodi = explode("|",$AutoId);
if(count($nocodi)==1)
{
    $cons = "Select CodElementos.Codigo,Nombre,Caracteristicas,Marca,Modelo,Serie,Impacto,FechaAdquisicion,
    PrimApe,SegApe,PrimNom,SegNom,CentrosCosto.CentroCostos,CentrosCosto.Codigo,SubUbicacion,Grupo
    from Infraestructura.Ubicaciones, Infraestructura.CodElementos, Central.Terceros, Central.CentrosCosto
    Where Ubicaciones.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
    and CentrosCosto.Anio = $ND[year] and Ubicaciones.CentroCostos = CentrosCosto.Codigo and Ubicaciones.AutoId = CodElementos.AutoId
    and Ubicaciones.Responsable = Terceros.Identificacion and Ubicaciones.AutoId=$AutoId order by FechaFin desc";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Codigo = $fila[0];
    $Nombre = $fila[1];$Caracteristicas=$fila[2];$Marca=$fila[3];$Modelo=$fila[4];$Serie=$fila[5];$Impacto=$fila[6];$FechaAd=$fila[7];
    $Responsable = strtoupper("$fila[8] $fila[9] $fila[10] $fila[11]");$Grupo = $fila[15];
    $Ubicacion = strtoupper("$fila[12] - $fila[13]");
    $SubUbicacion = $fila[14];
}
else
{
    $Nombre=$nocodi[1];
    $cons = "select primape,segape,primnom,segnom from Central.Terceros Where Compania='$Compania[0]' and Identificacion='$nocodi[0]'";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Responsable = "$fila[0] $fila[1] $fila[2] $fila[3]";
    $cons="Select CentroCostos from Central.CentrosCosto Where Compania='$Compania[0]' and Anio=$ND[year] and Codigo='$nocodi[2]'";
    $res = ExQuery($cons);
    $fila=ExFetch($res);
    $Ubicacion="$nocodi[2] - $fila[0]";
    $SubUbicacion = $nocodi[3];
    $AutoId=0;
    $consWhere = " and Descripcion = '$nocodi[0]' and Tercero ='$nocodi[2]' and CC='$nocodi[1]' and SubUbicacion='$nocodi[3]'";
}
$cons = "Select FechaSolicitud,UsuarioSolicitud,DetalleSolicitud,EstadoSolicitud,FechaAr,UsuarioAr,Encargado,FechUltRev,ClaseMantenimiento,TipoMantenimiento,
TotCosto,FechEvaluacion,Evaluacion,FechaCierreCaso,NotaCierre,NotaRechazo,Motivo From
Infraestructura.Mantenimiento Where Mantenimiento.Compania='$Compania[0]' and Agendado = 0 and AutoId=$AutoId $consWhere order by FechaSolicitud";
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
	$Solicitud[$fila[0]] = array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],
								$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16]);    
}
$cons = "Select usuario,primape,segape,primnom,segnom from Infraestructura.ResponsablesMantenimiento, Central.Terceros
Where Terceros.Compania='$Compania[0]' and ResponsablesMantenimiento.Compania='$Compania[0]' 
and Terceros.Identificacion = ResponsablesMantenimiento.Usuario group by Usuario,primape,segape,primnom,segnom";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
	$Encargado[$fila[0]]=strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]");
}
class PDF extends PDF_Rotate
{
    function BasicTable($Solicitud)
    {
		global $Encargado;
		
		$this->SetFillColor(228,228,228);
		$this->Cell(0,5,"MANTENIMIENTOS CORRECTIVOS",1,0,'C',1);
		$this->Ln(6);
		foreach($Solicitud as $filaMant)
		{
			$this->Cell(28,5,"Fecha Solicitud",1,0,'R',1);
			$this->Cell(30,5,$filaMant[0],1,0,'L');
			$this->Cell(6,5,"Por",1,0,'L',1);
			$this->Cell(30,5,$filaMant[1],1,0,'L');
			$this->Cell(12,5,"Detalle",1,0,'L',1);
			$this->Cell(0,5,substr($filaMant[2],0,100),1,0,'L');
			$this->Ln(6);
			/////////////////Verificar final de pagina//////////////////////////
			$this->Cell(28,5,"Estado",1,0,'R',1);
			$this->Cell(18,5,$filaMant[3],1,0,'L');
			if($filaMant[3]=="Solicitado")
			{
				$this->Cell(0,5,"",1,0,'L');
				$this->Ln(12);
			}//TERMINA
			if($filaMant[3]=="Rechazado")
			{
				$this->Cell(10,5,"Motivo",1,0,'L',1);
				$this->Cell(40,5,$filaMant[16],1,0,'L');
				$this->Cell(25,5,"Nota Rechazo",1,0,'L',1);
				$this->Cell(25,5,$filaMant[15],1,0,'L');
				$this->Ln(12);//TERMINA
			}
			if($filaMant[3]=="Aprobado" || $filaMant[3]=="Revisado" || $filaMant[3]=="Evaluado" || $filaMant[3]=="Cerrado")
			{
				$this->Cell(15,5,"Aprobado",1,0,'R',1);
				$this->Cell(30,5,$filaMant[4],1,0,'L');
				$this->Cell(6,5,"Por",1,0,'L',1);
				$this->Cell(25,5,$filaMant[5],1,0,'L');
				if($filaMant[6])
				{
					$this->Cell(15,5,"Encargado",1,0,'L',1);
					$this->Cell(0,5,utf8_decode($Encargado[$filaMant[6]]),1,0,'L');
					$this->Ln(6);
				}
				else
				{
					$this->Ln(12);//TERMINA
				}
				if($filaMant[3]=="Revisado" || $filaMant[3]=="Evaluado" || $filaMant[3]=="Cerrado")
				{
					$this->Cell(28,5,"Ultima Revision",1,0,'R',1);
					$this->Cell(30,5,$filaMant[7],1,0,'L');
					$this->Cell(28,5,"Clase Mantenimiento",1,0,'R',1);
					$this->Cell(30,5,$filaMant[8],1,0,'L');
					$this->Cell(28,5,"Tipo Mantenimiento",1,0,'R',1);
					$this->Cell(30,5,$filaMant[9],1,0,'L');
					$this->Cell(28,5,"Costo",1,0,'R',1);
					$this->Cell(30,5,number_format($filaMant[10],2),1,0,'R');
					$this->Cell(0,5,"",1,0,'L',1);
					$this->Ln(6);
					if($filaMant[3]=="Evaluado" || $filaMant[3]=="Cerrado")
					{
						$this->Cell(28,5,"Fecha Evaluacion",1,0,'R',1);
						$this->Cell(30,5,$filaMant[11],1,0,'L');
						$this->Cell(28,5,"Evaluacion",1,0,'R',1);
						$this->Cell(30,5,$filaMant[12],1,0,'L');
						if($filaMant[3]=="Cerrado")
						{
							$this->Cell(28,5,"Fecha cierre",1,0,'R',1);
							$this->Cell(30,5,$filaMant[13],1,0,'L');
							$this->Cell(28,5,"Nota cierre",1,0,'R',1);
							$this->Cell(0,5,substr($filaMant[14],0,40),1,0,'L');
							$this->Ln(12);//TERMINA
						}
						else
						{
							$this->Ln(12);//TERMINA
						}
					}
				}
				else
				{
					$this->Ln(6);//TERMINA
				}
			}
		}
	}

    function Header()
    {
        global $Compania;global $ND;global $nocodi;
        global $DatosElemento; global $Estado;
        $Codigo = $fila[0];
        global $Nombre,$Caracteristicas,$Marca,$Modelo,$Serie,$Impacto,$FechaAd,$Responsable,$Ubicacion,$SubUbicacion,$Grupo,$Codigo;

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
        $this->Cell(0,5,"HISTORIAL DE MANTENIMIENTOS",0,0,'C');
        $this->Ln(15);
        $this->SetFillColor(228,228,228);
        $this->SetFont('Arial','B',8);
        if(count($nocodi)==1)
        {
            $this->Cell(35,5,"",0,0,'C');
            $this->Cell(15,5,"Codigo",1,0,'R',1);
            $this->SetFont('Arial','',8);
            $this->Cell(25,5,$Codigo,1,0,'L');
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,"Fecha adquisicion",1,0,'C',1);
            $this->SetFont('Arial','',8);
            $this->Cell(20,5,$FechaAd,1,0,'L');
            $this->SetFont('Arial','B',8);
            $this->Cell(15,5,"Grupo",1,0,'C',1);
            $this->SetFont('Arial','',8);
            $this->Cell(40,5,utf8_decode(substr($Grupo,0,25)),1,0,'L');
            $this->SetFont('Arial','B',8);
            $this->Cell(20,5,"Impacto",1,0,'C',1);
            $this->SetFont('Arial','',8);
            $this->Cell(25,5,$Impacto,1,0,'L');
            $this->Ln(6);
            $this->Cell(35,5,"",0,0,'C');
            $this->SetFont('Arial','B',8);
            $this->Cell(15,5,"Nombre",1,0,'R',1);
            $this->SetFont('Arial','',8);
            $this->Cell(75,5,utf8_decode(substr($Nombre,0,30)),1,0,'L');
            $this->SetFont('Arial','B',8);
            $this->Cell(25,5,"Caracteristicas",1,0,'C',1);
            $this->SetFont('Arial','',8);
            $this->Cell(75,5,$Caracteristicas,1,0,'L');
            $this->Ln(6);
            $this->Cell(35,5,"",0,0,'C');
            $this->SetFont('Arial','B',8);
            $this->Cell(15,5,"Modelo",1,0,'R',1);
            $this->SetFont('Arial','',8);
            $this->Cell(27,5,$Modelo,1,0,'L');
            $this->SetFont('Arial','B',8);
            $this->Cell(20,5,"Estado",1,0,'C',1);
            $this->SetFont('Arial','',8);
            $this->Cell(28,5,$Estado,1,0,'L');
            $this->SetFont('Arial','B',8);
            $this->Cell(20,5,"Serie",1,0,'C',1);
            $this->SetFont('Arial','',8);
            $this->Cell(27,5,$Serie,1,0,'L');
            $this->SetFont('Arial','B',8);
            $this->Cell(24,5,"Marca",1,0,'C',1);
            $this->SetFont('Arial','',8);
            $this->Cell(29,5,$Marca,1,0,'L');
            $this->Ln(6);
        }
        else
        {
            $this->Cell(35,5,"",1,0,'C');
            $this->SetFont('Arial','B',8);
            $this->Cell(25,5,"Descripcion",1,0,'C',1);
            $this->SetFont('Arial','',8);
            $this->Cell(165,5,$Nombre,1,0,'C');
            $this->Ln(6);
        }
        $this->Cell(35,5,"",0,0,'C');
        $this->SetFont('Arial','B',8);
        $this->Cell(90,5,"Responsable",1,0,'C',1);
        $this->Cell(50,5,"Ubicacion",1,0,'C',1);
        $this->Cell(50,5,"Sub Ubicacion",1,0,'C',1);
        $this->Ln();
        $this->Cell(35,5,"",0,0,'C');
        $this->SetFont('Arial','',8);
        $this->Cell(90,5,utf8_decode($Responsable),1,0,'C');
        $this->Cell(50,5,utf8_decode($Ubicacion),1,0,'C');
        $this->Cell(50,5,utf8_decode(substr($SubUbicacion,0,30)),1,0,'C');
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
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->SetDrawColor(228,228,228);
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$pdf->BasicTable($Solicitud);
$pdf->Ln(20);
$pdf->Output();
?>