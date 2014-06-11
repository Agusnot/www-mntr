<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
require('LibPDF/rotation.php');
$ND=getdate();
$hora = getdate(strtotime("$Anio-$MesIni-01"));
$cons = "Select NumDias,Mes from Central.Meses Where Numero = $MesIni";
$res = ExQuery($cons);
$fila=ExFetch($res);
$NumDias = $fila[0];
$MesInforme = strtoupper($fila[1]);
$cons = "Select AutoId,Responsable,PrimApe,SegApe,PrimNom,SegNom,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos,SubUbicacion
From Infraestructura.Ubicaciones,Central.CentrosCosto,Central.Terceros Where Ubicaciones.Compania='$Compania[0]'
and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and Ubicaciones.Responsable = Terceros.Identificacion
and CentrosCosto.Codigo = Ubicaciones.CentroCostos and CentrosCosto.Anio = $Anio order by AutoId,FechaFin asc";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
	if(!$fila[8]){$fila[8]="N/A";}
	$Ubicacion[$fila[0]] = array("$fila[1]-$fila[2] $fila[3] $fila[4] $fila[5]","$fila[6] - $fila[7]","$fila[8]");
}
if($Encargado){$AndEncargado = " and Encargado = '$Encargado'";}
$cons = "Select FechAgenda,Mantenimiento.AutoId,Codigo,Nombre,Grupo,DetalleSolicitud,PrimApe,SegApe,PrimNom,SegNom,horaini
from Infraestructura.Mantenimiento, Infraestructura.CodElementos, Central.Terceros
Where Mantenimiento.AutoId = CodElementos.AutoId and Mantenimiento.Encargado = Terceros.Identificacion
and Mantenimiento.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]'
and FechAgenda >= '$Anio-$MesIni-01' and FechAgenda <= '$Anio-$MesIni-$NumDias' $AndEncargado";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $Mantenimientos[$fila[0]][$fila[1]]=array($fila[2],"$fila[3]","$fila[4]","$fila[5]","$fila[6] $fila[7] $fila[8] $fila[9]",$fila[10],$fila[1]);
}
class PDF extends PDF_Rotate
{
    function BasicTable($Mantenimientos,$Ubicacion)
    {
        global $hora; global $MesIni;global $NumDias;global $Anio;
        if($MesIni<10){$MesIni="0".$MesIni;}
        $D = $hora[wday];
        for($i=0;$i<=$D-1;$i++)
        {
            if($i==0){$A = 20;}else{$A = 40;}
            $this->Cell($A,5,"",0,0,'C');
        }
        for($i=1;$i<=$NumDias;$i++)
        {
            if(!$k){$k=$i;}
            if($D==6 || $i==$NumDias){$B="'TR'";}else{$B="'T'";}
            if($D==0)
            {

                $this->SetFillColor(228,228,228);
                $this->SetFont('Arial','B',6);
                $this->Cell(5,3,"$i",1,0,'C',1);
                $this->Cell(15,3,"",$B,0,'C');
            }
            else
            {
                $this->SetFont('Arial','',6);
                $this->Cell(5,3,"$i",1,0,'C');
                $this->Cell(35,3,"",$B,0,'C');
            }
            $D++;
            if($i<10){$i="0".$i;}
            if(count($Mantenimientos["$Anio-$MesIni-$i"])>$CuentaMant)
            {
                $CuentaMant = count($Mantenimientos["$Anio-$MesIni-$i"]);
            }
            if($D==7 || $i==$NumDias)
            {
                $D=0;
                $this->Ln();
                if(!$x)
                {
                    $DD = $hora[wday];
                    for($ii=0;$ii<=$DD-1;$ii++)
                    {
                        if($ii==0){$A = 20;}else{$A = 40;}
                        $this->Cell($A,9,"",0,0,'C');
                    }
                }
                $x=1;
                if($i<10){$ii=str_replace("0","",$i);}
                else{$ii=$i;}
                for($j=$k;$j<=$ii;$j++)
                {
                    $Alto = $CuentaMant * 60;
                    if($Alto==0){$Alto=3;}
                    if($DD==0){$Ancho = 20;}else{$Ancho = 40;}
                    //$this->Cell(20,$Alto,"$CuentaMant",'LRB',0,'C');
                    if($j<10){$j="0".$j;}
                    if($Mantenimientos["$Anio-$MesIni-$j"])
                    {
                        $sety = $this->getY();
                        foreach($Mantenimientos["$Anio-$MesIni-$j"] as $Elemento)
                        {
                            $setx = $this->getX();
                            $this->MultiCell($Ancho,3,"$Elemento[5]",'LRB','C');
                            $this->setX($setx);
                            $this->MultiCell($Ancho,3,"Codigo: $Elemento[0]",'LR');
                            $this->setX($setx);
                            $this->MultiCell($Ancho,3,utf8_decode("Elemento: $Elemento[1]"),'LR');
                            $this->setX($setx);
                            $this->MultiCell($Ancho,3,utf8_decode("Grupo: $Elemento[2]"),'LR');
                            $this->setX($setx);
                            $this->MultiCell($Ancho,3,utf8_decode("Detalle: $Elemento[3]"),'LR');
                            $this->setX($setx);
                            $this->MultiCell($Ancho,3,utf8_decode(strtoupper("Encargado Mantenimiento: $Elemento[4]")),'LRB');
                            $this->setX($setx);
							/////////////////////////////////////////////////////////////////////////////////////////////////
							$this->SetFillColor(228,228,228);
							$this->MultiCell($Ancho,3,utf8_decode(strtoupper("RESPONSABLE: ".$Ubicacion[$Elemento[6]][0])),'TLR','J',1);
                            $this->setX($setx);
							$this->MultiCell($Ancho,3,utf8_decode(strtoupper("UBICACION: ".$Ubicacion[$Elemento[6]][1])),'LR','J',1);
                            $this->setX($setx);
							$this->MultiCell($Ancho,3,utf8_decode(strtoupper("SUBUBICACION: ".$Ubicacion[$Elemento[6]][2])),'LRB','J',1);
                            $this->setX($setx);
                        }
                        if(($this->getY())<($sety+$Alto))
                        {
                            $this->Cell($Ancho,($sety+$Alto)-$this->getY(),"",'LRB',0,'C');
                        }
                        $this->setY($sety);
                        $this->setX($setx+$Ancho);
                    }
                    else{$this->Cell($Ancho,$Alto,"SIN ASIGNAR",'LRB',0,'C');}
                    $DD++;
                    if($DD==7){$DD=0;}
                }
                unset($k);
                $this->Ln();
                if($this->getY()>140 && $this->getY()<190){$this->AddPage();}
                $CuentaMant = 0;
            }
        }
    }
    function Header()
    {
        global $Compania;global $Anio;global $MesIni;global $ND; global $MesInforme;
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
        $this->Cell(0,5,"INFORME DE MANTENIMIENTOS PREVENTIVOS PARA EL MES DE $MesInforme",0,0,'C');
        $this->Ln(9);
        //ANCHO DE LA PAGINA:260
        $this->SetFillColor(228,228,228);
        $this->SetFont('Arial','B',10);
        $this->Cell(20,5,"Domingo",1,0,'C',1);
        $this->Cell(40,5,"Lunes",1,0,'C',1);
        $this->Cell(40,5,"Martes",1,0,'C',1);
        $this->Cell(40,5,"Miercoles",1,0,'C',1);
        $this->Cell(40,5,"Jueves",1,0,'C',1);
        $this->Cell(40,5,"Viernes",1,0,'C',1);
        $this->Cell(40,5,"Sabado",1,0,'C',1);
        $this->Ln();
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
$pdf->BasicTable($Mantenimientos,$Ubicacion);
$pdf->Output();
?>