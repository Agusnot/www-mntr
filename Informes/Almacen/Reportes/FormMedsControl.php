<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
include("ObtenerSaldos.php");
require('LibPDF/rotation.php');
$ND = getdate();
/////////////////////////////////DEVOLUCIONES/////////////////////////////////
    $cons = "Select Cedula,NoDocAfectado,DocAfectado,Autoid,Cantidad from Consumo.Movimiento
    Where Comprobante = 'Devoluciones' and TipoComprobante = 'Devoluciones' and Compania='$Compania[0]'
    and AlmacenPpal = '$AlmacenPpal' and Estado = 'AC'";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $Devolucion[$fila[0]][$fila[2]][$fila[1]][$fila[3]] = $fila[4];
//        echo "$fila[0]----$fila[1]----$fila[2]----$fila[3]----$fila[4]<br>";
    }
/////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////
$cons = "Select NomMunicipio from Central.Compania Where Nombre='$Compania[0]'";
$res = ExQuery($cons);
$fila = ExFetch($res);
$CiudadComp = $fila[0];
///////////////////////////////////////////
$cons = "Select Departamento,Codigo from Central.Departamentos";
$res = ExQuery($cons);
while($fila = ExFetch($res)){$D[$fila[1]] = $fila[0];}
/////////////////////////////////////////
$cons = "Select Servicios.Cedula,Entidad,PrimApe,Diagnostico,Medicotte,TipoAsegurador,Nombre,Especialidad,Usuarios.Cedula,RM
from Salud.Servicios, Salud.PagadorxServicios,Central.Terceros, Salud.CIE, Central.Usuarios, Salud.Medicos Where
Servicios.Compania='$Compania[0]' and PagadorxServicios.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
And Servicios.NumServicio=PagadorxServicios.NumServicio and Cie.Codigo = Servicios.DxServ and
Usuarios.Usuario = Servicios.MedicoTte and Usuarios.Usuario=Medicos.Usuario and Servicios.MedicoTte=Medicos.Usuario and
PagadorxServicios.Entidad = Terceros.Identificacion";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $Pagador[$fila[0]] = array($fila[2],$fila[5]);
    $Diagnostigo[$fila[0]] = $fila[3];
    $MedicoTte[$fila[0]] = array($fila[8],$fila[6],$fila[7],$fila[9]);
}
/////////////////////////////////////////
$cons = "Select Identificacion,PrimApe,SegApe,PrimNom,SegNom,TipoDoc,fecnac,Sexo,Telefono,Municipio,Direccion,Departamento
from Central.Terceros Where Compania='$Compania[0]' and Tipo='Paciente'";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $Edad = ObtenEdad($fila[6]);
    if($fila[11]*1 != 0){$Departamento = $D[$fila[11]];}
    $DatPaciente[$fila[0]] = array($fila[1],$fila[2],$fila[3],$fila[4],$fila[5],
                                   $Edad,$fila[7],$fila[8],$fila[9],$fila[10],$Departamento,
                                    $Pagador[$fila[0]],$Diagnostigo[$fila[0]],$MedicoTte[$fila[0]]);
}
//////////////////////////MEDICAMENTOS////////////////////////////
$cons = "Select Autoid,NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos Where Compania='$Compania[0]'
and AlmacenPpal = '$AlmacenPpal' and Control='Si' and Anio = $Anio";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $CaracMedicamento[$fila[0]] = array($fila[1],$fila[2],$fila[3]);
}
////////////////////////EN PLANTILLA////////////////////////////
$cons = "Select CedPaciente,AutoIdProd,NumOrden,IdEscritura,CantDiaria,
Posologia,ViaSuministro,NombreProd1,UnidadMedida,Presentacion from
Salud.PlantillaMedicamentos, Consumo.CodProductos
Where PlantillaMedicamentos.Compania = '$Compania[0]' and PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal'
and CodProductos.Compania = '$Compania[0]' and CodProductos.AlmacenPpal = '$AlmacenPpal'
and ((TipoMedicamento='Medicamento Programado' and PlantillaMedicamentos.Estado='AC') or (TipoMedicamento = 'Medicamento Urgente'))";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $Plantilla[$fila[0]][$fila[1]][$fila[2]][$fila[3]] = array($fila[4],$fila[5],$fila[6]);
}
////////////////////////////DESPACHADOS////////////////////////
$cons = "Select Cedula,NumeroControlados,Movimiento.Autoid,NumOrden,IdEscritura,
NumServicio,Cantidad,NombreProd1,UnidadMedida,Presentacion,Fecha,Movimiento.UsuarioCre,Numero,Comprobante
from Consumo.Movimiento,Consumo.CodProductos
Where Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]' and
Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal = '$AlmacenPpal' and Fecha >='$Anio-$MesIni-$DiaIni'
and Fecha <= '$Anio-$MesFin-$DiaFin' and (Comprobante = 'Salidas por Plantilla' or Comprobante = 'Salidas Urgentes')
and Movimiento.Estado = 'AC' and Control = 'Si' and CodProductos.Anio = $Anio and CodProductos.Autoid = Movimiento.Autoid
order by NumeroControlados,Cedula";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    if(!$MedicamentosD[$fila[1]][$fila[0]][$fila[2]][$fila[3]][$fila[4]])
    {
        $Cant = $fila[6] - $Devolucion[$fila[0]][$fila[13]][$fila[12]][$fila[2]];
        if($Cant>0)
        {
            $MedicamentosD[$fila[1]][$fila[0]][$fila[2]][$fila[3]][$fila[4]] = array($fila[5],$Cant);
        }
    }
    else
    {
        $Cant = $fila[6] - $Devolucion[$fila[0]][$fila[13]][$fila[12]][$fila[2]];
        if($Cant>0)
        {
            $MedicamentosD[$fila[1]][$fila[0]][$fila[2]][$fila[3]][$fila[4]][1] = $Cant; 
        }
    }
    //$MedicamentosD[$fila[1]][$fila[0]][$fila[2]][$fila[3]][$fila[4]] = array($fila[5],$fila[6]);
    $Fecha[$fila[0]][$fila[1]] = $fila[10];
    $cons1 = "Select Nombre,Cedula from Central.Usuarios Where Nombre='$fila[11]'";
    $res1 = ExQuery($cons1);
    $fila1 = ExFetch($res1);
    $Dispensa[$fila[0]][$fila[1]]=$fila1[0]; $CedulaD[$fila[0]][$fila[1]]=$fila1[1];
}

