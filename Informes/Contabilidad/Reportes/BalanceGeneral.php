<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	include_once("General/Configuracion/Configuracion.php");
	global  $borderTablaInfContable, $bordercolorTablaInfContable,  $cellspacingTablaInfContable, $cellpaddingTablaInfContable;
	
	
	require('LibPDF/fpdf.php');
	if(!$CuentaIni){$CuentaIni=0;}
	if(!$CuentaFin){$CuentaFin=9999999999;}
	$PerFin="$Anio-$MesFin-$DiaFin";
	$ND=getdate();
	if(!$PDF)
	{
	function Encabezados()
	{
		global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;
		global  $borderTablaInfContable, $bordercolorTablaInfContable,  $cellspacingTablaInfContable, $cellpaddingTablaInfContable;
		?>
		<table  rules="groups"  width="100%" class="tablaInformeContable" style="text-align:justify;" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
		<tr><td colspan="8" style="text-align:center;font-weight:bold;"><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>BALANCE GENERAL<br>CORTE A: <?echo $PerFin?></td></tr>
		<tr><td colspan="8" style="text-align:center;font-weight:bold;">FECHA DE IMPRESI&Oacute;N <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		<tr>
			<td class='encabezado2HorizontalInfCont'>C&Oacute;DIGO</td>
			<td class='encabezado2HorizontalInfCont'>DESCRIPCI&Oacute;N</td>
			<td class='encabezado2HorizontalInfCont' style="text-align:right;padding-right:10px;">SALDO</td>
		</tr>
		<?
			if($IncluyeCC=="on")
			{
				echo "<td class='encabezado2HorizontalInfCont' rowspan=2>CC</td>";
			}
		}

	$NumRec=0;$NumPag=1;
	Encabezados();
	

?>
<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
		</head>	
		<body <?php echo $backgroundBodyInfContableMentor;?>>

		<?	}
			$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";

			$res=ExQuery($cons,$conex);
			while($fila=ExFetchArray($res))
			{
				$Nivel++;$TotNivel++;
				if(!$fila[0]){$fila[0]="-100";}
				$TotCaracteres=$TotCaracteres+$fila[0];
				$Digitos[$Nivel]=$TotCaracteres;
			}

			$cons3="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as Anio from Contabilidad.Movimiento 
			where Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and $ExcluyeComprobantes 
			Group By Cuenta,Anio,Fecha Order By Cuenta";
			$res3=ExQuery($cons3);
			while($fila3=ExFetch($res3))
			{
				$CuentaMad=substr($fila3[2],0,1);
				if(($CuentaMad==0) && $Anio!=$fila3[3]){}
				else{
				for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
				{
					$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
					if($ParteAnt!=$ParteCuenta){
					$MPCuenta[$ParteCuenta]['debitos']=$MPCuenta[$ParteCuenta]['debitos']+$fila3[0];
					$MPCuenta[$ParteCuenta]['creditos']=$MPCuenta[$ParteCuenta]['creditos']+$fila3[1];}
					$ParteAnt=$ParteCuenta;
				}}
			}


			$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos from Contabilidad.PlanCuentas 
			where (Cuenta ilike '0%' Or Cuenta ilike '1%' Or Cuenta ilike '2%' Or Cuenta ilike '3%') and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Compania='$Compania[0]'
			and Anio=$Anio
			and length(Cuenta)<=$NoDigitos 
			Group By Cuenta,Nombre,Tipo,Naturaleza Order By Cuenta";


			$resCta=ExQuery($consCta); 

			while($filaCta=ExFetchArray($resCta))
			{
				if(!$PDF)
				{
					if($NumRec>=$Encabezados)
					{
						echo "</table><P>&nbsp;</P>";
						$NumPag++;
						Encabezados();
						$NumRec=0;
					}
				}
				$Debitos=$MPCuenta[$filaCta[0]]['debitos'];
				$Creditos=$MPCuenta[$filaCta[0]]['creditos'];

				if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
				if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
				elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}

				if($IncluyeCeros=="S"){$Muestre=1;}
				if($SaldoF!=0){$Muestre=1;}
				if(strlen($filaCta[0])==1){$Muestre=1;}
				if($Muestre==1)
				{
					$NumRec++;
					if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
					else{$BG="white";$Fondo=1;}
					if(!$PDF)
					{
						echo "<tr bgcolor='$BG'>";
						echo "<td>";
						echo "$filaCta[0]</td><td>$filaCta[1]</td><td style='text-align:right;padding-right:10px;'>" . number_format($SaldoF,2) . "</td></tr>";
					}
					$Datos[$NumRec]=array($filaCta[0],$filaCta[1],$SaldoF);
				}

				if($filaCta[0]=="1"){$TotActivo=$SaldoF;}
				if($filaCta[0]=="2"){$TotPasivo=$SaldoF;}
				if($filaCta[0]=="3"){$TotPatrimonio=$SaldoF;}
				$Muestre="N";
				$SaldoI=0;
			}
			$SumHaber=$TotPatrimonio+$TotPasivo;
			$Dif=$TotActivo-$SumHaber;
			if($Dif<0){$DifDebe=abs($Dif);$DifHaber=0;}
			if($Dif>0){$DifDebe=0;$DifHaber=abs($Dif);}

			$BuscCargos=array("Representante","Contador");
			foreach($BuscCargos as $GenCargos)
			{
				$cons="Select Nombre,Cargo from Central.CargosxCompania where Compania='$Compania[0]' and FechaIni<='$PerFin' and FechaFin>='$PerFin' and Categoria='$GenCargos'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);

				$DatoCargo[$GenCargos][0]=$fila[0];
				$DatoCargo[$GenCargos][1]=$fila[1];
			}

			if(!$PDF)
			{
				?>
				</table>
				<div align="center">
					<table class="tablaInformeContable" style="text-align:justify;" border="0" cellspacing="0" cellpadding="5">
						<tr>
							<td style="text-align:left; padding-left: 7px; font-weight: bold;">ACTIVO</td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;"><?echo number_format($TotActivo,2)?></td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;">0.00</td>
						</tr>
						<tr>
							<td style="text-align:left; padding-left: 7px; font-weight: bold;">PASIVO</td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;">0.00</td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;"><?echo number_format($TotPasivo,2)?></td>
						</tr>
						<tr>
							<td style="text-align:left; padding-left: 7px; font-weight: bold;">PATRIMONIO</td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;">0.00</td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;"><?echo number_format($TotPatrimonio,2)?></td>
						</tr>
						<tr>
							<td style="text-align:left; padding-left: 7px; font-weight: bold;">DIFERENCIA</td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;"  ><? echo number_format($DifDebe,2) ?></td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;"><? echo number_format($DifHaber,2) ?></td>
						</tr>
						<tr>
							<td style="text-align:left; padding-left: 7px; font-weight: bold;">SUMAS IGUALES</td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;"><?echo number_format($TotActivo+$DifDebe,2)?></td>
							<td style="text-align:right; padding-left: 5px; padding-right: 5px; font-weight: normal;"><?echo number_format($SumHaber+$DifHaber,2)?></td>
							<td> &nbsp;</td>
						</tr>

					</table>
					
					<br><br><br>
					<table border="0">
						<tr>
							<td>______________________________</td><td style="width:130px;"></td>
							<td>______________________________</td><td style="width:130px;"></td>
						</tr>
						<tr>
							<td style="color:#000;font-weight:bold;"><? echo $DatoCargo['Representante'][0]?></td>
							<td> &nbsp; </td>
							<td style="color:#000;font-weight:bold;"><? echo $DatoCargo['Contador'][0]?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td style="color:#000;font-weight:bold;"><? echo $DatoCargo['Representante'][1]?></td><td></td><td><? echo $DatoCargo['Contador'][1] ?></td>
						</tr>
					</table>
				</div>	

				</body>
				<?
			}

