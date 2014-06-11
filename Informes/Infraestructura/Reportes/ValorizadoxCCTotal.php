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
        <td>Centro de Costos</td><td>Costo Inicial</td><td>Ajuestes x Inf</td><td>Depreciaci&oacute;n Acumulada</td><td>Ajuestes x Inf</td><td>Saldo</td></tr>
    <?	}
Encabezados();	
}
///DEPRECIACIONES EJECUTADAS
$cons = "Select CentroCostos,SUM(VrDepreciacion) From Infraestructura.Ubicaciones,Infraestructura.Depreciaciones
Where Ubicaciones.AutoId = Depreciaciones.AutoId and FechaDepreciacion <='$Fecha' Group by CentroCostos order by CentroCostos";
$res = ExQuery($cons);
while($fila = ExFetch($res)){$Dep[$fila[0]] = $fila[1];}
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
	$TotalCostoIni = $TotalCostoIni + $fila[2];
        $TotalAxICI = $TotalAxICI + $fila[4];
	$TotalDeprecia = $TotalDeprecia + $Depreciacion;
        $TotalAxIDA = $TotalAxIDA + $fila[5];
	$TotalSaldo = $TotalSaldo + $Saldo;
	if(!$PDF)
	{
		echo "<tr><td>$fila[0] - $fila[1]</td>
		<td align='right'>".number_format($fila[2],2)."</td>
                <td align='right'>".number_format($fila[4],2)."</td>
		<td align='right'>".number_format($Depreciacion,2)."</td>
                <td align='right'>".number_format($fila[5],2)."</td>
		<td align='right'>".number_format($Saldo,2)."</td></tr>";
		$NumRec++;
		if($NumRec == $Encabezados)
		{ echo "</table><br />"; Encabezados();$NumRec=0;}
		$CCAnt = $fila[9];
	}
	$Datos[$fila[0]]=array("$fila[0] - $fila[1]",$fila[2],$fila[4],$Depreciacion,$fila[5],$Saldo);
}
if(!$PDF)
{
	?><tr bgcolor="#e5e5e5" style="font-weight:bold">
    <td align="right">TOTAL</td>
    <td align="right"><? echo number_format($TotalCostoIni,2)?></td>
    <td align="right"><? echo number_format($TotalAxICI,2)?></td>
    <td align="right"><? echo number_format($TotalDeprecia,2)?></td>
    <td align="right"><? echo number_format($TotalAxIDA,2)?></td>
    <td align="right"><? echo number_format($TotalSaldo,2)?></td></tr><?	
}
if($PDF)
{
	class PDF extends FPDF
	{
		function BasicTable($data)
		{
			global $TotalCostoIni; global $TotalDeprecia; global $TotalSaldo;
			$Anchos=array(90,30,30,30,30,50);
                        foreach($data as $row)
                        {
                                $x=0;
                                $fill=false;$this->SetFillColor(248,248,248);
                                foreach($row as $col)
                                {
                                        $POSY=$this->GetY();
                                        if($POSY>=185 && $POSY<190){$Lines="LRB";}
                                        else{$Lines="LR";}
                                        if($x==1){$col = substr(utf8_decode($col),0,58);}
                                        if($x>0){$Alinea='R';$col = number_format($col);}
                                        else{$Alinea = 'L';}
                                        $this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,$Alinea,$fill);
                                        $x++;
                                        if($x==7){break;}
                                }
                                $this->Ln();
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
	
		$this->Cell(90,5,"Centro de Costos",1,0,'C',1);
		$this->Cell(30,5,"Costo Inicial",1,0,'C',1);
                $this->Cell(30,5,"Ajustes x Inf",1,0,'C',1);
		$this->Cell(30,5,"Dep. Acumulada",1,0,'C',1);
                $this->Cell(30,5,"Ajustes x Inf",1,0,'C',1);
		$this->Cell(50,5,"Saldo",1,0,'C',1);
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
	$pdf->SetFont('Arial','',8);
	
	$pdf->BasicTable($Datos);
	$pdf->Cell(263,0,"","B",0,'C');
	
	$pdf->Ln(20);
	$pdf->Cell(0,8,"____________________________________",0,0,'C');
	$pdf->Ln(5);
	$pdf->Cell(0,8,"RESPONSABLE SUMINISTROS",0,0,'C');
	$pdf->Ln(5);
	
	if($PDF){$pdf->Output();}	
}
?>
