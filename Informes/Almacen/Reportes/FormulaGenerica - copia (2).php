<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
//UM:09-05-2011
//UM:03-05-2011
require('LibPDF/rotation.php');
$ND = getdate();
if($ND[mon]<10){$Mes = "0".$ND[mon];}else{$Mes = $ND[mon];}
if($ND[mday]<10){$Dia = "0".$ND[mday];}else{$Dia = $ND[mday];}
////////////////DATOS DE LOS ASEGURADORES X PACIENTE////////////
    $cons = "Select Servicios.Cedula,PrimApe,PagadorxServicios.Entidad,CodigoSGSSS,TipoAsegurador,
    Contratos.Contrato,Numero,PlanesTarifas.NombrePlan,Planeservicios.NombrePlan
    From Salud.Servicios, Salud.PagadorxServicios, Central.Terceros, ContratacionSalud.Contratos,
    ContratacionSalud.PlanesTarifas, ContratacionSalud.Planeservicios
    Where Servicios.NumServicio = PagadorxServicios.NumServicio and PagadorxServicios.Entidad = Terceros.Identificacion
    and Contratos.Entidad = Terceros.Identificacion and contratos.Plantarifario = PlanesTarifas.Autoid
    and contratos.PlanBeneficios = Planeservicios.Autoid and PlanesTarifas.Compania='$Compania[0]'
    and Planeservicios.Compania = '$Compania[0]' and Servicios.Compania='$Compania[0]'
    and PagadorxServicios.Compania='$Compania[0]' and Terceros.Compania = '$Compania[0]' and Contratos.Compania='$Compania[0]'";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $Asegurador[$fila[0]]=array($fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],);
    }
////////////////////////////////////////////////////////////////
///////////////DATOS DE LOS MEDICOS//////////////////
     $cons = "Select Medicos.Usuario,Nombre,Cedula,RM,Especialidad from
     salud.Medicos, Central.Usuarios Where Compania='$Compania[0]'
     and Medicos.Usuario = Usuarios.Usuario";
     $res = ExQuery($cons);
     while($fila = ExFetch($res))
     {
         $Medico[$fila[0]] = array($fila[1],$fila[2],$fila[3],$fila[4]);
     }
////////////////////////////////////////////////////////////////////
if($Urgentes)
{
    $TipoMed = "Medicamento Urgente";
    $Comprobante = "Salidas Urgentes";
}
else
{
    $TipoMed = "Medicamento Programado";
    $Comprobante = "Salidas por Plantilla";
}
$cons = "Select CedPaciente,AutoidProd,NumOrden,IdEscritura,NumServicio,CantDiaria,Posologia,Usuario,ViaSuministro,
NombreProd1,UnidadMedida,Presentacion From Salud.PlantillaMedicamentos,Consumo.CodProductos
Where TipoMedicamento = '$TipoMed' and Autoid = AutoidProd and PlantillaMedicamentos.Compania='$Compania[0]'
and CodProductos.Compania='$Compania[0]' and PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal'
and CodProductos.AlmacenPpal = '$AlmacenPpal' and CodProductos.Anio = '$ND[year]'";
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
    if($TarjetaMeds[$fila[0]][$fila[1]])
    {
        $Datos[$fila[0]][$fila[1]][$fila[2]][$fila[3]] = array($fila[9],$fila[10],
                                                               $fila[11],$fila[4],
                                                               $fila[5],$fila[6],
                                                               $Medico[$fila[7]],$fila[8]);
    }
}
if($Despachados)
{
    $cons = "Select Cedula,Autoid,NumOrden,IdEscritura,Numero,Cantidad,UsuarioCre from Consumo.Movimiento
    Where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Comprobante = '$Comprobante'";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        if($Datos[$fila[0]][$fila[1]][$fila[2]][$fila[3]])
        {
            array_push($Datos[$fila[0]][$fila[1]][$fila[2]][$fila[3]],$fila[4],$fila[5],$fila[6]);
        }
    }
}

