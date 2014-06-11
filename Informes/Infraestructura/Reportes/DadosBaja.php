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
		<? echo $Compania[1]?><br>INFORME DE ELEMENTOS ELIMINADOS <br>Corte a: <? echo "$Fecha"?></td></tr>
		<tr><td colspan="11" align="right">Fecha de Impresi&oacute;n <? echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
        <td>Codigo</td><td>Elemento</td><td>Ultimo Responsable</td><td>Ultimo CC</td><td>Fecha</td><td>Usuario</td></tr>
    <?	}
Encabezados();	
}
///Ubicacion de Elementos Eliminados
$cons = "Select Ubicaciones.autoid,responsable,primape,segape,primnom,segnom,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos
From Infraestructura.Ubicaciones,Infraestructura.CodElementos,Central.Terceros,Central.CentrosCosto
Where Ubicaciones.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]'
and Ubicaciones.AutoId = CodElementos.AutoId and Ubicaciones.Responsable=Terceros.Identificacion and Ubicaciones.CentroCostos=CentrosCosto.Codigo
and CodElementos.Tipo='Baja' order by FechaFin asc";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
	$UltUb[$fila[0]] = array("$fila[2] $fila[3] $fila[4] $fila[5]","$fila[6] - $fila[7]");	
}
$cons = "Select Bajas.AutoId,Bajas.Codigo,Nombre,Caracteristicas,Modelo,Serie,Bajas.usuarioCrea,Bajas.Fecha
From Infraestructura.CodElementos,Infraestructura.Bajas Where Bajas.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]'
and CodElementos.AutoId = Bajas.AutoId And CodElementos.Tipo='Baja' order by Fecha";
//echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
	
	$fila[7]=substr($fila[7],0,10);
	$FechaI = explode("-",$Fecha);if($FechaI[1]<10){$FechaI[1]="0".$FechaI[1];}if($FechaI[2]<10){"0".$FechaI[2];}
	$FechaE = explode("-",$fila[7]);if($FechaE[1]<10){$FechaE[1]="0".$FechaE[1];}if($FechaE[2]<10){"0".$FechaE[2];}
	
	//echo "$FechaElimina<=$Fecha";
	if("$FechaE[0]-$FechaE[1]-$FechaE[2]"<="$FechaI[0]-$FechaI[1]-$FechaI[2]")
	{
		if(!$PDF)
		{
			if(!$UltUb[$fila[0]][0]){$UltUb[$fila[0]][0]=" &nbsp; ";}
			if(!$UltUb[$fila[0]][1]){$UltUb[$fila[0]][1]=" &nbsp; ";}
			echo "<tr><td>$fila[1]</td><td>$fila[2] $fila[3] $fila[4] $fila[5]</td><td>".$UltUb[$fila[0]][0]."</td><td>".$UltUb[$fila[0]][1]."</td>
                        <td align='center'>$fila[7]</td><td>$fila[6]</td>";
			$NumRec++;
			if($NumRec == $Encabezados)
			{ echo "</table><br />"; Encabezados();$NumRec=0;}		
		}
		$Datos[$fila[0]]=array($fila[1],"$fila[2] $fila[3] $fila[4] $fila[5]",$UltUb[$fila[0]][0],$UltUb[$fila[0]][1],$fila[7],$fila[6]);
	}
}
if($PDF)
{
	class PDF extends FPDF
	{
		function BasicTable($data)
		{
			global $TotalCostoIni; global $TotalDeprecia; global $TotalSaldo;
			$Anchos=array(20,65,60,50,20,50);
			foreach($data as $row)
			{
				$x=0;
				$fill=false;$this->SetFillColor(248,248,248);
				foreach($row as $col)
				{
					$POSY=$this->GetY();
					if($POSY>=185 && $POSY<190){$Lines="LRB";}
					else{$Lines="LR";}
					if($x==1){$col = substr(utf8_decode($col),0,35);}
					if($x==2){$col = substr(utf8_decode($col),0,30);}
					if($x==3){$col = substr(utf8_decode($col),0,28);}
					if($x==4){$col = substr(utf8_decode($col),0,28);}
					//if($x>0){$Alinea='R';$col = number_format($col);}
					else{$Alinea = 'L';}		
					$this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,$Alinea,$fill);
					$x++;
					if($x==6){break;}
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
		$this->Cell(0,8,"INFORME DE ELEMENTOS ELIMINADOS",0,0,'C');
		$this->Ln(4);
		$this->Cell(0,8,"Corte a: $Anio-$MesIni-$DiaIni",0,0,'C');
		$this->Ln(10);
		$this->SetFillColor(228,228,228);
		$this->SetFont('Arial','B',8);
	
		$this->Cell(20,5,"Codigo",1,0,'C',1);
		$this->Cell(65,5,"Elemento",1,0,'C',1);
		$this->Cell(60,5,"Ultimo Responsable",1,0,'C',1);
		$this->Cell(50,5,"Ultimo Centro Costos",1,0,'C',1);
		$this->Cell(20,5,"Fecha",1,0,'C',1);
                $this->Cell(50,5,"Usuario Baja",1,0,'C',1);
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
	$pdf->Cell(265,0,"","B",0,'C');
	
	$pdf->Ln(20);
	$pdf->Cell(0,8,"____________________________________",0,0,'C');
	$pdf->Ln(5);
	$pdf->Cell(0,8,"RESPONSABLE SUMINISTROS",0,0,'C');
	$pdf->Ln(5);
	
	if($PDF){$pdf->Output();}	
}
?>
