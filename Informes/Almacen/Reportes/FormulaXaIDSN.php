<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
require('LibPDF/rotation.php');
$ND = getdate();
$Despachados = array();
//echo $Numero."----$Cedula";
if($Numero){$AdCons3 = " and NumeroControlados='$Numero'";}
if($Cedula){$AdCons4 = " and Cedula = '$Cedula'";}
$cons = "Select Cedula,Autoid,NumeroControlados,Fecha,Cantidad,UsuarioCre
from Consumo.Movimiento Where AlmacenPpal='$AlmacenPpal' and Anio = $ND[year] $AdCons3 $AdCons4";
//echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $DatFecha = explode("-",$fila[3]);
    $Anio = $DatFecha[0]; $Mes = $DatFecha[1]; $Dia=$DatFecha[2];
    $Dispensa = $fila[5];
    $cons1 = "Select Nombre,Cedula from Central.Usuarios Where Usuario='$fila[5]'";
    $res1 = ExQuery($cons1);
    $fila1 = ExFetch($res1);
    $Dispensa =$fila1[0]; $CedulaD=$fila1[1];
    $Despachados[$fila[0]][$fila[1]] = $fila[4];
    $NumeroControl[$fila[0]] = $fila[2];
}
if(!$Dia){$Dia = $ND[mday];}
if(!$Mes){$Mes = $ND[mon];}
if(!$Anio){$Anio = $ND[year];}
if(!$Dispensa){$Dispensa=$usuario[0];$CedulaD=$usuario[2];};
$cons = "Select NomMunicipio from Central.Compania Where Nombre='$Compania[0]'";
$res = ExQuery($cons);
$fila = ExFetch($res);
$CiudadComp = $fila[0];
$cons = "Select Departamento,Codigo from Central.Departamentos";
$res = ExQuery($cons);
while($fila = ExFetch($res)){$D[$fila[1]] = $fila[0];}
if($Cedula){$AdCons=" And Identificacion = '$Cedula'";$AdCons2=" And Servicios.Cedula='$Cedula'";}
while(list($Identificacion,$Arreglo) = each($TarjetaMeds))
{
    $cons = "Select PrimNom,SegNom,PrimApe,SegApe from Central.Terceros Where Compania='$Compania[0]' and Identificacion='$Identificacion'";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Nombre[$Identificacion] = "$fila[0] $fila[1] $fila[2] $fila[3]";
    while(list($AutoId,$Cantidad) = each($Arreglo))
    {
        $cons = "Select NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos
            Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio = $ND[year] and AutoId=$AutoId";
        $res = ExQuery($cons);
        $fila = ExFetch($res);
        $Medicamento[$AutoId] = "$fila[0] $fila[1] $fila[2]";
        $cons = "Select Hora,Cantidad from salud.horacantidadxMedicamento
            Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
            and paciente='$Identificacion' and AutoId = $AutoId and Estado = 'AC' and Tipo='P'";
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            if(!$Tarjeta[$Identificacion][$AutoId]){!$Tarjeta[$Identificacion][$AutoId] = "$fila[1]($fila[0])";}
            else{$Tarjeta[$Identificacion][$AutoId] = $Tarjeta[$Identificacion][$AutoId]." - $fila[1]($fila[0])";}
        }
    }
}
$cons = "Select Autoid,NombreProd1,UnidadMedida,Presentacion,Control from Consumo.CodProductos
Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio='$Anio' and Control='Si'";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $Medicamento[$fila[0]] = array($fila[1],$fila[2],$fila[3],$fila[4]);
}
$cons = "Select Servicios.Cedula,Entidad,PrimApe,Diagnostico,Medicotte,TipoAsegurador,Nombre,Especialidad,Usuarios.Cedula,RM
from Salud.Servicios, Salud.PagadorxServicios,Central.Terceros, Salud.CIE, Central.Usuarios, Salud.Medicos Where
Servicios.Compania='$Compania[0]' and PagadorxServicios.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
And Servicios.NumServicio=PagadorxServicios.NumServicio and Cie.Codigo = Servicios.DxServ and
Usuarios.Usuario = Servicios.MedicoTte and Usuarios.Usuario=Medicos.Usuario and Servicios.MedicoTte=Medicos.Usuario and
PagadorxServicios.Entidad = Terceros.Identificacion $AdCons2";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $Pagador[$fila[0]] = array($fila[2],$fila[5]);
    $Diagnostigo[$fila[0]] = $fila[3];
    $MedicoTte[$fila[0]] = array($fila[8],$fila[6],$fila[7],$fila[9]);
    //echo $fila[4].": ".$Medico[$fila[4]];
}
$cons = "Select Identificacion,PrimApe,SegApe,PrimNom,SegNom,TipoDoc,fecnac,Sexo,Telefono,Municipio,Direccion,Departamento
from Central.Terceros Where Compania='$Compania[0]' and Tipo='Paciente' $AdCons";
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
    if($TarjetaMeds[$fila[0]])
    {
        $Edad = ObtenEdad($fila[6]);
        if($fila[11]*1 != 0){$Departamento = $D[$fila[11]];}
        //if(!$fila[10]){$Direccion=$Compania[2];}
        /*else{*/$Direccion=$fila[10];//}
        $Datos[$fila[0]] = array($fila[1],$fila[2],$fila[3],$fila[4],$fila[5],
                                $Edad,$fila[7],$fila[8],$fila[9],$Direccion,$Departamento,
                                $Pagador[$fila[0]],$Diagnostigo[$fila[0]],$MedicoTte[$fila[0]]);
    
        //echo $MedicoTte[$fila[0]];
    }
}
class PDF extends PDF_Rotate
{
    function Header()
    {
    }
    function BasicTable($Datos)
    {
        global $Dia;        global $Mes;        global $Anio;
        global $TarjetaMeds;global $Medicamento;global $CiudadComp;
        global $Tarjeta;    global $Compania;   global $NumeroControl;
        global $Dispensa;   global  $CedulaD;   global $Despachados;
        while(list($Identificacion,$Formulas1)=each($Datos))
        {
            $Raiz = $_SERVER['DOCUMENT_ROOT'];
            $this->Image("$Raiz/Imgs/Logo.jpg",10,7,10,10);
            $this->SetFillColor(228,228,228);
            $this->Cell(1,5,"",0,0,'R');
            $this->SetFont('Arial','B',10);
            $this->Cell(74,5,"        ".strtoupper($Compania[0]),0,0,'L');$this->Ln();
            
            $this->Cell(74,5,"        ".strtoupper($Compania[1]),0,0,'L');
            $this->Cell(0,5,"RECETARIO OFICIAL PARA MEDICAMENTOS DE CONTROL ESPECIAL",0,0,'R',1);$this->Ln();
            $this->Cell(0,5,"No. $NumeroControl[$Identificacion]",0,0,'R');$this->Ln();
            //DATOS DEL PACIENTE
            $this->Cell(50,5,"1.PACIENTE",1,0,'L',1);
            $this->SetFont('Arial','',8);
            $this->Cell(50,5,"Fecha",1,0,'R');
            $this->Cell(22,5,"Dia",'TB',0,'L');
            $this->Cell(9,5,$Dia,'TB',0,'R');
            $this->Cell(22,5,"Mes",'LTB',0,'L');
            $this->Cell(9,5,$Mes,'TB',0,'R');
            $this->Cell(22,5,"Año",'LTB',0,'L');
            $this->Cell(12,5,$Anio,'TBR',0,'R');
            $this->Ln();
            $this->Cell(50,5,'Primer Apellido',1,0,'C');
            $this->Cell(81,5,'Segundo Apellido',1,0,'C');
            $this->Cell(65,5,'Nombres',1,0,'C');
            $this->Ln();
            $this->Cell(50,5,utf8_decode(strtoupper($Formulas1[0])),1,0,'L');
            $this->Cell(81,5,utf8_decode(strtoupper($Formulas1[1])),1,0,'L');
            $this->Cell(65,5,utf8_decode(strtoupper("$Formulas1[2] $Formulas1[3]")),1,0,'L');
            $this->Ln();
            $this->Cell(50,5,'Documento de Identificación',1,0,'C');
            $this->Cell(103,5,'Numero',1,0,'C');
            $this->Cell(23,5,'Edad',1,0,'C');
            $this->Cell(20,5,'Genero',1,0,'C');
            $this->Ln();
            $this->Cell(8,5,'T.I',1,0,'L');
            if($Formulas1[4]=="Tarjeta de identidad"){$this->Cell(8,5,'X',1,0,'C');}
            else{$this->Cell(8,5,'',1,0,'L');}
            $this->Cell(8,5,'C.C.',1,0,'L');
            if($Formulas1[4]=="Cedula de ciudadania"){$this->Cell(8,5,'X',1,0,'C');}
            else{$this->Cell(8,5,'',1,0,'L');}
            $this->Cell(8,5,'Otro',1,0,'C');
            if($Formulas1[4]!="Tarjeta de identidad" && $Formulas1[4]!="Cedula de ciudadania"){$this->Cell(8,5,'X',1,0,'C');}
            else{$this->Cell(8,5,'',1,0,'L');}
            $this->Cell(105,5,$Identificacion,1,0,'C');
            $this->Cell(23,5,$Formulas1[5],1,0,'C');
            $sety = $this->GetY();$setx=$this->GetX();
            $this->Cell(5,5,'F','LTB',0,'C');
            $this->SetY($sety+1);$this->SetX($setx+6);
            if($Formulas1[6][0]=="F"){$this->Cell(3,3,'X',1,0,'C');}
            else{$this->Cell(3,3,'',1,0,'C');}
            $this->SetY($sety);$this->SetX($setx+10);
            $this->Cell(5,5,'M','LTB',0,'C');
            $this->SetY($sety+1);$this->SetX($setx+10+6);
            if($Formulas1[6][0]=="M"){$this->Cell(3,3,'X',1,0,'C');}
            else{$this->Cell(3,3,'',1,0,'C');}
            $this->SetY($sety);$this->Cell(0,5,'',1,0,'C');
            $this->Ln();
            $this->Cell(48,5,'Teléfono',1,0,'C');
            $this->Cell(40,5,'Municipio',1,0,'C');
            $this->Cell(60,5,'Dirección de Residencia',1,0,'C');
            $this->Cell(48,5,'Departamento',1,0,'C');
            $this->Ln();
            $this->Cell(48,5,$Formulas1[7],1,0,'L');
            $this->Cell(40,5,utf8_decode($Formulas1[8]),1,0,'L');
            $this->Cell(60,5,utf8_decode($Formulas1[9]),1,0,'L');
            $this->Cell(48,5,utf8_decode($Formulas1[10]),1,0,'L');
            $this->Ln();
            $this->Cell(41,5,"Afiliacion al S.G.S.S  Subsidiado","LTB",0,'L');
            $sety = $this->GetY();$setx=$this->GetX();
            $this->SetY($sety+1);$this->SetX($setx+2);
            if($Formulas1[11][1]=="Subsidiado"){$this->Cell(3,3,'X',1,0,'C');}
            else{$this->Cell(3,3,'',1,0,'C');}
            $this->SetY($sety);$this->SetX($setx+2+5);
            $this->Cell(18,5,"Contributivo","TB",0,'L');
            $this->SetY($sety+1);$this->SetX($setx+2+5+18);
            if($Formulas1[11][1]=="Contributivo"){$this->Cell(3,3,'X',1,0,'C');}
            else{$this->Cell(3,3,'',1,0,'C');}
            $this->SetY($sety);$this->SetX($setx+2+5+18+4);
            $this->Cell(15,5,"Vinculado","TB",0,'L');
            $this->SetY($sety+1);$this->SetX($setx+2+5+18+4+15);
            if($Formulas1[11][1]=="Vinculado"){$this->Cell(3,3,'X',1,0,'C');}
            else{$this->Cell(3,3,'',1,0,'C');}
            $this->SetY($sety);$this->SetX($setx+2+5+18+4+15+4);
            $this->Cell(30,5,"Nombre de la Entidad","TB",0,'L');
            $this->Cell(0,5,utf8_decode($Formulas1[11][0]),"TBR",0,'C');
            $this->SetFont('Arial','B',10);
            $this->Ln();
            //MEDICAMENTOS
            $this->Cell(0,5,"2.MEDICAMENTOS",1,0,'L',1);$this->Ln();
            $this->SetFont('Arial','',8);
            $this->Cell(50,10,"Nombre Genérico",1,0,'C');
            $this->Cell(30,10,"Concentración",1,0,'C');
            $this->Cell(20,5,"Forma","TLR",0,'C');
            $this->Cell(40,10,"Dosis Diaria",1,0,'C');
            $this->Cell(20,5,"Via de",'TRL',0,'C');
            $this->Cell(36,4,"Cantidad Prescrita",1,0,'C');
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
            while(list($Autoid,$TarjetaMeds1)=each($TarjetaMeds[$Identificacion]))
            {
                if($Medicamento[$Autoid][3]=="Si")
                {
                    $this->Cell(50,3,$Medicamento[$Autoid][0],'LR',0,'L',1);
                    $this->Cell(30,3,$Medicamento[$Autoid][1],'LR',0,'L',1);
                    $this->Cell(20,3,$Medicamento[$Autoid][2],'LR',0,'L',1);
                    $this->Cell(40,3,$Tarjeta[$Identificacion][$Autoid],'LR',0,'L',1);
                    $this->Cell(20,3,"Oral",'RL',0,'L',1);
                    $this->Cell(12,3,$TarjetaMeds1,"RL",0,'C',1);
                    $PosPesos = strpos(NumerosxLet($TarjetaMeds1),"pesos");
                    $this->Cell(0,3,substr(NumerosxLet($TarjetaMeds1),0,$PosPesos),"RL",0,'L',1);
                    $this->Ln();
                }
            }
            //$this->Ln();
            //DIAGNOSTICO Y MEDICO
            $this->SetFont('Arial','',10);
            $sety = $this->GetY();
            $this->Cell(30,5,'',0,0,'L');
            $this->MultiCell(0, 5, $Formulas1[12], 'RTB');
            $y=$this->GetY();
            $Alto = $y-$sety;
            $this->SetY($sety);
            $this->Cell(30,$Alto,'DIAGNOSTICO','LTB',0,'L');
            $this->Ln();
            $this->SetFont('Arial','B',10);
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
            if($Formulas1[13][2]=="Medicina General"){$this->Cell(3,3,'X',1,0,'C');}
            else{$this->Cell(3,3,'',1,0,'C');}
            $this->SetY($sety);$this->SetX($setx+4+40+4);
            $this->Cell(40,5,'Especializado','TB',0,'R');
            $this->SetY($sety+1);$this->SetX($setx+4+40+4+40);
            if($Formulas1[13][2]!="Medicina General"){$this->Cell(3,3,'X',1,0,'C');}
            else{$this->Cell(3,3,'X',1,0,'C');}
            $this->SetY($sety);$this->SetX($setx+4+40+4+40+4);
            $this->Cell(55,5,'ODONTOLOGO','TB',0,'R');
            $this->SetY($sety+1);$this->SetX($setx+4+40+4+40+4+55);
            $this->Cell(3,3,'',1,0,'C');
            $this->SetY($sety);$this->SetX($setx+4+40+4+40+4+55+4);
            $this->Cell(0,5,'','RTB',0,'C');
            $this->Ln();
            $this->Cell(0,5,"Especialidad,    Cual:     ".strtoupper($Formulas1[13][2]),1,0,'L');$this->Ln();
            $this->Cell(0,5,'Nombres y Apellidos',1,0,'C');
            $this->Ln();
            $this->Cell(0,5,$Formulas1[13][1],1,0,'C');
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
                    if($Formulas1[13][0])
                    {
                        if(ereg($Formulas1[13][0],$files))
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
            $this->Cell(35,5,$Formulas1[13][0],1,0,'C');
            $this->Cell(60,5,$Formulas1[13][3],1,0,'C');
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
            $this->Cell(80,5,$Dispensa,1,0,'C');
            $this->Cell(60,5,$CedulaD,1,0,'C');
            $this->Cell(0,5,'',1,0,'C');
            $this->Ln();
            $this->Cell(80,5,'Establecimiento Farmacéutico Minorista',1,0,'C');
            $this->Cell(70,5,'Dirección',1,0,'C');
            $this->Cell(46,5,'Fecha de Despacho',1,0,'C');
            $this->Ln();
            $this->Cell(80,5,$Compania[0],1,0,'C');
            $this->Cell(70,5,$Compania[2],1,0,'C');
            $this->Cell(7,5,'Dia',1,0,'R');
            $this->Cell(7,5,$Dia,1,0,'C');
            $this->Cell(7,5,'Mes',1,0,'R');
            $this->Cell(7,5,$Mes,1,0,'C');
            $this->Cell(9,5,'Año',1,0,'R');
            $this->Cell(9,5,$Anio,1,0,'C');
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
            reset($TarjetaMeds[$Identificacion]);
            while(list($Autoid,$TarjetaMeds1)=each($TarjetaMeds[$Identificacion]))
            {
                if($Medicamento[$Autoid][3]=="Si")
                {
                    $this->Cell(40,3,$Medicamento[$Autoid][0],'LR',0,'L',1);
                    $this->Cell(30,3,$Medicamento[$Autoid][1],'LR',0,'L',1);
                    $this->Cell(20,3,$Medicamento[$Autoid][2],'LR',0,'L',1);
                    $this->Cell(35,3,$Tarjeta[$Identificacion][$Autoid],'LR',0,'L',1);
                    $this->Cell(20,3,'Oral','RL',0,'C',1);
                    $this->Cell(12,3,$TarjetaMeds1,"RL",0,'C',1);
                    $PosPesos = strpos(NumerosxLet($TarjetaMeds1),"pesos");
                    $this->Cell(19,3,substr(NumerosxLet($TarjetaMeds1),0,$PosPesos),"RL",0,'L',1);
                    if($Despachados)
                    {
                        $this->Cell(0,3,$Despachados[$Identificacion][$Autoid],"RL",0,'C',1);
                    }
                    else
                    {
                        $this->Cell(0,3,"","RL",0,'C',1);
                    }
                    $this->Ln();
                }
            }
            $this->Cell(0,0,"",'T',0,'C');
        }
    }
}	

$pdf=new PDF('P','mm','Letter');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',8);
$pdf->BasicTable($Datos);
$pdf->Output();	
?>	

