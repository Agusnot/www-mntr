<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
include("ObtenerSaldos.php");
require('LibPDF/rotation.php');
$ND = getdate();
if($MesFin<10){$MesFin = "0".$MesFin;}
$cons = "Select NumDias from Central.Meses Where Numero = $MesFin";
$res = ExQuery($cons);
$fila = ExFetch($res);
$DiasMes = $fila[0];
///////////////////////////////////////////
/////////////////////////////////DEVOLUCIONES/////////////////////////////////
    $cons = "Select Cedula,NoDocAfectado,DocAfectado,Autoid,Cantidad from Consumo.Movimiento
    Where Comprobante = 'Devoluciones' and TipoComprobante = 'Devoluciones' and Compania='$Compania[0]'
    and Fecha >='$Anio-$MesFin-01' and Fecha <= '$Anio-$MesFin-$DiasMes'
    and AlmacenPpal = '$AlmacenPpal' and Estado = 'AC'";
    $res = ExQuery($cons);
    //echo $cons;
    while($fila = ExFetch($res))
    {
        $Devolucion[$fila[0]][$fila[2]][$fila[1]][$fila[3]] = $fila[4];
//        echo "$fila[0]----$fila[1]----$fila[2]----$fila[3]----$fila[4]<br>";
    }
/////////////////////////////////////////////////////////////////////////////
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
and CodProductos.Compania = '$Compania[0]' and CodProductos.AlmacenPpal = '$AlmacenPpal' and Control = 'Si'
and AutoidProd = Autoid";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $fila[5] = str_replace ("Suministrar","",$fila[5]);
    $fila[5] = str_replace ("Tomar","",$fila[5]);
    $fila[5] = str_replace (" a las ","(",$fila[5]);
    $fila[5] = str_replace (", ",")",$fila[5]);
    $fila[5] = str_replace (" - ","",$fila[5]);
    $Plantilla[$fila[0]][$fila[1]] = array($fila[4],$fila[5],$fila[6]);
}
////////////////////////////DESPACHADOS////////////////////////
$cons = "Select Cedula,NumeroControlados,Movimiento.Autoid,NumOrden,IdEscritura,
NumServicio,Cantidad,NombreProd1,UnidadMedida,Presentacion,Fecha,Movimiento.UsuarioCre,Numero,Comprobante
from Consumo.Movimiento,Consumo.CodProductos
Where Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]' and
Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal = '$AlmacenPpal' and Fecha >='$Anio-$MesFin-01'
and Fecha <= '$Anio-$MesFin-$DiasMes' and TipoComprobante = 'Salidas'
and Movimiento.Estado = 'AC' and Control = 'Si' and CodProductos.Anio = $Anio and CodProductos.Autoid = Movimiento.Autoid
order by NumeroControlados,Cedula";
//echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    if(!$MedicamentosD[$fila[0]][$fila[2]])
    {
        $Cant = $fila[6] - $Devolucion[$fila[0]][$fila[13]][$fila[12]][$fila[2]];
        if($Cant>0){$MedicamentosD[$fila[0]][$fila[2]] = array($fila[5],$Cant);}
    }
    else
    {
        $Cant = $fila[6] - $Devolucion[$fila[0]][$fila[12]][$fila[13]][$fila[2]];
        if($Cant>0){$MedicamentosD[$fila[0]][$fila[2]][1] = $MedicamentosD[$fila[0]][$fila[2]][1] + $Cant;}
    }
    //if($fila[2]==83)
    //{
        //if(!$C){$C = $Cant;}
        //else{$C = $C+$Cant;}
        //echo "<b>$fila[0].....$Cant</b>--------------".$C."<br>";
    //}
    $Fecha[$fila[0]][$fila[1]] = $fila[10];
    $cons1 = "Select Nombre,Cedula from Central.Usuarios Where Nombre='$fila[11]'";
    $res1 = ExQuery($cons1);
    $fila1 = ExFetch($res1);
    $Dispensa[$fila[0]] =$fila1[0]; $CedulaD[$fila[0]]=$fila1[1];
}

