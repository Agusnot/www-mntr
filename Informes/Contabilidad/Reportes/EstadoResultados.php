<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/fpdf.php');
	if(!$CuentaIni){$CuentaIni=1;}
	if(!$CuentaFin){$CuentaFin=9999999999;}
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";
	$ND=getdate();
	if(!$PDF){
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
<body>
<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<?
	function Encabezados()
	{
		global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;
		?>
		<table  rules="groups"  width="100%" class="tablaInformeContable" style="text-align:justify; padding:left:10px;" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
		<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>ESTADO DE RESULTADOS<br>CORTE A: <?echo $PerFin?></td></tr>
		<tr><td colspan="8" style="text-align:center;">FECHA DE IMPRESI&Oacute;N <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		
		<tr>
			<td class='encabezado2HorizontalInfCont'> C&Oacute;DIGO</td>
			<td class='encabezado2HorizontalInfCont'>DESCRIPCI&Oacute;N</td>
			<td class='encabezado2HorizontalInfCont' style="text-align:right;padding-right:10px;" >SALDO </td></tr>
		<?
			if($IncluyeCC=="on"){
				echo "<td class='encabezado2HorizontalInfCont' rowspan=2>CC</td>";
			}
		}

	$NumRec=0;$NumPag=1;
	Encabezados();
	}
	$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetchArray($res))
	{
		$Nivel++;$TotNivel++;
		if(!$fila[0]){$fila[0]="-100";}
		$TotCaracteres=$TotCaracteres+$fila[0];
		$Digitos[$Nivel]=$TotCaracteres;
	}

	$cons2="Select sum(Debe),sum(Haber),Cuenta from Contabilidad.Movimiento 
	where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and $ExcluyeComprobantes 
	and date_part('year',Fecha)=$Anio
	Group By Cuenta Order By Cuenta";

	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$SICuenta[$ParteCuenta]['Debitos']=$SICuenta[$ParteCuenta]['Debitos']+$fila2[0];
			$SICuenta[$ParteCuenta]['Creditos']=$SICuenta[$ParteCuenta]['Creditos']+$fila2[1];}
			$ParteAnt=$ParteCuenta;
		}
	}

	$cons3="Select sum(Debe),sum(Haber),Cuenta from Contabilidad.Movimiento 
	where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and $ExcluyeComprobantes Group By Cuenta Order By Cuenta";
	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
		{
			$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$MPCuenta[$ParteCuenta]['Debitos']=$MPCuenta[$ParteCuenta]['Debitos']+$fila3[0];
			$MPCuenta[$ParteCuenta]['Creditos']=$MPCuenta[$ParteCuenta]['Creditos']+$fila3[1];}
			$ParteAnt=$ParteCuenta;
		}
	}

		$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos from Contabilidad.PlanCuentas 
		where (Cuenta ilike '4%' Or Cuenta ilike '5%' Or Cuenta ilike '6%' Or Cuenta ilike '7%') and Cuenta>='$CuentaIni' 
		and Cuenta<='$CuentaFin' and Compania='$Compania[0]' and Anio=$Anio and length(Cuenta)<=$NoDigitos Order By Cuenta";

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
		$Debitos=$MPCuenta[$filaCta[0]]['Debitos'];
		$Creditos=$MPCuenta[$filaCta[0]]['Creditos'];
		$DebitosSI=$SICuenta[$filaCta[0]]['Debitos'];
		$CreditosSI=$SICuenta[$filaCta[0]]['Creditos'];

		if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
		if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
		elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}

		if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
		elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}


			if($IncluyeCeros=="on"){$Muestre=1;}

			if($SaldoF!=0){$Muestre=1;}
			if($Muestre==1){

			$NumRec++;
			if(!$PDF){
			if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
			else{$BG="white";$Fondo=1;}
			
			echo "<tr bgcolor='$BG'>";
			echo "<td>";
			echo "$filaCta[0]</td><td>$filaCta[1]</td><td align='right'>" . number_format($SaldoF,2) . "</td></tr>";}
			$Datos[$NumRec]=array($filaCta[0],$filaCta[1],$SaldoF);
			}


			if($filaCta[0]=="4"){$TotIngresos=$SaldoF;}
			if($filaCta[0]=="5"){$TotEgresos=$SaldoF;}
			if($filaCta[0]=="6"){$TotCostos=$SaldoF;}
			$Muestre="N";
			$SaldoI=0;
		}
		$Resultado=$TotIngresos-$TotEgresos-$TotCostos;
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
<center>
<table border="1" cellpadding="6" rules="groups" bordercolor="#ffffff" style="font-weight:bold;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td>Ingresos</td><td align="right"><?echo number_format($TotIngresos,2)?></td></tr>
<tr><td>Gastos</td><td align="right"><?echo number_format($TotEgresos,2)?></td></tr>
<tr><td>Costos</td><td align="right"><?echo number_format($TotCostos,2)?></td></tr>
<tr><td>Resultado</td><td align="right"><?echo number_format($Resultado,2)?></td></tr>
</table>
<br><br>

<table border="0">
<tr><td>______________________________</td><td style="width:130px;"></td><td>______________________________</td><td style="width:130px;"></td></tr>
<tr style="font-weight:bold;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<td><? echo $DatoCargo['Representante'][0]?></td><td></td><td><? echo $DatoCargo['Contador'][0]?></td><td></td></tr>
<tr style="font-weight:bold;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<td><? echo $DatoCargo['Representante'][1]?></td><td></td><td><? echo $DatoCargo['Contador'][1] ?></td></tr>
</table>
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
				if($x==1){$col=substr($col,0,50);}
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
    $this->Cell(0,8,"ESTADO DE RESULTADOS",0,0,'C');
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

$pdf->Ln(25);
$pdf->Cell(70,8,"",0,0,'L');
$pdf->Cell(30,8,"INGRESOS",0,0,'L');
$pdf->Cell(30,8,number_format($TotIngresos,2),0,0,'R');

$pdf->Ln(8);
$pdf->Cell(70,8,"",0,0,'L');
$pdf->Cell(30,8,"GASTOS",0,0,'L');
$pdf->Cell(30,8,number_format($TotEgresos,2),0,0,'R');

$pdf->Ln(8);
$pdf->Cell(70,8,"",0,0,'L');
$pdf->Cell(30,8,"COSTOS",0,0,'L');
$pdf->Cell(30,8,number_format($TotCostos,2),0,0,'R');

$pdf->Ln(8);
$pdf->SetFillColor(218,218,218);
$pdf->Cell(70,8,"",0,0,'L');
$pdf->Cell(30,8,"RESULTADO",0,0,'L',1);
$pdf->Cell(30,8,number_format($Resultado,2),0,0,'R',1);


$pdf->Ln(50);
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