class PDF extends FPDF
{
	function BasicTable($data)
	{
		$Anchos=array(40,110,50);
		if(count($data)==0){exit;}
		
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
				if($x==1){$col=substr($col,0,80);}
				if($x>1){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				if($col=="SUMAS"){$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',8);}
				$this->Cell($Anchos[$x],5,$col,1,0,$Alinea,$fill);
				$x++;
			}
			$this->Ln();
		}
	}

//Cabecera de página
function Header()
{
	global $Compania;global $PerFin;
    //Logo
//    $this->Image('/Imgs/Logo.jpg',10,8,33);
    //Arial bold 15
    $this->SetFont('Arial','B',12);
    //Movernos a la derecha

    //Título
    $this->Cell(0,8,strtoupper($Compania[0]),0,0,'C');
    //Salto de línea
    $this->Ln(5);
    $this->SetFont('Arial','B',10);
    $this->Cell(0,8,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(5);
    $this->Cell(0,8,"BALANCE GENERAL",0,0,'C');
    $this->Ln(5);
    $this->Cell(0,8,"CORTE: $PerFin",0,0,'C');
    $this->Ln(10);
    $this->Cell(40,5,"Codigo",1,0,'C');
    $this->Cell(110,5,"Descripcion",1,0,'C');
    $this->Cell(50,5,"Saldo",1,0,'C');
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

$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$pdf->BasicTable($Datos);

$pdf->SetFont('Arial','B',10);
$pdf->Ln(20);
$pdf->Cell(50,5,"",0,0,'C');
$pdf->Cell(30,5,"ACTIVO",0,0,'L');
$pdf->Cell(40,5,number_format($TotActivo,2),0,0,'R');
$pdf->Cell(40,5,number_format(0,2),0,0,'R');

$pdf->Ln(5);
$pdf->Cell(50,5,"",0,0,'C');
$pdf->Cell(30,5,"PASIVO",0,0,'L');
$pdf->Cell(40,5,number_format(0,2),0,0,'R');
$pdf->Cell(40,5,number_format($TotPasivo,2),0,0,'R');

$pdf->Ln(5);
$pdf->Cell(50,5,"",0,0,'C');
$pdf->Cell(30,5,"PATRIMONIO",0,0,'L');
$pdf->Cell(40,5,number_format(0,2),0,0,'R');
$pdf->Cell(40,5,number_format($TotPatrimonio,2),0,0,'R');

$pdf->Ln(5);
$pdf->SetFillColor(218,218,218);
$pdf->Cell(50,5,"",0,0,'C');
$pdf->Cell(30,5,"DIFERENCIA",0,0,'L',1);
$pdf->Cell(40,5,number_format($DifDebe,2),0,0,'R',1);
$pdf->Cell(40,5,number_format($DifHaber,2),0,0,'R',1);

$pdf->Ln(5);
$pdf->Cell(50,5,"",0,0,'C');
$pdf->Cell(30,5,"SUMAS IGUALES",0,0,'L');
$pdf->Cell(40,5,number_format($TotActivo+$DifDebe,2),0,0,'R');
$pdf->Cell(40,5,number_format($SumHaber+$DifHaber,2),0,0,'R');

$pdf->Ln(30);
$pdf->Cell(97,8,"____________________________________",0,0,'C');
$pdf->Cell(97,8,"____________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(97,8,$DatoCargo['Representante'][0],0,0,'C');
$pdf->Cell(97,8,$DatoCargo['Contador'][0],0,0,'C');
$pdf->Ln(5);
$pdf->Cell(97,8,$DatoCargo['Representante'][1],0,0,'C');
$pdf->Cell(97,8,$DatoCargo['Contador'][1],0,0,'C');


if($PDF){$pdf->Output();}
?>