class PDF extends PDF_Rotate
{
    function BasicTable($Medicamentos,$Plantilla)
    {
        global $Fecha;global $DatPaciente;global $Compania;global $CaracMedicamento;global $CiudadComp;
        global $Dispensa; global $CedulaD; //echo $CaracMedicamento[80][0];
        while(list($Numero,$Medicamentos1) = each($Medicamentos))
        {
            while(list ($Identificacion,$Medicamentos2) = each ($Medicamentos1))
            {
                $Raiz = $_SERVER['DOCUMENT_ROOT'];
                $this->Image("$Raiz/Imgs/Logo.jpg",10,7,10,10);
                $this->SetFillColor(228,228,228);
                $this->Cell(1,5,"",0,0,'R');
                $this->SetFont('Arial','B',10);
                $this->Cell(74,5,"        ".strtoupper($Compania[0]),0,0,'L');$this->Ln();

                $this->Cell(74,5,"        ".strtoupper($Compania[1]),0,0,'L');
                $this->Cell(0,5,"RECETARIO OFICIAL PARA MEDICAMENTOS DE CONTROL ESPECIAL",0,0,'R',1);$this->Ln();
                $this->Cell(0,5,"No. $Numero",0,0,'R');$this->Ln();
                //////ENCABEZADO DATOS DEL PACIENTE
                $this->Cell(50,5,"1.PACIENTE",1,0,'L',1);
                $this->SetFont('Arial','',8);
                $this->Cell(50,5,"Fecha",1,0,'R');
                $this->Cell(22,5,"Dia",'TB',0,'L');
                $EstaFecha = explode("-",$Fecha[$Identificacion][$Numero]);
                $this->Cell(9,5,$EstaFecha[2],'TB',0,'R');
                $this->Cell(22,5,"Mes",'LTB',0,'L');
                $this->Cell(9,5,$EstaFecha[1],'TB',0,'R');
                $this->Cell(22,5,"Año",'LTB',0,'L');
                $this->Cell(0,5,$EstaFecha[0],'TBR',0,'R');
                $this->Ln();
                $this->Cell(50,5,'Primer Apellido',1,0,'C');
                $this->Cell(81,5,'Segundo Apellido',1,0,'C');
                $this->Cell(0,5,'Nombres',1,0,'C');
                $this->Ln();
                ///////////////DATOS DEL PACIENTE////////////
                $this->Cell(50,5,utf8_decode(strtoupper($DatPaciente[$Identificacion][0])),1,0,'L');
                $this->Cell(81,5,utf8_decode(strtoupper($DatPaciente[$Identificacion][1])),1,0,'L');
                $this->Cell(0,5,utf8_decode(strtoupper($DatPaciente[$Identificacion][2]." ".$DatPaciente[$Identificacion][3])),1,0,'L');
                $this->Ln();
                ////////////////OTRO ENCABEZADO PARA DATOS DEL PACIENTE///////////////
                $this->Cell(50,5,'Documento de Identificación',1,0,'C');
                $this->Cell(103,5,'Numero',1,0,'C');
                $this->Cell(23,5,'Edad',1,0,'C');
                $this->Cell(0,5,'Genero',1,0,'C');
                $this->Ln();
                /////////////////MAS DATOS DEL PACIENTE
                $this->Cell(8,5,'T.I',1,0,'L');
                if($DatPaciente[$Identificacion][4]=="Tarjeta de identidad"){$this->Cell(8,5,'X',1,0,'C');}
                else{$this->Cell(8,5,'',1,0,'L');}
                $this->Cell(8,5,'C.C.',1,0,'L');
                if($DatPaciente[$Identificacion][4]=="Cedula de ciudadania"){$this->Cell(8,5,'X',1,0,'C');}
                else{$this->Cell(8,5,'',1,0,'L');}
                $this->Cell(8,5,'Otro',1,0,'C');
                if($DatPaciente[$Identificacion][4]!="Tarjeta de identidad" &&
                        $DatPaciente[$Identificacion][4]!="Cedula de ciudadania"){$this->Cell(8,5,'X',1,0,'C');}
                else{$this->Cell(8,5,'',1,0,'L');}
                $this->Cell(105,5,$Identificacion,1,0,'C');
                $this->Cell(23,5,$DatPaciente[$Identificacion][5],1,0,'C');
                $sety = $this->GetY();$setx=$this->GetX();
                $this->Cell(5,5,'F','LTB',0,'C');
                $this->SetY($sety+1);$this->SetX($setx+6);
                if($DatPaciente[$Identificacion][6][0]=="F"){$this->Cell(3,3,'X',1,0,'C');}
                else{$this->Cell(3,3,'',1,0,'C');}
                $this->SetY($sety);$this->SetX($setx+10);
                $this->Cell(5,5,'M','LTB',0,'C');
                $this->SetY($sety+1);$this->SetX($setx+10+6);
                if($DatPaciente[$Identificacion][6][0]=="M"){$this->Cell(3,3,'X',1,0,'C');}
                else{$this->Cell(3,3,'',1,0,'C');}
                $this->SetY($sety);$this->Cell(0,5,'',1,0,'C');
                $this->Ln();
                /////////////////////ENCABEZADO Y UBICACION DEL PACIENTE
                $this->Cell(48,5,'Teléfono',1,0,'C');
                $this->Cell(40,5,'Municipio',1,0,'C');
                $this->Cell(60,5,'Dirección de Residencia',1,0,'C');
                $this->Cell(0,5,'Departamento',1,0,'C');
                $this->Ln();
                $this->Cell(48,5,substr($DatPaciente[$Identificacion][7],0,30),1,0,'L');
                $this->Cell(40,5,utf8_decode($DatPaciente[$Identificacion][8]),1,0,'L');
                $this->Cell(60,5,utf8_decode($DatPaciente[$Identificacion][9]),1,0,'L');
                $this->Cell(0,5,utf8_decode($DatPaciente[$Identificacion][10]),1,0,'L');
                $this->Ln();
                ////////////////////////////ASEGURADOR
                $this->Cell(41,5,"Afiliacion al S.G.S.S  Subsidiado","LTB",0,'L');
                $sety = $this->GetY();$setx=$this->GetX();
                $this->SetY($sety+1);$this->SetX($setx+2);
                if($DatPaciente[$Identificacion][11][1]=="Subsidiado"){$this->Cell(3,3,'X',1,0,'C');}
                else{$this->Cell(3,3,'',1,0,'C');}
                $this->SetY($sety);$this->SetX($setx+2+5);
                $this->Cell(18,5,"Contributivo","TB",0,'L');
                $this->SetY($sety+1);$this->SetX($setx+2+5+18);
                if($DatPaciente[$Identificacion][11][1]=="Contributivo"){$this->Cell(3,3,'X',1,0,'C');}
                else{$this->Cell(3,3,'',1,0,'C');}
                $this->SetY($sety);$this->SetX($setx+2+5+18+4);
                $this->Cell(15,5,"Vinculado","TB",0,'L');
                $this->SetY($sety+1);$this->SetX($setx+2+5+18+4+15);
                if($DatPaciente[$Identificacion][11][1]=="Vinculado"){$this->Cell(3,3,'X',1,0,'C');}
                else{$this->Cell(3,3,'',1,0,'C');}
                $this->SetY($sety);$this->SetX($setx+2+5+18+4+15+4);
                $this->Cell(30,5,"Nombre de la Entidad","TB",0,'L');
                $this->Cell(0,5,utf8_decode($DatPaciente[$Identificacion][11][0]),"TBR",0,'C');
                $this->SetFont('Arial','B',10);
                $this->Ln();
                ////////////////////MEDICAMENTOS////////////
                $this->Cell(0,5,"2.MEDICAMENTOS",1,0,'L',1);$this->Ln();
                $this->SetFont('Arial','',8);
                $this->Cell(50,10,"Nombre Genérico",1,0,'C');
                $this->Cell(30,10,"Concentración",1,0,'C');
                $this->Cell(20,5,"Forma","TLR",0,'C');
                $this->Cell(40,10,"Dosis Diaria",1,0,'C');
                $this->Cell(20,5,"Via de",'TRL',0,'C');
                $this->Cell(0,4,"Cantidad Prescrita",1,0,'C');
                $this->Ln(5);
                $this->SetY(($this->GetY())-1);
                $this->Cell(80,4,"",0,0,'C');
                $this->Cell(20,6,"Farmaceutica","BLR",0,'C');
                $this->Cell(40,4,"",0,0,'C');
                $this->Cell(20,6,"Administración",'BRL',0,'C');
                $setx=  $this->GetX();$sety=  $this->GetY();
                $this->Cell(12,3,"En","TRL",0,'C');
                $this->Cell(0,6,"En Letras",1,0,'C');
                $this->SetY($sety+3);$this->SetX($setx);
                $this->Cell(12,3,"Numeros","BRL",0,'C');
                $this->Ln(3);
                $this->SetFont('Arial','',5);
                while(list($AutoId,$Medicamentos3) = each ($Medicamentos2))
                {
                    if($Plantilla[$Identificacion][$AutoId])
                    {
                        while(list($NumOrden,$Plantilla2) = each($Plantilla[$Identificacion][$AutoId]))
                        {
                            while(list($IdEscritura,$Plantilla3) = each($Plantilla2))
                            {
                                if($Medicamentos[$Numero][$Identificacion][$AutoId])
                                {
                                    $this->Cell(50,3,$CaracMedicamento[$AutoId][0],'LR',0,'L',1);
                                    $this->Cell(30,3,$CaracMedicamento[$AutoId][1],'LR',0,'L',1);
                                    $this->Cell(20,3,$CaracMedicamento[$AutoId][2],'LR',0,'L',1);
                                    $this->Cell(40,3,$Plantilla3[1],'LR',0,'L',1);
                                    $this->Cell(20,3,$Plantilla3[2],'RL',0,'L',1);
                                    $this->Cell(12,3,$Plantilla3[0],"RL",0,'C',1);
                                    $PosPesos = strpos(NumerosxLet($Plantilla3[0]),"pesos");
                                    $this->Cell(0,3,substr(NumerosxLet($Plantilla3[0]),0,$PosPesos),"RL",0,'L',1);
                                    $this->Ln();
                                }
                            }
                        }
                    }
                }
                $this->SetFont('Arial','',10);
                $sety = $this->GetY();
                $this->Cell(30,5,'',0,0,'L');
                $this->MultiCell(0, 5, $DatPaciente[$Identificacion][12], 'RTB');
                $y=$this->GetY();
                $Alto = $y-$sety;
                $this->SetY($sety);
                $this->Cell(30,$Alto,'DIAGNOSTICO','LTB',0,'L');
                $this->Ln();
                $this->SetFont('Arial','B',10);
                //////////////////////DATOS DEL PROFESIONAL
                $this->Cell(0,5,'3.PROFESIONAL',1,0,'L',1);
                $this->Ln();
                $this->SetFont('Arial','',8);
                $this->Cell(20,5,'MEDICO','LTB',0,'L');
                $setx=$this->GetX();$sety=$this->GetY();
                $this->SetY($sety+1);$this->SetX($setx);
                $this->Cell(3,3,'X',1,0,'C');
                $this->SetY($sety);$this->SetX($setx+4);
                $this->Cell(40,5,'General','TB',0,'R');
                $this->SetY($sety+1);$this->SetX($setx+4+40);
                if($DatPaciente[$Identificacion][13][2]=="Medicina General"){$this->Cell(3,3,'X',1,0,'C');}
                else{$this->Cell(3,3,'',1,0,'C');}
                $this->SetY($sety);$this->SetX($setx+4+40+4);
                $this->Cell(40,5,'Especializado','TB',0,'R');
                $this->SetY($sety+1);$this->SetX($setx+4+40+4+40);
                if($DatPaciente[$Identificacion][13][2]!="Medicina General"){$this->Cell(3,3,'X',1,0,'C');}
                else{$this->Cell(3,3,'X',1,0,'C');}
                $this->SetY($sety);$this->SetX($setx+4+40+4+40+4);
                $this->Cell(55,5,'ODONTOLOGO','TB',0,'R');
                $this->SetY($sety+1);$this->SetX($setx+4+40+4+40+4+55);
                $this->Cell(3,3,'',1,0,'C');
                $this->SetY($sety);$this->SetX($setx+4+40+4+40+4+55+4);
                $this->Cell(0,5,'','RTB',0,'C');
                $this->Ln();
                $this->Cell(0,5,"Especialidad,    Cual:     ".strtoupper($DatPaciente[$Identificacion][13][2]),1,0,'L');$this->Ln();
                $this->Cell(0,5,'Nombres y Apellidos',1,0,'C');
                $this->Ln();
                $this->Cell(0,5,$DatPaciente[$Identificacion][13][1],1,0,'C');
                $this->Ln();
                $this->Cell(80,5,'Documento de Identificación',1,0,'C');
                $this->Cell(60,5,'Resolución Por la Que se Autoriza','TLR',0,'C');
                $RutaRoot=$_SERVER['DOCUMENT_ROOT'];
                $midir=opendir("$RutaRoot/Firmas");
                while($files=readdir($midir))
                {
                    $ext=substr($files,-3);
                    if (!is_dir($files) && ($ext=="PNG"))
                    //$files="Formatos/".$files;
                    if($files!="." && $files!="..")
                    {
                        if($DatPaciente[$Identificacion][13][0])
                        {
                            unset($b);
                            if(ereg($DatPaciente[$Identificacion][13][0],$files))
                            {
                                $b=1;
                                $Raiz = $_SERVER['DOCUMENT_ROOT'];
                                $this->Image("$Raiz/Firmas/$files",$this->GetX(),$this->GetY(),55,10);
                                break;
                            }
                        }
                    }
                }
                if(!$b){$Mensaje="NO REGISTRA";}
                $this->Cell(0,5,"$Mensaje",'TLR',0,'C');
                $this->Ln();
                $this->Cell(15,10,'C.C.','LB',0,'C');
                $setx=$this->GetX();$sety=$this->GetY();
                $this->SetY($sety+2);$this->SetX($setx);
                $this->Cell(6,6,'X',1,0,'C');
                $this->SetY($sety);$this->SetX($setx+7);
                $this->Cell(15,10,'C.E.','LB',0,'C');
                $this->SetY($sety+2);$this->SetX($setx+7+15);
                $this->Cell(6,6,'',1,0,'C');
                $this->SetY($sety);$this->SetX($setx+7+15+7);
                $this->Cell(1,10,'','R',0,'C');
                $this->Cell(35,5,'Numero',1,0,'C');
                $this->Cell(60,5,'El Ejercicio de la profesión No.','BLR',0,'C');
                $this->Cell(0,5,'Firma','BLR',0,'C');
                $this->Ln();
                $this->Cell(45,5,'',0,0,'C');
                $this->Cell(35,5,$DatPaciente[$Identificacion][13][0],1,0,'C');
                $this->Cell(60,5,$DatPaciente[$Identificacion][13][3],1,0,'C');
                $this->Cell(0,5,'',1,0,'C');
                $this->Ln();
                $this->Cell(80,5,'Institución donde labora:',1,0,'C');
                $this->Cell(60,5,'Direccion',1,0,'C');
                $this->Cell(27,5,'Ciudad',1,0,'C');
                $this->Cell(0,5,'Telefono',1,0,'C');
                $this->Ln();
                $this->Cell(80,5,$Compania[0],1,0,'C');
                $this->Cell(60,5,$Compania[2],1,0,'C');
                $this->Cell(27,5,$CiudadComp,1,0,'C');
                $this->Cell(0,5,$Compania[3],1,0,'C');
                $this->Ln();
                $this->SetFont('Arial','B',10);
                ///////////////////////////////////DATOS DE DESPACHO////////////
                $this->SetFont('Arial','B',10);
                $this->Cell(60,5,'4.ENTREGA DEL MEDICAMENTO','LBT',0,'L',1);
                $this->SetFont('Arial','',8);
                $this->Cell(0,5,'(A diligenciar por el establecimiento farmacéutico minorista)','RBT',0,'L',1);
                $this->Ln();
                $this->Cell(80,5,'Apellidos y Nombres de quien recibe',1,0,'C');
                $this->Cell(60,5,'No. de Identidad',1,0,'C');
                $this->Cell(0,5,'Firma',1,0,'C');
                $this->Ln();
                $this->Cell(80,5,'',1,0,'C');
                $this->Cell(60,5,'',1,0,'C');
                $this->Cell(0,5,'',1,0,'C');
                $this->Ln();
                $this->Cell(80,5,'Apellidos y Nombres de quien dispensa',1,0,'C');
                $this->Cell(60,5,'No. de Identidad',1,0,'C');
                $this->Cell(0,5,'Firma',1,0,'C');
                $this->Ln();
                $this->Cell(80,5,$Dispensa[$Identificacion][$Numero],1,0,'C');
                $this->Cell(60,5,$CedulaD[$Identificacion][$Numero],1,0,'C');
                //$this->Cell(0,5,'',1,0,'C');
                $RutaRoot=$_SERVER['DOCUMENT_ROOT'];
                $midir=opendir("$RutaRoot/Firmas");
                while($files=readdir($midir))
                {
                    $ext=substr($files,-3);
                    if (!is_dir($files) && ($ext=="PNG"))
                    //$files="Formatos/".$files;
                    if($files!="." && $files!="..")
                    {
                        if($CedulaD[$Identificacion][$Numero])
                        {
                            unset($b);
                            if(ereg($CedulaD[$Identificacion][$Numero],$files))
                            {
                                $b=1;
                                $Raiz = $_SERVER['DOCUMENT_ROOT'];
                                $this->Image("$Raiz/Firmas/$files",$this->GetX(),$this->GetY(),55,10);
                                break;
                            }
                        }
                    }
                }
                if(!$b){$Mensaje="NO REGISTRA";}
                $this->Cell(0,5,"$Mensaje",'TLR',0,'C');
                $this->Ln();
                $this->Cell(80,5,'Establecimiento Farmacéutico Minorista',1,0,'C');
                $this->Cell(70,5,'Dirección',1,0,'C');
                $this->Cell(0,5,'Fecha de Despacho',1,0,'C');
                $this->Ln();
                $this->Cell(80,5,$Compania[0],1,0,'C');
                $this->Cell(70,5,$Compania[2],1,0,'C');
                $this->Cell(7,5,'Dia',1,0,'R');
                $this->Cell(7,5,$EstaFecha[2],1,0,'C');
                $this->Cell(7,5,'Mes',1,0,'R');
                $this->Cell(7,5,$EstaFecha[1],1,0,'C');
                $this->Cell(9,5,'Año',1,0,'R');
                $this->Cell(0,5,$EstaFecha[0],1,0,'C');
                $this->Ln();
                $this->Cell(40,10,"Nombre Genérico",1,0,'C');
                $this->Cell(30,10,"Concentración",1,0,'C');
                $this->Cell(20,5,"Forma","TLR",0,'C');
                $this->Cell(35,10,"Dosis Diaria",1,0,'C');
                $this->Cell(20,5,"Via de",'TRL',0,'C');
                $this->Cell(31,4,"Cantidad Prescrita",1,0,'C');
                $this->Cell(0,4,"Cantidad",'LRT',0,'C');
                $this->Ln(5);
                $this->SetY(($this->GetY())-1);
                $this->Cell(70,4,"",0,0,'C');
                $this->Cell(20,6,"Farmaceutica","BLR",0,'C');
                $this->Cell(35,4,"",0,0,'C');
                $this->Cell(20,6,"Administración",'BRL',0,'C');
                $setx=  $this->GetX();$sety=  $this->GetY();
                $this->Cell(12,3,"En","TRL",0,'C');
                $this->Cell(19,6,"En Letras",1,0,'C');
                $this->Cell(0,6,"Despachada",'LRB',0,'C');
                $this->SetY($sety+3);$this->SetX($setx);
                $this->Cell(12,3,"Numeros","BRL",0,'C');
                $this->Ln(3);
                reset($Medicamentos2);
                $this->SetFont('Arial','',5);
                while(list($AutoId,$Medicamentos3) = each ($Medicamentos2))
                {
                    if($Plantilla[$Identificacion][$AutoId])
                    {
                        reset($Plantilla[$Identificacion][$AutoId]);
                        while(list($NumOrden,$Plantilla2) = each($Plantilla[$Identificacion][$AutoId]))
                        {
                            while(list($IdEscritura,$Plantilla3) = each($Plantilla2))
                            {
                                if($Medicamentos[$Numero][$Identificacion][$AutoId])
                                {
                                    $this->Cell(50,3,$CaracMedicamento[$AutoId][0],'LR',0,'L',1);
                                    $this->Cell(30,3,$CaracMedicamento[$AutoId][1],'LR',0,'L',1);
                                    $this->Cell(20,3,$CaracMedicamento[$AutoId][2],'LR',0,'L',1);
                                    $this->Cell(40,3,$Plantilla3[1],'LR',0,'L',1);
                                    $this->Cell(20,3,$Plantilla3[2],'RL',0,'L',1);
                                    $this->Cell(12,3,$Plantilla3[0],"RL",0,'C',1);
                                    $PosPesos = strpos(NumerosxLet($Plantilla3[0]),"pesos");
                                    $this->Cell(0,3,substr(NumerosxLet($Plantilla3[0]),0,$PosPesos),"RL",0,'L',1);
                                    $this->Ln();
                                }
                            }
                        }
                    }
                }
                $this->SetFont('Arial','',8);
//                while(list($AutoId,$Medicamentos3) = each ($Medicamentos2))
//                {
//                    while(list($NumOrden,$Medicamentos4) = each($Medicamentos3))
//                    {
//                        while(list($IdEscritura,$Medicamentos5) = each($Medicamentos4))
//                        {
//                            $this->Cell(40,3,$CaracMedicamento[$AutoId][0],'LR',0,'L',1);
//                            $this->Cell(30,3,$CaracMedicamento[$AutoId][1],'LR',0,'L',1);
//                            $this->Cell(20,3,$CaracMedicamento[$AutoId][2],'LR',0,'L',1);
//                            $this->Cell(35,3,$Plantilla[$Identificacion][$AutoId][$NumOrden][$IdEscritura][1],'LR',0,'L',1);
//                            $this->Cell(20,3,$Plantilla[$Identificacion][$AutoId][$NumOrden][$IdEscritura][2],'RL',0,'L',1);
//                            $this->Cell(12,3,$Plantilla[$Identificacion][$AutoId][$NumOrden][$IdEscritura][0],"RL",0,'C',1);
//                            $PosPesos = strpos(NumerosxLet($Plantilla[$Identificacion][$AutoId][$NumOrden][$IdEscritura][0]),"pesos");
//                            $this->Cell(19,3,substr(NumerosxLet($Plantilla[$Identificacion][$AutoId][$NumOrden][$IdEscritura][0]),0,$PosPesos),"RL",0,'L',1);
//                            $this->Cell(0,3,$Medicamentos5[1],"RL",0,'L',1);
//                            $this->Ln();
//                        }
//                    }
//                }
                $this->Cell(0,1,'','T',0,'C');
                $this->AddPage();
            }
        }
    }
    function Header()
    {}
    function Footer()
    {}
}
$pdf=new PDF('P','mm','Letter');
//$pdf->SetMargins(3, 3, 3);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$pdf->BasicTable($MedicamentosD,$Plantilla);
$pdf->Output();
?>