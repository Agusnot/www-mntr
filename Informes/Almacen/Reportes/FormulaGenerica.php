<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
//echo $TarjetaMeds;
//require('LibPDF/fpdf.php');
//UM:03-05-2011
require('LibPDF/rotation.php');
$ND = getdate();
if($ND[mon]<10){$Mes = "0".$ND[mon];}else{$Mes = $ND[mon];}
if($ND[mday]<10){$Dia = "0".$ND[mday];}else{$Dia = $ND[mday];}
//Datos Orden
$Despachados = array();
if($Urgente)
{
    $AdconsTipoM=" tipoorden='Medicamento Urgente'";
    //if(!$Despacho)
    //{
        $cons = "Select cedula,Movimiento.Autoid,Numero,fecha,numorden,idescritura,cantidad,control,numerocontrolados
        from consumo.movimiento,consumo.codproductos where Movimiento.Compania='$Compania[0]'
        and codproductos.Compania='$Compania[0]' and Codproductos.AlmacenPpal='$AlmacenPpal'
        and Movimiento.AlmacenPpal = '$AlmacenPpal' and Movimiento.Autoid = CodProductos.Autoid
        and Movimiento.Estado = 'AC' and Comprobante = 'Salidas Urgentes' and Cedula = '$Cedula'";//echo $cons;
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            $Despachados[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = $fila[6];
            $TotalDespacho[$fila[1]] = $TotalDespacho[$fila[1]] + $fila[6];
            $D[$fila[3]][$fila[0]][$fila[1]][$fila[4]][$fila[5]] = $fila[6];
            $Fecha[$fila[0]][$fila[1]] = $fila[3];//echo $fila[3];
            if($fila[3]=="$ND[year]-$ND[mon]-$ND[mday]")
            {
                if($fila[7]=="Si"){$DC[$fila[0]]=$fila[8];}
                if($fila[7]=="No"){$DNC[$fila[0]]=$fila[2];}
            }
            //echo "D:$Cedula----$fila[1]-----$fila[4]----$fila[5]<br>";
        }
    //}
}
else{$AdconsTipoM=" (tipoorden='Medicamento Programado' or tipoorden='Medicamento No Programado') and ordenesMedicas.Estado = 'AC'
    and (FechaFin is NULL or FechaFin >= '$ND[year]-$ND[mon]-$ND[mday]')";}

$cons="select ordenesmedicas.detalle,tipoorden,ordenesmedicas.idescritura,
ordenesmedicas.numorden,ordenesmedicas.posologia,plantillamedicamentos.AlmacenPpal,AutoidProd,CantDiaria,Control
from salud.ordenesmedicas,salud.plantillamedicamentos,consumo.CodProductos
where ordenesmedicas.Compania='$Compania[0]' and plantillamedicamentos.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
and plantillamedicamentos.AutoidProd = Codproductos.Autoid and plantillamedicamentos.AlmacenPpal=Codproductos.AlmacenPpal
and plantillamedicamentos.Almacenppal = '$AlmacenPpal' and plantillamedicamentos.NumOrden = ordenesmedicas.NumOrden
and plantillamedicamentos.IdEscritura = ordenesmedicas.IdEscritura
and cedpaciente = cedula and cedula='$Cedula'
and plantillamedicamentos.estado='AC' 
and ordenesmedicas.detalle = plantillamedicamentos.detalle and Control='$Tipo'
and $AdconsTipoM order by fecha";
//echo $cons;
$res=ExQuery($cons);
$C = 0;
while($fila=ExFetch($res))
{
    $fila[4] = str_replace("Suministrar ","",$fila[4]);
    $fila[4] = str_replace("a las ","(",$fila[4]);
    $fila[4] = str_replace(", ",") - ",$fila[4]);
    $Datos[$fila[3]][$C]=array($fila[0],$fila[4],$fila[5],$fila[6],$fila[7],$fila[3],$fila[2]);
    $C++;
    //echo "O:$Cedula----$fila[6]-----$fila[3]----$fila[2]<br>";
}
$cons = "Select AlmacenPpal,Autoid,NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos Where
Compania='$Compania[0]' and anio=$ND[year]";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $DetalleMedicamento[$fila[0]][$fila[1]] = array($fila[2],$fila[3],$fila[4]);
}
//Datos Paciete
$cons="select cedula,(primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom ),terceros.nocarnet,terceros.tipousu,terceros.nivelusu,autorizac1,autorizac2,autorizac3
from salud.servicios,central.terceros
where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and identificacion=cedula and Identificacion='$Cedula'";
$res=ExQuery($cons);
$DatPaciente=ExFetch($res);
$consxxx = "Select NumServicio,Cedula from salud.Servicios Where Cedula='$Cedula'";
$resxxx = ExQuery($consxxx);
$Servcxxx = ExFetch($resxxx);
//Datos Cliente
$cons="select (primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom ),
pagadorxservicios.entidad,pagadorxservicios.contrato,pagadorxservicios.nocontrato,
direccion,telefono,tipoasegurador,codigosgsss,planbeneficios,planservmeds
from salud.pagadorxservicios,central.terceros,contratacionsalud.contratos
where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and
pagadorxservicios.entidad=identificacion and NumServicio = $Servcxxx[0]
and contratos.compania='$Compania[0]' and contratos.entidad=pagadorxservicios.entidad and pagadorxservicios.contrato=contratos.contrato
and numero=nocontrato";
//echo $cons;
$res=ExQuery($cons);
$Cliente=ExFetch($res);