////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
class PDF extends PDF_Rotate
{
    function Header1($Ini)
    {
            $this->AddPage();
            global $Compania; global $Tipo;
            $Raiz = $_SERVER['DOCUMENT_ROOT'];
            $this->Image("$Raiz/Imgs/Logo.jpg",10,7,20,20);
            $this->SetFont('Arial','B',12);
            $this->Cell(25,5,"",0,0,'L');
            $this->Cell(130,5,utf8_decode(substr(strtoupper($Compania[0]),0,50)),0,0,'L');
            $this->SetFont('Arial','B',10);
            $this->SetFillColor(228,228,228);
            if($Tipo=="Si"){$Tit="FORMULA DE MEDICAMENTOS DE CONTROL";}
            else{$Tit="FORMULA DE MEDICAMENTOS";}
            $Tamano=$this->GetStringWidth(" $IdEscritura ");
            if($this->GetStringWidth(" $IdEscritura ")<$this->GetStringWidth(" No. ORDEN ")){
                    $Tamano=$this->GetStringWidth($Tit);
            }
            $this->Cell($Tamano+1,5,$Tit,"LRTB",0,'C',1);
            $this->SetFont('Arial','',8);
            $this->Ln(4);
            $this->Cell(25,5,"",0,0,'L');
            $this->Cell(130,5,strtoupper($Compania[1]),0,0,'L');
            $this->SetFont('Arial','B',16);
            $this->Cell($Tamano+1,10," $Numero ","LRB",0,'C');
            $this->SetFont('Arial','',8);
            $this->Ln(4);
            $this->Cell(25,5,"",0,0,'L');
            $this->Cell(130,5,"CODIGO SGSSS ".strtoupper($Compania[17]),0,0,'L');
            $this->Ln(4);
            $this->SetFont('Arial','',8);
            $this->Cell(25,5,"",0,0,'L');
            $this->Cell(130,5,$Compania[2]." - TELEFONOS: ".strtoupper($Compania[3]),0,0,'L');
            if($Ini==1){$this->Ln(8);}
            else{$this->Ln(12);}
    }
    function Header2()
    {
        //$this->AddPage();
        //DATOS CLIENTE
        global $Cliente; global $PlanMeds; global $PlanServ;
        $this->SetFont('Arial','B',8);
        $this->Cell(5,5,"",0,0,'L');
        $this->Cell(25,5,"ASEGURADOR:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(0,5,strtoupper(utf8_decode($Cliente[0])),0,0,'L');
        $this->Ln(4);
        $this->SetFont('Arial','B',8);
        $this->Cell(5,5,"",0,0,'L');
        $this->Cell(25,5,"NIT:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(55,5,$Cliente[1],0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(24,5,"CODIGO SGSSS:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(27,5,$Cliente[7],0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(19,5,"REGIMEN:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(30,5,strtoupper($Cliente[6]),0,0,'L');
        $this->Ln(4);
        $this->Cell(5,5,"",0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(25,5,"CONTRATO:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(55,5,substr(strtoupper($Cliente[2]),0,28),0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(24,5,"No CONTRATO:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(27,5,strtoupper($Cliente[3]),0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(19,5,"PLAN MEDS:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(27,5,strtoupper($PlanMeds[0]),0,0,'L');
        $this->Ln(4);
        $this->Cell(5,5,"",0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(25,5,"DIRECCION:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(55,5,substr($Cliente[4],0,38),0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(24,5,"TELEFONO:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(27,5,$Cliente[5],0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(19,5,"PLAN SERVS:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(27,5,utf8_decode(strtoupper($PlanServ[0])),0,0,'L');
        //$this->Rect(20,35,182,17);	//RECTANGULO CLIENTE
        $this->Ln(8);

        //DATOS DatPaciente
        global $DatPaciente;
        $this->Cell(5,5,"",0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(35,5,"APELLIDOS Y NOMBRES:",0,0,'L');
        $this->SetFont('Arial','',8);
        $NomPac=strtoupper(utf8_decode($DatPaciente[1]));
        $this->Cell(96,5,substr($NomPac,0,52),0,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(29,5,"IDENTIFICACION:",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(20,5,$DatPaciente[0],0,0,'L');
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
    function BasicTable($Medicamentos)
    {
        while(list($Identificacion,$Medicamentos2) = each($Medicamentos))
        {
            $this->Header1(1);
            $this->Header2();
        }
    }
}
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',8);
$pdf->BasicTable($Datos);
$pdf->Output();
?>