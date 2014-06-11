<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
require('LibPDF/fpdf.php');
include("Consumo/ObtenerSaldos.php");
$FechaIni="$Anio-01-01";
$FechaFin="$Anio-$MesFin-$DiaFin";
$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
$ND=getdate();
$cons = "Select AutoId,Lote,Vence,Cantidad from Consumo.Lotes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Tipo='Saldo Inicial'
and Numero='$Anio' and Cerrado = 1 order by AutoId, Vence asc";//echo $cons;OK!!
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
	$LotesxVenc[$fila[0]][$fila[1]]=array($fila[2],$fila[3],"-",$fila[3]);
	$CantSI[$fila[0]] = $CantSI[$fila[0]] + $fila[3];
}
$cons = "Select Lotes.AutoId,Lote,Vence,Lotes.Cantidad,Lotes.Numero,Lotes.Tipo
From Consumo.Lotes, Consumo.Movimiento Where Lotes.Compania='$Compania[0]'
and Movimiento.Compania='$Compania[0]' and Lotes.AutoId = Movimiento.AutoId and Lotes.Numero=Movimiento.Numero
and Lotes.Tipo!='Saldo Inicial' and TipoComprobante='Entradas' and Movimiento.Estado='AC' and Lotes.AlmacenPpal='$AlmacenPpal'
and Movimiento.AlmacenPpal='$AlmacenPpal' and Movimiento.Anio=$Anio
and Movimiento.Fecha <= '$FechaFin' and Movimiento.Fecha >= '$FechaIni' order by Lotes.AutoId asc, Vence asc, Lote";
//echo $cons;OK POR AHORA
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
    $LotesxVenc[$fila[0]][$fila[1]]=array($fila[2],$fila[3],$fila[4],$fila[3]);
	$CantEN[$fila[0]] = $CantEN[$fila[0]] + $fila[3];
	//echo $CantEN[$fila[0]];
}

$cons = "Select AutoId,Codigo1,NombreProd1,UnidadMedida,Presentacion
from COnsumo.CodProductos Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
and Anio = $Anio";
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
    $Nombre[$fila[0]] = array($fila[1],"$fila[2] $fila[3] $fila[4]");
}

if(!$PDF)
{
	?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
    <tr><td colspan="11"><center><strong><? echo strtoupper($Compania[0])?><br>
    <? echo $Compania[1]?><br>LOTES POR VENCIMIENTO - <? echo $AlmacenPpal?><br>Corte a: <? echo "$Anio-$MesFin-$DiaFin"?></td></tr>
    <tr><td colspan="11" align="right">Fecha de Impresi&oacute;n <? echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
    </tr>

    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    <td>Codigo</td><td>Nombre</td><td>Lote</td><td>Vencimiento</td><td>Cantidad Inicial</td><td>No Documento</td><td>Cantidad lote</td></tr>
    <?
}    
if($VrEntradas || $VrSaldoIni)
{
	while(list($cad,$val)=each($VrSaldoIni))
	{
		if(!$PDF)
		{
			echo "<tr bgcolor='$Estilo[1]' style='font-weight:bold; color=#FFFFFF'>
			<td align='right'>".$Nombre[$cad][0]."</td>
			<td>".utf8_decode_seguro($Nombre[$cad][1])."</td>
			<td colspan='2'>&nbsp;</td>
			<td align='right'>".number_format($VrSaldoIni[$cad][0]+$VrEntradas[$cad][0],2)."</td>
			<td>&nbsp;</td>
			<td align='right'>".number_format($VrSaldoIni[$cad][0]+$VrEntradas[$cad][0]-$VrSalidas[$cad][0],2)."</td>
			</tr>";
		}
		$DatosCab[$cad]=array($Nombre[$cad][0],$Nombre[$cad][1],"","",
									$VrSaldoIni[$cad][0]+$VrEntradas[$cad][0],"",$VrSaldoIni[$cad][0]+$VrEntradas[$cad][0]-$VrSalidas[$cad][0]);
		$SinLoteSI = $val[0]-$CantSI[$cad];
		$Salidas = $VrSalidas[$cad][0];
		if($SinLoteSI > 0)
		{
			if($Salidas <= $SinLoteSI)
			{
				$SinLoteSID = $SinLoteSI - $Salidas;
				$Salidas = 0;
			}
			else
			{
				$SinLoteSID = 0;
				$Salidas = $Salidas - $SinLoteSI;
			}
			if(!$PDF)
			{
				echo "<tr><td colspan='2' align='center'>Saldo Inicial</td><td>sin lote</td>
				<td align='right'>sin vencimiento</td>
				<td align='right'>".number_format($SinLoteSI,2)."</td>
				<td align='center'>-</td>
				<td align='right'>".number_format($SinLoteSID,2)."</td></tr>";
			}
			$Lotes[$cad]["SLSI"]=array("","","Sin lote (Saldo Inicial)","Sin vencimiento",$SinLoteSI,"",$SinLoteSID);
		}
		$SinLoteEN = $VrEntradas[$cad][0]-$CantEN[$cad];
		if($SinLoteEN>0)
		{
			if($Salidas<=$SinLoteEN)
			{
				$SinLoteEND = $SinLoteEN - $Salidas;
				$Salidas = 0;
			}
			else
			{
				$Salidas = $Salidas - $SinLoteEN;
				$SinLoteEND = 0;
			}
			if(!$PDF)
			{
				echo "<tr><td colspan='2' align='center'>Entradas</td><td>sin lote</td>
				<td align='right'>sin vencimiento</td>
				<td align='right'>".number_format($SinLoteEN,2)."</td>
				<td align='center'>-</td>
				<td align='right'>".number_format($SinLoteEND,2)."</td></tr>";
			}
			$Lotes[$cad]["SLEN"]=array("","","Sin lote (Entradas)","Sin vencimiento",$SinLoteEN,"",$SinLoteEND);
		}
		if($LotesxVenc[$cad])
		{
			while(list($cad1,$val1)=each($LotesxVenc[$cad]))
			{
				if($Salidas<=$val1[3])
				{
					$val1[3] = $val1[3] - $Salidas;
					$Salidas = 0;
				}
				else
				{
					$Salidas = $Salidas - $val1[3];
					$val1[3] = 0;
				}
				if(!$PDF)
				{
					echo "<tr><td colspan='2'>&nbsp;</td><td>$cad1</td>
					<td align='right'>$val1[0]</td>
					<td align='right'>".number_format($val1[1],2)."</td>
					<td align='center'>$val1[2]</td>
					<td align='right'>".number_format($val1[3],2)."</td></tr>";
				}
				$Lotes[$cad][$cad1]=array("","",$cad1,$val1[0],$val1[1],$val1[2],$val1[3]);
			}
		}
	}
}
if(!$PDF)
{
	?></table><?
}