class PDF extends PDF_Rotate
{
    function BasicTable($Medicamentos,$Plantilla)
    {
        global $Fecha;global $DatPaciente;global $Compania;global $CaracMedicamento;global $CiudadComp;
        global $Dispensa; global $CedulaD; global $DiasMes; global $Anio; global $MesFin;
        ////echo $CaracMedicamento[80][0];
        //while(list($Numero,$Medicamentos1) = each($Medicamentos))
        //{			
            while(list ($Identificacion,$Medicamentos2) = each ($Medicamentos))
            {
				
                $this->Cell(0,8,"",0,0,'C');
                $this->Ln();
				$XX=$this->GetX();
				$YY=$this->GetY();
				$this->SetXY($XX+20,$YY+11);
                ///1.PACIENTE    Fecha   Dia    Anio
                $this->Cell(77,5,"",0,0,'C');
                $this->Cell(15,5,$DiasMes,0,0,'L');
                $this->Cell(5,5,"",0,0,'C');
                $this->Cell(30,5,$MesFin,0,0,'L');
                $this->Cell(6,5,"",0,0,'R');
                $this->Cell(25,5,$Anio,0,0,'L');
                $this->Ln(8);
                ////////////////////////////////DATOS DEL PACIENTE/////////////////////
                ///Primer Apellido       Segundo Apellido     Nombres
                //$this->Cell(8,5,"",0,0,'C');
                $this->SetX($XX+15); //cambiar posicion de X segun la variable de arriba $XX
                $this->Cell(50,5,$DatPaciente[$Identificacion][0],0,0,'L');
                $this->Cell(44,5,$DatPaciente[$Identificacion][1],0,0,'L');
                $this->Cell(67,5,$DatPaciente[$Identificacion][2]." ".$DatPaciente[$Identificacion][3],0,0,'L');
                $this->Ln(8);
                ///////////////////////////////////////////////////////
                ///Documento de Identificacion    Numero      Edad   Genero
			    $this->SetX($XX+6); 
                $this->Cell(19,5,"",0,0,'C');
                $this->Cell(5,5,"X",0,0,'C');//$val1[6]=C.C.
                $this->Cell(16,5,"",0,0,'C');
                $this->Cell(67,5,$Identificacion,0,0,'L');
                $this->Cell(29,5,$val1[8],0,0,'L');
                if($DatPaciente[$Identificacion][6][0]=="F"){$this->Cell(5,5,"",0,0,'L');$this->Cell(5,5,"X",1,0,'C');}
                if($DatPaciente[$Identificacion][6][0]=="M"){$this->Cell(17,5,"",0,0,'L');$this->Cell(5,5,"X",1,0,'C');}
                $this->Ln(8);
                ///////////////////////////////////////////////////////////////////
                ///Telefono  Municipio  Direccion Residencia   Departamento
                $this->SetX($XX+15); //cambiar posicion de X segun la variable de arriba $XX//7
                $this->Cell(44,5,substr($DatPaciente[$Identificacion][7],0,30),0,0,'L');
                $this->Cell(25,5,utf8_decode($DatPaciente[$Identificacion][8]),0,0,'L');
                $this->Cell(54,5,utf8_decode($DatPaciente[$Identificacion][9]),0,0,'L');
                $this->Cell(40,5,utf8_decode($DatPaciente[$Identificacion][10]),0,0,'L');
                $this->Ln(5);
                ////////////////////////////////////////////////////////////
                ///Afiliacion al SGSSS    Nombre de la Entidad
                $this->Cell(35,5,"",0,0,'L');
                if($DatPaciente[$Identificacion][11][1]=="Subsidiado")
                {
                        $this->Cell(5,5,"X",0,0,'C');
                        $this->Cell(55,5,"",0,0,'C');
                }
                if($DatPaciente[$Identificacion][11][1]=="Contributivo")
                {
                        $this->Cell(17,5,"",0,0,'C');
                        $this->Cell(5,5,"X",0,0,'C');
                        $this->Cell(40,5,"",0,0,'C');
                }
                if($DatPaciente[$Identificacion][11][1]=="Vinculado")
                {
                        $this->Cell(32,5,"",0,0,'C');
                        $this->Cell(5,5,"X",0,0,'C');
                        $this->Cell(25,5,"",0,0,'C');
                }
                if($DatPaciente[$Identificacion][11][1]!="Subsidiado"
                        && $DatPaciente[$Identificacion][11][1]!="Contributivo"
                        && $DatPaciente[$Identificacion][11][1]!="Vinculado")
                {
                        $this->Cell(60,5,"",0,0,'C');
                }
                $this->Cell(65,5,utf8_decode($DatPaciente[$Identificacion][11][0]),0,0,'L');
                $this->Ln(13);
                ////////////////////MEDICAMENTOS//////////////////////
                if($Plantilla[$Identificacion])
                {
                    while(list($AutoId,$Plantilla1) = each($Plantilla[$Identificacion]))
                    {
                        if($Medicamentos[$Identificacion][$AutoId])
                        {
                            $this->SetFont('Arial','',5);
                            $this->SetX($XX+10); //cambiar posicion de X segun la variable de arriba $XX
                            $this->Cell(50,5,$CaracMedicamento[$AutoId][0],0,0,'L');
                            $this->Cell(18,5,$CaracMedicamento[$AutoId][1],0,0,'L');
                            $this->Cell(15,5,$CaracMedicamento[$AutoId][2],0,0,'L');
                            $this->Cell(22,5,$Plantilla1[1],0,0,'L');
                            $this->Cell(22,5,$Plantilla1[2],0,0,'L');
                            $this->Cell(12,5,$Plantilla1[0],0,0,'L');
                            $PosPesos = strpos(NumerosxLet($Plantilla1[0]),"pesos");
                            $this->Cell(25,5,substr(NumerosxLet($Plantilla1[0]),0,$PosPesos),0,0,'L');
                            $this->Ln(2);
                        }
                    }
                }
                $this->SetFont('Arial','',7);
                $this->SetY(87);
                ///////////////////////////////////
                ///DIAGNOSTICO
                $this->Cell(20,5,"",0,0,'L');
                $this->Cell(135,5,substr($DatPaciente[$Identificacion][12],0,80),0,0,'L');
                $this->Ln(8);
                ////////////////////////////////
                ///3.PROFESIONAL   Especializado $val1[24];
                $this->Cell(90,5,"",0,0,'C');
                $this->Cell(5,5,"X",0,0,'C');
                $this->Ln(6);
                ////////////////////////////////////////////
                $this->Cell(25,5,"",0,0,'C');
                $this->Cell(130,5,"Psiquiatra",0,0,'L');
                $this->Ln(9);
                /////////////////////////////////////////////////
                ///Primer Apellido    Segundo Apellido   Nombres
                $this->SetX($XX+10); //cambiar posicion de X segun la variable de arriba $XX
                //$this->Cell(42,5,$val1[25],0,0,'L');
                //$this->Cell(52,5,$val1[26],0,0,'L');
                //$this->Cell(67,5,$val1[27],0,0,'L');
                $this->Cell(161,5,$DatPaciente[$Identificacion][13][1],0,0,'C');
                $this->Ln(9);
                /////////////////////////////////////////////////
                ///CC     Numero      Resolucion    Firma
                $this->Cell(6,5,"",0,0,'C');
                $this->Cell(5,5,"X",0,0,'C');///$val1[28]=C.C.
                $this->Cell(30,5,"",0,0,'C');
                $this->Cell(28,5,$DatPaciente[$Identificacion][13][0],0,0,'L');
                $this->Cell(55,5,$DatPaciente[$Identificacion][13][3],0,0,'L');
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
                                $this->Image("$Raiz/Firmas/$files",130,115,10,10);
                                break;
                            }
                        }
                    }
                }
                $this->Ln(9);
                ///////////////////////////////////////////////
                ///////////////////////////////////////////////
                ///Institucion donde Labora          Direccion      Ciudad   Telefono
                $this->SetX($XX+10); //cambiar posicion de X segun la variable de arriba $XX
                $this->Cell(69,5,$Compania[0],0,0,'L');
                $this->Cell(52,5,$Compania[2],0,0,'L');
                $this->Cell(22,5,$CiudadComp,0,0,'L');
                $this->Cell(21,5,$Compania[3],0,0,'L');
                $this->Ln(11);
                ////////////////////////////////////////////////////////////////////////
                ///Apellidos y Nombre de quien recibe        No Identidad        Firma
                //$this->Cell(10,5,"",0,0,'C');
                $this->SetX($XX+10); //cambiar posicion de X segun la variable de arriba $XX
                $this->Cell(68,5,"Leidy Cristina Eraso",0,0,'L');
                $this->Cell(35,5,"27298396",0,0,'L');
                //$this->Image("/var/www/html/Firmas/27298396.png",110,138,25,8);
                $this->Ln(8);
                ///////////////////////////////////////////////////////////////////////
                ///Apellidos y Nombre de quien dispensa        No Identidad        Firma
                //$this->Cell(10,5,"",0,0,'C');
                $this->SetX($XX+10); //cambiar posicion de X segun la variable de arriba $XX
                //$this->Cell(68,5,$Dispensa[$Identificacion],0,0,'L');
                $this->Cell(68,5,"Rita Castro",0,0,'L');
                $this->Cell(35,5,"30743813",0,0,'L');
                //$this->Cell(60,5,$val1[41],0,0,'L');
                //$this->Image("/var/www/html/Firmas/30743813.PNG",110,148,25,8);
