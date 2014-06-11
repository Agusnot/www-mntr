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
		<? echo $Compania[1]?><br>VALORIZADO POR CENTROS DE COSTO <br>Corte a: <? echo "$Fecha"?></td></tr>
		<tr><td colspan="11" align="right">Fecha de Impresi&oacute;n <? echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
        <td>Codigo</td><td>Elemento</td><td>Costo Inicial</td><td>Ajustes x Inf</td><td>Depreciaci&oacute;n Acumulada</td><td>Ajustes x Inf</td><td>Saldo</td></tr>
    <?	}
Encabezados();	
}
///DEPRECIACIONES EJECUTADAS
$cons = "Select AutoId,Sum(VrDepreciacion) from Infraestructura.Depreciaciones Where Compania='$Compania[0]' and FechaDepreciacion <='$Fecha' Group by AutoId";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $Dep[$fila[0]] = $fila[1];
}
//Elementos dados de Baja
$cons = "Select AutoId,Fecha from Infraestructura.Bajas Where Compania='$Compania[0]' and Clase = 'Devolutivos'";
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
    $fila[1]=str_replace("-0","-",$fila[1]);
    $Bajas[$fila[0]]=$fila[1];
}
$cons = "Select CodElementos.AutoId,CodElementos.Codigo,Nombre,Caracteristicas,Modelo,Marca,Serie,CostoInicial,
DepAcumulada,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos, AxICostoIni, AxIDepAcumulada
From Infraestructura.Ubicaciones,Central.CentrosCosto,Infraestructura.CodElementos
Where Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' 
and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.AutoId = CodElementos.AutoId 
and FechaIni<='$Fecha' and (FechaFin >='$Fecha' or FechaFin IS NULL)
and (CodElementos.Tipo != 'Orden Compra' and (EstadoCompras IS NULL or EstadoCompras='Ingresado'))
and (UVE IS NOT NULL) AND ( Eliminado IS NULL or Eliminado != 1) and CentrosCosto.Anio=$Anio order by Ubicaciones.CentroCostos,CodElementos.Codigo";
//echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    if(!$Bajas[$fila[0]] || "$Fecha"<$Bajas[$fila[0]])
    {
        if(!$PDF)
	{
		if($fila[9] != $CCAnt)
		{
			if($CCAnt)
			{
				echo "<tr bgcolor='$Estilo[1]'  style='color:white;font-weight:bold;'><td colspan='2' align='right'>TOTALIZADO($CCAnt)</td>
				<td align='right'>".number_format($TotalCostoIni[$CCAnt],2)."</td>
                                <td align='right'>".number_format($TotalAxICI[$CCAnt],2)."</td>
				<td align='right'>".number_format($TotalDeprecia[$CCAnt],2)."</td>
                                <td align='right'>".number_format($TotalAxIDA[$CCAnt],2)."</td>
				<td align='right'>".number_format($TotalSaldo[$CCAnt],2)."</td></tr>";
			}
			echo "<tr bgcolor='$Estilo[1]'  style='color:white;font-weight:bold;'><td colspan='9' align='center'>$fila[9] - $fila[10]</td></tr>";
		}
	}
	$Depreciacion = $fila[8] + $Dep[$fila[0]];
	$Saldo = $fila[7] - $fila[8];
	$TotalCostoIni[$fila[9]] = $TotalCostoIni[$fila[9]] + $fila[7];
        $TotalAxICI[$fila[9]] = $TotalAxI[$fila[9]] + $fila[11];
	$TotalDeprecia[$fila[9]] = $TotalDeprecia[$fila[9]] + $Depreciacion;
        $TotalAxIDA[$fila[9]] = $TotalAxI[$fila[9]] + $fila[12];
	$TotalSaldo[$fila[9]] = $TotalSaldo[$fila[9]] + $Saldo;
	if(!$PDF)
	{
		echo "<tr><td>$fila[1]</td><td>$fila[2] $fila[3] $fila[4] $fila[5] $fila[6]</td>
		<td align='right'>".number_format($fila[7],2)."</td>
                <td align='right'>".number_format($fila[11],2)."</td>
                <td align='right'>".number_format($Depreciacion,2)."</td>
                <td align='right'>".number_format($fila[12],2)."</td>
		<td align='right'>".number_format($Saldo,2)."</td></tr>";
		$NumRec++;
		if($NumRec == $Encabezados)
		{ echo "</table><br />"; Encabezados();$NumRec=0;}
		$CCAnt = $fila[9];
	}
	$Datos["$fila[9] - $fila[10]"][$fila[0]]=array($fila[1],"$fila[2] $fila[3] $fila[4] $fila[5] $fila[6]",$fila[7],$fila[11],$Depreciacion,$fila[12],$Saldo,$fila[9]);
    }
	
}
if($PDF)
{
	class PDF extends FPDF
	{
		function BasicTable($data)
		{
			global $TotalCostoIni; global $TotalDeprecia; global $TotalSaldo; global $TotalAxICI; global $TotalAxIDA;
			$Anchos=array(20,105,25,25,25,25,25);
			while(list($cad,$val) = each($data))
			{
				$this->SetFillColor(228,228,228);
				$this->SetFont('Arial','B',8);
				$this->Cell(250,5,"CENTRO DE COSTOS: ".utf8_decode(strtoupper($cad)),'1',0,'L',1);
				$this->SetFont('Arial','',8);
				$this->Ln();
				$fill=false;$this->SetFillColor(248,248,248);
				foreach($val as $Elemento)
				{
					$x=0;
					foreach($Elemento as $col)
					{
						$POSY=$this->GetY();
						if($POSY>=185 && $POSY<190){$Lines="LRB";}
						else{$Lines="LR";}
						if($x==1){$col = substr(utf8_decode($col),0,58);}
						if($x>1){$Alinea='R';$col = number_format($col);}
						else{$Alinea = 'L';}		
						$this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,$Alinea,$fill);
						$x++;
						if($x==7){break;}
					}
					$this->Ln();
					$fill=!$fill;
					$TCIN = $TotalCostoIni[$Elemento[7]];
                                        $TAIC = $TotalAxICI[$Elemento[7]];
                                        $TDEP = $TotalDeprecia[$Elemento[7]];
                                        $TAID = $TotalAxIDA[$Elemento[7]];
					$TSAL = $TotalSaldo[$Elemento[7]];
					
				}
				$this->SetFillColor(228,228,228);
				$this->SetFont('Arial','B',8);
				$this->Cell(125,5,"TOTALIZADO:",'1',0,'R',1);
				$this->Cell(25,5,number_format($TCIN,2),'1',0,'R',1);
                                $this->Cell(25,5,number_format($TAIC,2),'1',0,'R',1);
				$this->Cell(25,5,number_format($TDEP,2),'1',0,'R',1);
                                $this->Cell(25,5,number_format($TAID,2),'1',0,'R',1);
				$this->Cell(25,5,number_format($TSAL,2),'1',0,'R',1);
				$this->SetFont('Arial','',8);
				$this->AddPage();
			}
		}
	
	//Cabecera de página
	function Header()
	{
		global $Compania;global $Anio;global $MesIni;global $DiaIni;global $MesFin;global $DiaFin;
		//Logo
	//    $this->Image('/Imgs/Logo.jpg',10,8,33);
		//Arial bold 15
		$this->SetFont('Arial','B',10);
		$this->Cell(0,8,strtoupper($Compania[0]),0,0,'C');
		$this->Ln(4);
		$this->SetFont('Arial','B',8);
		$this->Cell(0,8,strtoupper($Compania[1]),0,0,'C');
		$this->Ln(4);
		$this->Cell(0,8,"INFORME DE EXISTENCIAS POR CENTRO DE COSTOS",0,0,'C');
		$this->Ln(4);
		$this->Cell(0,8,"Corte a: $Anio-$MesIni-$DiaIni",0,0,'C');
		$this->Ln(10);
		$this->SetFillColor(228,228,228);
		$this->SetFont('Arial','B',8);
	
		$this->Cell(20,5,"Codigo",1,0,'C',1);
		$this->Cell(105,5,"Elemeto",1,0,'C',1);
		$this->Cell(25,5,"Costo Inicial",1,0,'C',1);
                $this->Cell(25,5,"Ajustes x Inf",1,0,'C',1);
		$this->Cell(25,5,"Dep. Acumulada",1,0,'C',1);
                $this->Cell(25,5,"Ajustes x Inf",1,0,'C',1);
		$this->Cell(25,5,"Saldo",1,0,'C',1);
		$this->Ln(5);
		//$this->Ln(5);
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
