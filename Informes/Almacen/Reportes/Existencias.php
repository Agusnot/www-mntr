<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/fpdf.php');
	include("Consumo/ObtenerSaldos.php");
	$FechaIni="$Anio-$MesIni-$DiaIni";
	$FechaFin="$Anio-$MesFin-$DiaFin";
	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
	$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
        $VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$ND=getdate();
	if(!$PDF){
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
		<tr><td colspan="13"><center><strong><? echo strtoupper($Compania[0])?><br>
		<? echo $Compania[1]?><br>EXISTENCIAS GENERALES - <? echo $AlmacenPpal?><br>Corte a: <? echo $Fecha?></td></tr>
		<tr><td colspan="13" align="right">Fecha de Impresi&oacute;n <? echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>

<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    <td rowspan="2">Codigo</td><td rowspan="2">Nombre</td><td colspan="2">Saldo Inicial</td>
    <td colspan="5">Movimientos Periodo</td><td colspan="2">Saldo Final</td><td rowspan="2">Costo Unidad</td>
</tr>
<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    <td>Cantidad</td><td>Valor</td>
    <td>Entradas</td><td>Valor</td>
    <td>Salidas</td><td>Valor</td>
    <td>Devoluciones</td>
    <td>Cantidad</td><td>Valor</td>
</tr>
		
<?	}

	Encabezados();}
	$cons="Select Codigo1,NombreProd1,UnidadMedida,Presentacion,AutoId from Consumo.CodProductos where Compania='$Compania[0]' 
	and AlmacenPpal='$AlmacenPpal' and Anio=$Anio order by NombreProd1,UnidadMedida,Presentacion";
	$res=ExQuery($cons);
	$TotVrSaldoIni1=0;$TotVrEntradas1=0;$TotVrSalidas1=0;$TotSaldoFinal=0;
	while($fila=ExFetch($res))
	{
		//echo $VrDevoluciones[$fila[4]][1]."-------".$VrDevoluciones[$fila[4]][0]."<br>";
                $CantFinal=$VrSaldoIni[$fila[4]][0]+$VrEntradas[$fila[4]][0]-$VrSalidas[$fila[4]][0]+$VrDevoluciones[$fila[4]][0];
		$SaldoFinal=$VrSaldoIni[$fila[4]][1]+$VrEntradas[$fila[4]][1]-$VrSalidas[$fila[4]][1]+$VrDevoluciones[$fila[4]][1];
		if($CantFinal>0){$CostoUnidad=$SaldoFinal/$CantFinal;}
		else{$CostoUnidad=0;}
		if($VrSaldoIni[$fila[4]][0] || $VrSaldoIni[$fila[4]][1] || $VrEntradas[$fila[4]][0] || $VrEntradas[$fila[4]][1] || $VrSalidas[$fila[4]][0] || $VrSalidas[$fila[4]][1] || $CantFinal || $SaldoFinal) {$Muestre=1;}
		if($Muestre){
		if(!$PDF){
		?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><?
		if($VrSaldoIni[$fila[4]][0]==0){$VrSaldoIni[$fila[4]][1]=0;}
        if($VrEntradas[$fila[4]][0]==0){$VrEntradas[$fila[4]][1]=0;}
        if($VrSalidas[$fila[4]][0]==0){$VrSalidas[$fila[4]][1]=0;}
        if($VrDevoluciones[$fila[4]][0]==0){$VrDevoluciones[$fila[4]][1]=0;}
        if($CantFinal==0){$SaldoFinal=0;}
        echo"<td align='center'>$fila[0]</td><td>$fila[1] $fila[2] $fila[3]</td>
		<td align='right'>".number_format($VrSaldoIni[$fila[4]][0],2)."</td><td align='right'>".number_format($VrSaldoIni[$fila[4]][1],2)."</td>
		<td align='right'>".number_format($VrEntradas[$fila[4]][0],2)."</td><td align='right'>".number_format($VrEntradas[$fila[4]][1],2)."</td>
		<td align='right'>".number_format($VrSalidas[$fila[4]][0],2)."</td><td align='right'>".number_format($VrSalidas[$fila[4]][1],2)."</td>
		<td align='right'>".number_format($VrDevoluciones[$fila[4]][0],2)."</td>
                <td align='right'>".number_format($CantFinal,2)."</td><td align='right'>".number_format($SaldoFinal,2)."</td>
		<td align='right'>".number_format($CostoUnidad,2)."</td></tr>";}
		$TotVrSaldoIni1=$TotVrSaldoIni1+$VrSaldoIni[$fila[4]][1];
		$TotVrEntradas1=$TotVrEntradas1+$VrEntradas[$fila[4]][1];
		$TotVrSalidas1=$TotVrSalidas1+$VrSalidas[$fila[4]][1]-$VrDevoluciones[$fila[4]][1];
                
		$TotSaldoFinal=$TotSaldoFinal+$SaldoFinal;
		$NumRec++;
		$Datos[$NumRec]=array($fila[0],"$fila[1] $fila[2] $fila[3]",$VrSaldoIni[$fila[4]][0],$VrSaldoIni[$fila[4]][1],
                    $VrEntradas[$fila[4]][0],$VrEntradas[$fila[4]][1],$VrSalidas[$fila[4]][0],$VrSalidas[$fila[4]][1],$VrDevoluciones[$fila[4]][0],
                    $CantFinal,$SaldoFinal,$CostoUnidad);
		$Muestre=0;}
	}
	if(!$PDF){
	echo "<tr bgcolor='#e5e5e5' style='font-weight=bold; font-size=12px'><td colspan='2' align='right'>TOTALES</td>
	<td align='right'></td><td align='right'>".number_format($TotVrSaldoIni1,2)."</td>
	<td align='right'></td><td align='right'>".number_format($TotVrEntradas1,2)."</td>
	<td align='right'></td><td align='right'>".number_format($TotVrSalidas1,2)."</td>
        <td align='right'></td><td align='right'>".number_format($TotSaldoFinal,2)."</td><td align='right'></td></tr>";
?>
</table>
</body>
</html>
<?	}
$NumRec++;
$Datos[$NumRec]=array("","TOTALES","",$TotVrSaldoIni1,"",$TotVrEntradas1,"",$TotVrSalidas1,"",$TotSaldoFinal,"");

class PDF extends FPDF
{//192,168.1.110
	function BasicTable($data)
	{
		$Anchos=array(10,70,15,22,15,22,15,22,15,15,22,20);
		$fill=false;$this->SetFillColor(248,248,248);
		if(!$data){exit;}
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
				$POSY=$this->GetY();
				if($x==1){$col=utf8_decode(substr($col,0,25));}
				if($x>1){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				if($col=="TOTALES"){$Final=1;$Alinea="R";}
				if($Final)
				{
					$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',7);$Lines="LRBT";
				}
				else
				{
				if($POSY>=187 && $POSY<192){$Lines="LRB";}
				else{$Lines="LR";}
				}
				$this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,$Alinea,$fill);
				$x++;
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
    $this->Cell(0,8,"INFORME DE EXISTENCIAS GENERALES",0,0,'C');
    $this->Ln(4);
    $this->Cell(0,8,"PERIODO: $Anio-$MesIni-$DiaIni a $Anio-$MesFin-$DiaFin",0,0,'C');
    $this->Ln(10);
    $this->SetFillColor(228,228,228);
    $this->SetFont('Arial','B',8);

    $this->Cell(10,10,"Cod",1,0,'C',1);
    $this->Cell(70,10,"Nombre",1,0,'C',1);
    $this->Cell(37,5,"Saldo Inicial",1,0,'C',1);
    $this->Cell(89,5,"Movimientos del Periodo",1,0,'C',1);
    $this->Cell(37,5,"Saldo Final",1,0,'C',1);
    $this->Cell(20,10,"Costo Unidad",1,0,'C',1);
    $this->Ln(5);
    $this->Cell(80,5,"",0,0,'C');
    $this->Cell(15,5,"Cant",1,0,'C',1);
    $this->Cell(22,5,"Valor",1,0,'C',1);

    $this->Cell(15,5,"Entr",1,0,'C',1);
    $this->Cell(22,5,"Valor",1,0,'C',1);

    $this->Cell(15,5,"Salid",1,0,'C',1);
    $this->Cell(22,5,"Valor",1,0,'C',1);

    $this->Cell(15,5,"Dev",1,0,'C',1);
    //$this->Cell(22,5,"Valor",1,0,'C',1);

    $this->Cell(15,5,"Cant",1,0,'C',1);
    $this->Cell(22,5,"Valor",1,0,'C',1);

    $this->Ln(5);
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

$pdf->BasicTable($Datos);
$pdf->Cell(263,0,"","B",0,'C');

$pdf->Ln(20);
$pdf->Cell(0,8,"____________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(0,8,"RESPONSABLE SUMINISTROS",0,0,'C');
$pdf->Ln(5);

if($PDF){$pdf->Output();}
?>