//                $RutaRoot=$_SERVER['DOCUMENT_ROOT'];
//                $midir=opendir("$RutaRoot/Firmas");
//                while($files=readdir($midir))
//                {
//                    $ext=substr($files,-3);
//                    if (!is_dir($files) && ($ext=="PNG"))
//                    //$files="Formatos/".$files;
//                    if($files!="." && $files!="..")
//                    {
//                        if($DatPaciente[$Identificacion][13][0])
//                        {
//                            if(ereg($Dispensa[$Identificacion],$files))
//                            {
//                                $b=1;
//                                $Raiz = $_SERVER['DOCUMENT_ROOT'];
//                                $this->Image("$Raiz/Firmas/$files",110,148,25,8);
//                                break;
//                            }
//                        }
//                    }
//                }

                $this->Ln(10);
                ////////////////////////////////////////////////////////////////////////
                ///Establecimiento Farmaceutico Minorista           Direccion    Fecha de despacho
                //$this->Cell(10,5,"",0,0,'C');
                $this->SetX($XX+10); //cambiar posicion de X segun la variable de arriba $XX
                $this->Cell(68,5,$Compania[0],0,0,'L');
                //$this->Cell(10,5,"",0,0,'C');
                $this->Cell(46,5,$Compania[2],0,0,'L');
                $this->Cell(6,5,"",0,0,'C');
                $this->Cell(10,5,$DiasMes,0,0,'L');
                $this->Cell(6,5,"",0,0,'C');
                $this->Cell(10,5,$MesFin,0,0,'L');
                $this->Cell(8,5,"",0,0,'C');
                $this->Cell(10,5,$Anio,0,0,'L');
                $this->Ln(10);
                //////////////////////////////////////////////////////////////////////////////////////
                $this->SetFont('Arial','',5);
                while(list($AutoId,$Medicamentos3) = each($Medicamentos2))
                {
                    $this->SetX($XX+10); //cambiar posicion de X segun la variable de arriba $XX
                    $this->Cell(46,5,$CaracMedicamento[$AutoId][0],0,0,'L');
                    $this->Cell(19,5,$CaracMedicamento[$AutoId][1],0,0,'L');
                    $this->Cell(15,5,$CaracMedicamento[$AutoId][2],0,0,'L');
                    $this->Cell(15,5,$Plantilla[$Identificacion][$AutoId][1],0,0,'L');
                    $this->Cell(17,5,$Plantilla[$Identificacion][$AutoId][2],0,0,'L');
                    $this->Cell(11,5,$Plantilla[$Identificacion][$AutoId][0],0,0,'L');
                    $PosPesos = strpos(NumerosxLet($Plantilla[$Identificacion][$AutoId][0]),"pesos");
                    $this->Cell(26,5,substr(NumerosxLet($Plantilla[$Identificacion][$AutoId][0]),0,$PosPesos),0,0,'L');
                    $this->Cell(14,5,$Medicamentos3[1],0,0,'L');
                    $this->Ln(2);
                }
                $this->SetFont('Arial','',7);
                $this->AddPage();
            }
        //}
    }
    function Header()
    {}
    function Footer()
    {}
}

$Hoja=array('180','253');
$pdf=new PDF('P','mm',$Hoja);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',7);
$pdf->BasicTable($MedicamentosD,$Plantilla);
$pdf->Output();
?>