$cons="select nombreplan from contratacionsalud.planeservicios where compania='$Compania[0]' and autoid=$Cliente[9] and clase='Medicamentos'";
$res=ExQuery($cons);
$PlanMeds=ExFetch($res);
$cons="select nombreplan from contratacionsalud.planeservicios where compania='$Compania[0]' and autoid=$Cliente[8] and clase!='Medicamentos'";
$res=ExQuery($cons);
$PlanServ=ExFetch($res);
class PDF extends PDF_Rotate
{
	function Header1($Ini)
	{
                global $Numero;
                $this->AddPage();
		global $Compania; global $IdEscritura; global $Tipo;
		$Raiz = $_SERVER['DOCUMENT_ROOT'];
		$this->Image("$Raiz/Imgs/Logo.jpg",10,7,20,20);
		$this->SetFont('Arial','B',12);
		$this->Cell(25,5,"",0,0,'L');
		$this->Cell(90,5,utf8_decode(substr(strtoupper($Compania[0]),0,50)),0,0,'L');
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
		$this->Cell(90,5,strtoupper($Compania[1]),0,0,'L');
		$this->SetFont('Arial','B',16);
		$this->Cell($Tamano+1,10," $Numero ","LRB",0,'C');
		//$this->Cell($Tamano+1,10," 8589541 ","LRB",0,'C');
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
	function Titulos($Ini){
		//Titulos
		if($Ini==1){
			$this->Ln(8);
		}
		$this->SetFillColor(228,228,228);
		$this->SetFont('Arial','B',8);
		//$this->Cell(94,5,"DESCRIPCION",1,0,'C',1);
		//$this->Cell(100,5,"POSOLOGIA",1,0,'C',1);
                $this->Cell(9,5,"No",1,0,'C',1);
                $this->Cell(60,5,"Medicamento",1,0,'C',1);
                $this->Cell(33,5,"Forma Farmaceutica",1,0,'C',1);
                $this->Cell(22,5,"Concentracion",1,0,'C',1);
                $this->Cell(40,5,"POSOLOGIA",1,0,'C',1);
                $this->Cell(30,5,"Cantidad",1,0,'C',1);
		$this->SetFont('Arial','',8);
	}
	function BasicTable($Datos){
                global $DetalleMedicamento;global $Despachados;global $Cedula;
                global $Fecha; global $Urgente; global $Despacho;global $Mes;global $Dia; global $ND;
		$this->Header1(1);
		$this->Header2();
		$this->Titulos(1);
		if($Datos)
                {
                    $C = 1;
                    foreach($Datos as $Datos1)
                    {
                        foreach($Datos1 as $Meds)
                        {
                            if($Urgente)
                            {
                                if($Despacho)
                                {
                                    if($Fecha[$Cedula][$Meds[3]]=="$ND[year]-$Mes-$Dia")
                                    {
                                        $this->Ln(5);
                                        $POSY=$this->GetY();
                                        if($POSY>=106.00125&&$POSY<=111.00125){
                                            $this->Cell(194,5,'','T',0,'C');
                                            $this->Header1(0);
                                            $this->Titulos(0);
                                            $this->Ln();
                                        }
                                        $this->SetFont('Arial','',6);

                                        $this->Cell(9,5,$C,'LR',0,'R');
                                        $this->Cell(60,5,utf8_decode($DetalleMedicamento[$Meds[2]][$Meds[3]][0]),'LR',0,'L');
                                        $this->Cell(33,5,utf8_decode($DetalleMedicamento[$Meds[2]][$Meds[3]][2]),'LR',0,'L');
                                        $this->Cell(22,5,utf8_decode($DetalleMedicamento[$Meds[2]][$Meds[3]][1]),'LR',0,'L');
                                        $this->Cell(40,5,substr($Meds[1],0,60),'LR',0,'L');
                                        $PosPesos = strpos(NumerosxLet($Meds[4]),"pesos");
                                        $CantLetras=substr(NumerosxLet($Meds[4]),0,$PosPesos);
                                        $this->Cell(30,5,$Meds[4]."($CantLetras)",'LR',0,'L');
                                        //$this->MultiCell(94,5,$Meds[0],'LR','C');
                                        //$this->MultiCell(100,5,$Meds[1],'LR','C',0);
                                        $C++;
                                    }
                                }
                                else
                                {
                                    if(!$Despachados[$Cedula][$Meds[3]][$Meds[5]][$Meds[6]])
                                    {
                                        $this->Ln(5);
                                        $POSY=$this->GetY();
                                        if($POSY>=106.00125&&$POSY<=111.00125){
                                            $this->Cell(194,5,'','T',0,'C');
                                            $this->Header1(0);
                                            $this->Titulos(0);
                                            $this->Ln();
                                        }
                                        $this->SetFont('Arial','',6);

                                        $this->Cell(9,5,$C,'LR',0,'R');
                                        $this->Cell(60,5,utf8_decode($DetalleMedicamento[$Meds[2]][$Meds[3]][0]),'LR',0,'L');
                                        $this->Cell(33,5,utf8_decode($DetalleMedicamento[$Meds[2]][$Meds[3]][2]),'LR',0,'L');
                                        $this->Cell(22,5,utf8_decode($DetalleMedicamento[$Meds[2]][$Meds[3]][1]),'LR',0,'L');
                                        $this->Cell(40,5,substr($Meds[1],0,60),'LR',0,'L');
                                        $PosPesos = strpos(NumerosxLet($Meds[4]),"pesos");
                                        $CantLetras=substr(NumerosxLet($Meds[4]),0,$PosPesos);
                                        $this->Cell(30,5,$Meds[4]."($CantLetras)",'LR',0,'L');
                                        //$this->MultiCell(94,5,$Meds[0],'LR','C');
                                        //$this->MultiCell(100,5,$Meds[1],'LR','C',0);
                                        $C++;
                                    }
                                }
                            }
                            else
                            {
                                $this->Ln(5);
                                $POSY=$this->GetY();
                                if($POSY>=106.00125&&$POSY<=111.00125){
                                    $this->Cell(194,5,'','T',0,'C');
                                    $this->Header1(0);
                                    $this->Titulos(0);
                                    $this->Ln();
                                }
                                $this->SetFont('Arial','',6);

                                $this->Cell(9,5,$C,'LR',0,'R');
                                $this->Cell(60,5,utf8_decode($DetalleMedicamento[$Meds[2]][$Meds[3]][0]),'LR',0,'L');
                                $this->Cell(33,5,utf8_decode($DetalleMedicamento[$Meds[2]][$Meds[3]][2]),'LR',0,'L');
                                $this->Cell(22,5,utf8_decode($DetalleMedicamento[$Meds[2]][$Meds[3]][1]),'LR',0,'L');
                                $this->Cell(40,5,substr($Meds[1],0,60),'LR',0,'L');
                                $PosPesos = strpos(NumerosxLet($Meds[4]),"pesos");
                                $CantLetras=substr(NumerosxLet($Meds[4]),0,$PosPesos);
                                $this->Cell(30,5,$Meds[4]."($CantLetras)",'LR',0,'L');
                                //$this->MultiCell(94,5,$Meds[0],'LR','C');
                                //$this->MultiCell(100,5,$Meds[1],'LR','C',0);
                                $C++;
                            }
                        }
                    }
                }
		//}
		if($POSY<=101.00125)
                {
                    $this->Ln(5);
                    $this->Cell(194,5,'','T',0,'C');
		}
		else
                {
                    $this->Ln(5);
                    $this->Cell(194,5,'','T',0,'C');
		}
		$this->Ln(10);
		if($POSY>=106.00125-$Incre && $POSY<111.00125-$Incre){$this->Header1(0);}
		else
                {
                    $this->Cell(10,5,"",0,0,'D');
                    $this->Cell(80,5,"QUIEN FORMULA","T",0,'C');
                    $this->Cell(15,5,"",0,0,'D');
                    $this->Cell(80,5,"QUIEN RECIBE","T",0,'C');
		}
	}
}
$Hoja=array('216','140');
$pdf=new PDF('P','mm',$Hoja);
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',8);
$pdf->BasicTable($Datos);
$pdf->Output();
?>