if($PDF)
{
		
	class PDF extends FPDF
	{
		function BasicTable($DatosCab,$Lotes)
		{
			if(!$DatosCab){echo "No hay datos";exit;}
			$Anchos = array(10,70,30,23,20,17,20);
			while(list($AutoId,$Datos)=each($DatosCab))
			{
				$x=0;
				$this->SetFillColor(200,200,200);
				foreach($Datos as $Celda)
				{
					if($x!=1){$Alinea = 'R';}
					else{$Alinea = 'L';}
					if($x==1){$Celda = utf8_decode($Celda);}
					if($x>1)
					{
						if($Celda){$Celda = number_format($Celda,2);}
					}
					$this->Cell($Anchos[$x],5,$Celda,1,0,$Alinea,1);
					$x++;
				}
				$this->Ln();
				if($Lotes[$AutoId])
				{
					foreach($Lotes[$AutoId] as $FilaLotes)
					{
						$xx=0;
						foreach($FilaLotes as $CeldaLotes)
						{
							if($xx==4 || $xx==6){$Alinea='R';$CeldaLotes = number_format($CeldaLotes,2);}
							else
							{
								if($xx==5){$Alinea='C';}
								else{$Alinea='L';}
							}
							$this->Cell($Anchos[$xx],5,$CeldaLotes,1,0,$Alinea);
							$xx++;
						}
						$this->Ln();
					}
				}
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
			$this->Cell(0,8,"INFORME DE LOTES POR VENCIMIENTO",0,0,'C');
			$this->Ln(4);
			$this->Cell(0,8,"CORTE: $Anio-$MesFin-$DiaFin",0,0,'C');
			$this->Ln(10);
			$this->SetFillColor(228,228,228);
			$this->SetFont('Arial','B',8);
		
			$this->Cell(10,5,"Cod",1,0,'C',1);
			$this->Cell(70,5,"Nombre",1,0,'C',1);
			$this->Cell(30,5,"Lote",1,0,'C',1);
			$this->Cell(23,5,"Vencimiento",1,0,'C',1);
			$this->Cell(20,5,"Cant. Inicial",1,0,'C',1);
			$this->Cell(17,5,"Doc. Entrada",1,0,'C',1);
			$this->Cell(20,5,"Cant. Lote",1,0,'C',1);
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
	
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',8);
	$pdf->BasicTable($DatosCab,$Lotes);
	$pdf->Ln(20);
	$pdf->Cell(0,8,"____________________________________",0,0,'C');
	$pdf->Ln(5);
	$pdf->Cell(0,8,"RESPONSABLE SUMINISTROS",0,0,'C');
	$pdf->Ln(5);
	if($PDF){$pdf->Output();}

}
    