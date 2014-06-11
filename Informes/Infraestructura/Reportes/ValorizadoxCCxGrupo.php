<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
require('LibPDF/fpdf.php');
$ND = getdate();
$Fecha = "$Anio-$MesIni-$DiaIni";
if(!$PDF)
{
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<body>
<?
	function Encabezados()
	{
		global $Compania;global $Fecha;global $NumPag;global $TotPaginas;global $ND;
		?>
		<table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
		<tr><td colspan="11"><center><strong><? echo strtoupper($Compania[0])?><br>
		<? echo $Compania[1]?><br>VALORIZADO TOTAL POR CENTROS DE COSTO <br>Corte a: <? echo "$Fecha"?></td></tr>
		<tr><td colspan="11" align="right">Fecha de Impresi&oacute;n <? echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
        <td colspan="2">Grupo</td><td>Costo Inicial</td><td>Ajuestes x Inf</td><td>Depreciaci&oacute;n Acumulada</td><td>Ajuestes x Inf</td><td>Saldo</td></tr>
    <?	}
Encabezados();	
}
///DEPRECIACIONES EJECUTADAS Centros de Costo
$cons = "Select CentroCostos,SUM(VrDepreciacion),Grupo From Infraestructura.Ubicaciones,Infraestructura.Depreciaciones,Infraestructura.CodElementos
Where Ubicaciones.AutoId = Depreciaciones.AutoId and Ubicaciones.AutoId = CodElementos.AutoId and FechaDepreciacion <='$Fecha'
and Depreciaciones.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' 
Group by CentroCostos,Grupo order by CentroCostos,Grupo";
//echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $DepCCG[$fila[0]][$fila[2]]=$fila[1];
    $Dep[$fila[0]] = $Dep[$fila[0]] + $DepCCG[$fila[0]][$fila[2]];
}
////////////////////
//Elementos dados de Baja
$cons = "Select AutoId,Fecha from Infraestructura.Bajas Where Compania='$Compania[0]' and Clase = 'Devolutivos'";
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
     $fila[1]=str_replace("-0","-",$fila[1]);
    if($Fecha>=$fila[1])
    {
        if(!$Notin){$Notin=$fila[0];}
        else{$Notin=$Notin.",$fila[0]";}
        $AutoidNotin = " and Ubicaciones.AutoId not In($Notin)";
    }
}
$cons = "Select Ubicaciones.CentroCostos, CentrosCosto.CentroCostos,SUM(CostoInicial),SUM(DepAcumulada),SUM(AxICostoIni),SUM(AxIDepAcumulada)
From Infraestructura.Ubicaciones,Central.CentrosCosto,Infraestructura.CodElementos
Where Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]'
and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.AutoId = CodElementos.AutoId
and FechaIni<='$Fecha' and (FechaFin >='$Fecha' or FechaFin IS NULL)
and (CodElementos.Tipo != 'Orden Compra' and (EstadoCompras IS NULL or EstadoCompras='Ingresado'))
and (UVE IS NOT NULL) AND (Eliminado IS NULL or Eliminado != 1) and CentrosCosto.Anio=$Anio $AutoidNotin
group by Ubicaciones.CentroCostos, CentrosCosto.CentroCostos order by Ubicaciones.CentroCostos";
//echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
	$Depreciacion = $fila[3] + $Dep[$fila[0]];
	$Saldo = $fila[2] - $Depreciacion;
	$TotalCostoIni[$fila[0]] = $TotalCostoIni[$fila[0]] + $fila[2];
        $TotalAxICI[$fila[0]] = $TotalAxICI[$fila[0]] + $fila[4];
	$TotalDeprecia[$fila[0]] = $TotalDeprecia[$fila[0]] + $Depreciacion;
	$TotalAxIDA[$fila[0]] = $TotalAxIDA[$fila[0]] + $fila[4];
        $TotalSaldo[$fila[0]] = $TotalSaldo[$fila[0]] + $Saldo;
	$Datos[$fila[0]]=array("$fila[0] - $fila[1]",$fila[2],$fila[4],$Depreciacion,$fila[5],$Saldo);
}
////////////////////
$cons = "Select Ubicaciones.CentroCostos, CentrosCosto.CentroCostos,SUM(CostoInicial),SUM(DepAcumulada), Grupo, SUM(AxICostoIni),SUM(AxIDepAcumulada)
From Infraestructura.Ubicaciones,Central.CentrosCosto,Infraestructura.CodElementos 
Where Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]'
and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.AutoId = CodElementos.AutoId
and FechaIni<='$Fecha' and (FechaFin >='$Fecha' or FechaFin IS NULL)
and (CodElementos.Tipo = 'Levantamiento Inicial' or ( CodElementos.Tipo = 'Compras' and EstadoCompras='Ingresado')
and UVE IS NOT NULL) AND (Eliminado IS NULL or Eliminado != 1) and CentrosCosto.Anio=$Anio
group by Ubicaciones.CentroCostos, CentrosCosto.CentroCostos,Grupo order by Ubicaciones.CentroCostos";
//echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
	$DepreciacionCCG = $fila[3] + $DepCCG[$fila[0]][$fila[4]];
	$SaldoCCG = $fila[2] - $DepreciacionCCG;
	$TotalCostoIniCCG = $TotalCostoIniCCG + $fila[2];
	$TotalAxICICCG = $TotalAxICICCG + $fila[5];
        $TotalDepreciaCCG = $TotalDepreciaCCG + $DepreciacionCCG;
	$TotalAxIDACCG = $TotalAxIDACCG + $fila[6];
        $TotalSaldoCCG = $TotalSaldoCCG + $SaldoCCG;
	if(!$PDF)
	{
		if($fila[0] != $CCAnt)
                {
                ?>
                <tr bgcolor="<? echo $Estilo[1] ?>"  style="color:white;font-weight:bold;">
                <td colspan="2"><? echo "$fila[0] - $fila[1]"?></td>
                <td align="right"><? echo number_format($TotalCostoIni[$fila[0]],2);?></td>
                <td align="right"><? echo number_format($TotalAxICI[$fila[0]],2);?></td>
                <td align="right"><? echo number_format($TotalDeprecia[$fila[0]],2);?></td>
                <td align="right"><? echo number_format($TotalAxIDA[$fila[0]],2);?></td>
                <td align="right"><? echo number_format($TotalSaldo[$fila[0]],2);?></td>
                </tr>
                <?
                }
                echo "<tr><td width='5%'>&nbsp;</td><td>$fila[4]</td>
		<td align='right'>".number_format($fila[2],2)."</td>
                <td align='right'>".number_format($fila[5],2)."</td>
		<td align='right'>".number_format($DepreciacionCCG,2)."</td>
                <td align='right'>".number_format($fila[6],2)."</td>
		<td align='right'>".number_format($SaldoCCG,2)."</td></tr>";
		$NumRec++;
		if($NumRec == $Encabezados)
		{ echo "</table><br />"; Encabezados();$NumRec=0;}
		$CCAnt = $fila[9];
	}
	$DatosCCG["$fila[0]"]["$fila[4]"]=array("$fila[4]",$fila[2],$fila[5],$DepreciacionCCG,$fila[6],$SaldoCCG);
        $CCAnt = $fila[0];
}
if(!$PDF)
{
	?><tr bgcolor="#e5e5e5" style="font-weight:bold">
    <td align="right" colspan="2">TOTAL</td>
    <td align="right"><? echo number_format($TotalCostoIniCCG,2)?></td>
    <td align="right"><? echo number_format($TotalDepreciaCCG,2)?></td>
    <td align="right"><? echo number_format($TotalSaldoCCG,2)?></td></tr><?
}
if($PDF)
{
	class PDF extends FPDF
	{
		function BasicTable($data,$data1)
		{
			//echo $data1["010101"]["Equipo de Computo y Comunicacion"][0]."*****";
                        global $TotalCostoIni; global $TotalDeprecia; global $TotalSaldo;
			$Anchos=array(90,30,30,30,30,50);
			foreach($data as $row)
                        {
                                $x=0;
                                $fill=false;$this->SetFillColor(248,248,248);
                                $this->SetFont('Arial','B',8);
                                foreach($row as $col)
                                {
                                        if($x==0){$CC = explode("-",$col);}
                                        $POSY=$this->GetY();
                                        if($POSY>=250 && $POSY<255){$Lines="LRB";}
                                        else{$Lines="LR";}
                                        if($x==1){$col = substr(utf8_decode($col),0,58);}
                                        if($x>0){$Alinea='R';$col = number_format($col);}
                                        else{$Alinea = 'L';}
                                        $this->Cell($Anchos[$x],5,strtoupper($col),1,0,$Alinea,$fill);
                                        $x++;
                                        if($x==6)
                                        {
                                            $this->Ln();
                                            $this->SetFont('Arial','',8);
                                            foreach ($data1[trim("$CC[0]")] as $row1)
                                            {
                                                $x1=0;
                                                foreach ($row1 as $col1)
                                                {
                                                    $POSY=$this->GetY();
                                                    if($POSY>=185 && $POSY<190){$Lines="LRB";}
                                                    else{$Lines="LR";}
                                                    if($x1==1){$col1 = substr(utf8_decode($col1),0,60);}
                                                    if($x1>0){$Alinea='R';$col1 = number_format($col1);}
                                                    else{$Alinea = 'L';}
                                                    $this->Cell($Anchos[$x1],5,strtoupper($col1),$Lines,0,$Alinea,$fill);
                                                    $x1++;
                                                }
                                                $this->Ln();
                                            }
                                        }
                                }
                                $fill=!$fill;
                        }
		}
	
	function Header()
	{
		global $Compania;global $Anio;global $MesIni;global $DiaIni;global $MesFin;global $DiaFin;
		$this->SetFont('Arial','B',10);
		$this->Cell(0,8,strtoupper($Compania[0]),0,0,'C');
		$this->Ln(4);
		$this->SetFont('Arial','B',8);
		$this->Cell(0,8,strtoupper($Compania[1]),0,0,'C');
		$this->Ln(4);
		$this->Cell(0,8,"INFORME DE EXISTENCIAS TOTALIZADO POR CENTRO DE COSTOS",0,0,'C');
		$this->Ln(4);
		$this->Cell(0,8,"Corte a: $Anio-$MesIni-$DiaIni",0,0,'C');
		$this->Ln(10);
		$this->SetFillColor(228,228,228);
		$this->SetFont('Arial','B',8);
	
		$this->Cell(90,5,"Grupo",1,0,'C',1);
		$this->Cell(30,5,"Costo Inicial",1,0,'C',1);
                $this->Cell(30,5,"Ajustes x Inf",1,0,'C',1);
		$this->Cell(30,5,"Dep. Acumulada",1,0,'C',1);
                $this->Cell(30,5,"Ajustes x Inf",1,0,'C',1);
		$this->Cell(50,5,"Saldo",1,0,'C',1);
		$this->Ln(5);
		//$this->Ln(5);
	}
	
	//Pie de página
	function Footer()
	{
		global $ND;
		//Posición: a 1,5 cm del final
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Número de página
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		$this->Ln(3);
		$this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
	}
	}
	
	$pdf=new PDF('L','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',8);
	//echo $DatosCCG["010101"]["Equipo de Computo y Comunicacion"][0];exit;
	$pdf->BasicTable($Datos,$DatosCCG);
	$pdf->Cell(263,0,"","B",0,'C');
	
	$pdf->Ln(20);
	$pdf->Cell(0,8,"____________________________________",0,0,'C');
	$pdf->Ln(5);
	$pdf->Cell(0,8,"RESPONSABLE SUMINISTROS",0,0,'C');
	$pdf->Ln(5);
	
	if($PDF){$pdf->Output();}	
}
?>
