<?
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();
	include("Informes.php");	
	require('LibPDF/fpdf.php');
	$ND=getdate();
	if($ND[mon]<10){$cero1="0";}
	if($ND[mday]<10){$cero2="0";}
/*?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td colspan="10" style="font-weight:bold" align="center">
		<? echo $Compania[0]?><BR><? echo $Compania[1]?><br>Usuarios Repetidos Base de Datos Afiliados<br>Fecha <? echo "$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]"?>
    </td>
</tr>
<tr colspan="10" style="font-weight:bold" align="center" bgcolor="#e5e5e5">
	<td>Identificacion</td><td>Nombre</td><td>No. Repeticiones</td>
</tr>
*/
	
	$FilasActua=explode(",",$Actualizados);
	foreach($FilasActua as $ActualizaFinal){	
		//echo $ActualizaFinal."<br>";
		if($ActualizaFinal[0]){
			$NumRec++;
			$cons="Select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$ActualizaFinal'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$Datos[$NumRec]=array($ActualizaFinal,$fila[0]." ".$fila[1]." ".$fila[2]." ".$fila[3]);
		}
	}
	/*	?>
			<tr>
    	    	<td align="center"><? echo $fila[1]?></td>	<td><? echo "$fila[2] $fila[3] $fila[4] $fila[5]";?></td><td align="center"><? echo $fila[0]?></td>	
			</tr>
<?		}	
	}

/*?>

</table>
</body>
</html>
<? */
class PDF extends FPDF
{//192,168.1.110
	function BasicTable($data)
	{
		$Anchos=array(35,130);    
		$fill=false;$this->SetFillColor(248,248,248);
		if(!$data){exit;}
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
				$POSY=$this->GetY();
				if($x==1){$col=substr($col,0,52);}
				$Alinea='C';
				//if($x>6){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				//if($col=="TOTALES"){$Final=1;$Alinea="R";}
				if($Final)
				{
					$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',7);$Lines="LRBT";
				}
				else
				{
				if($POSY>=250 && $POSY<260){$Lines="LRB";}
				else{$Lines="LR";}
				}
				$this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,$Alinea,$fill);
				$x++;
			}
			$this->Ln();
			$fill=!$fill;
			
		}
	}

//Cabecera de página
	function Header()
	{
		global $Compania; global $ND;
		//Logo
	//    $this->Image('/Imgs/Logo.jpg',10,8,33);
		//Arial bold 15
		$this->SetFont('Arial','B',10);
		//Movernos a la derecha
	
		//Título
		$this->Cell(0,8,strtoupper($Compania[0]),0,0,'C');
		//Salto de línea
		$this->Ln(4);
		$this->SetFont('Arial','B',8);
		$this->Cell(0,8,strtoupper($Compania[1]),0,0,'C');
		$this->Ln(4);
		$this->Cell(0,8,"INFORME USUARIOS ACTUALIZADOS EN LA BASE DE DATOS TERCEROS",0,0,'C');   	
		$this->Ln(10);
		$this->SetFillColor(228,228,228);
		$this->SetFont('Arial','B',8);
		//$this->SetX(100); 
		$this->Cell(35,5,"Identificacion",1,0,'C',1);
		$this->Cell(130,5,"Nombre",1,0,'C',1);		
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

$pdf=new PDF('P','mm','Letter'); //P horizontal L vertical
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$pdf->BasicTable($Datos);
$pdf->Cell(165,0,"","B",0,'C');

/*$pdf->Ln(20);
$pdf->Cell(0,8,"____________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(0,8,"RESPONSABLE SUMINISTROS",0,0,'C');
$pdf->Ln(5);*/

$pdf->Output();
?>