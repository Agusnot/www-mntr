<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/fpdf.php');
	if(!$CuentaIni){$CuentaIni=0;}
	if(!$CuentaFin){$CuentaFin=9999999999;}
	$ND=getdate();
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";

	$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
	$res=ExQuery($cons,$conex);
	for($Nivel=1;$Nivel<=6;$Nivel++)
	{
		$fila=ExFetchArray($res);
		if(!$fila[0]){$fila[0]="-100";}
		$TotCaracteres=$TotCaracteres+$fila[0];
		if($TotCaracteres==$NoDigitos){$NivelSel=$Nivel;}
		$Digitos[$Nivel]=$TotCaracteres;
	}
	
	$cons2="Select sum(Debe),sum(Haber),Movimiento.Cuenta from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Fecha>='$PerIni' and Fecha<='$PerFin' and date_part('year',Fecha)=$Anio and 
	Movimiento.Compania='$Compania[0]' and PlanCuentas.Compania='$Compania[0]' and PlanCuentas.Anio=$Anio
	and Estado='AC' and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin' Group By Movimiento.Cuenta Order By Cuenta";

	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		for($Nivel=1;$Nivel<=6;$Nivel++)
		{
			$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){
			$MACuenta[$ParteCuenta]['Debitos']=$MACuenta[$ParteCuenta]['Debitos']+$fila2[0];
			$MACuenta[$ParteCuenta]['Creditos']=$MACuenta[$ParteCuenta]['Creditos']+$fila2[1];}
			$ParteAnt=$ParteCuenta;
		}
	}


	$cons2="Select sum(Debe),sum(Haber),Movimiento.Cuenta,Comprobante from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Fecha>='$PerIni' and Fecha<='$PerFin' and date_part('year',Fecha)=$Anio and Movimiento.Compania='$Compania[0]'
	and PlanCuentas.Compania='$Compania[0]'
	 and PlanCuentas.Anio=$Anio and Movimiento.Cuenta!='0' and Movimiento.Cuenta!='1'
	and Estado='AC' and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin' Group By Movimiento.Cuenta,Comprobante Order By Cuenta";

	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		for($Nivel=1;$Nivel<=6;$Nivel++)
		{
			$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
			if($ParteAnt!=$ParteCuenta){

			$MPCuenta[$ParteCuenta]['Debitos'][$fila2[3]]=$MPCuenta[$ParteCuenta]['Debitos'][$fila2[3]]+$fila2[0];
			$MPCuenta[$ParteCuenta]['Creditos'][$fila2[3]]=$MPCuenta[$ParteCuenta]['Creditos'][$fila2[3]]+$fila2[1];}
			$ParteAnt=$ParteCuenta;
		}
	}
	if(!$PDF)
	{
?>

<body>

	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>LIBRO DIARIO<br>Periodo: <?echo $PerIni?> a <?echo $PerFin?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresi&oacute;n <?echo "$PerFin"?></td>
	</table>

<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<?
	}
	$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos from Contabilidad.PlanCuentas 
	where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Compania='$Compania[0]' and PlanCuentas.Anio=$Anio 
	Group By Cuenta,Nombre,Tipo,Naturaleza
	having length(Cuenta)=$NoDigitos 
	Order By Cuenta";
	$resCta=ExQuery($consCta);echo ExError();

	while($filaCta=ExFetchArray($resCta))
	{
		$Total=count($MPCuenta[$filaCta[0]]);
		if( $Total>0 )
		{
			$NumRec++;
			$Datos[$NumRec]=array("CUENTA: $filaCta[0] $filaCta[1]");
			if(!$PDF){
			echo "<table border='1' bordercolor='white' rules='groups' width='100%'  style='font-family:$Estilo[8];font-size:12px;font-style:$Estilo[10]'>";
			echo "<tr bgcolor='#e5e5e5'><td><strong><center>Cuenta $filaCta[0] $filaCta[1]</td></tr>";
			echo "<tr><td colspan=2>";?>
			<table border="1" width="100%" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
<?
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold'><td>Comprobante</td><td align='right'>Debitos</td><td align='right'>Creditos</td></tr>";}
			$cons2="Select Comprobante from Contabilidad.Comprobantes where Compania='$Compania[0]'";
			$res2=ExQuery($cons2);
			while($fila2=ExFetch($res2))
			{
				$NumRec++;
				if($MPCuenta[$filaCta[0]]['Debitos'][$fila2[0]] || $MPCuenta[$filaCta[0]]['Creditos'][$fila2[0]])
				{
					if($filaCta[4]==$NoDigitos)
					{
						$Datos[$NumRec]=array($fila2[0],$MPCuenta[$filaCta[0]]['Debitos'][$fila2[0]],$MPCuenta[$filaCta[0]]['Creditos'][$fila2[0]]);
						if(!$PDF)
						{
							echo "<tr><td style='width:80%'>$fila2[0]";
							echo "<td align='right' style='width:10%'>".number_format($MPCuenta[$filaCta[0]]['Debitos'][$fila2[0]],2)."</td>";
							echo "<td align='right' style='width:10%'>".number_format($MPCuenta[$filaCta[0]]['Creditos'][$fila2[0]],2)."</td>";
							echo "</tr>";
						}
					}
					$SumDebe=$SumDebe+$MPCuenta[$filaCta[0]]['Debitos'][$fila2[0]];$SumHaber=$SumHaber+$MPCuenta[$filaCta[0]]['Creditos'][$fila2[0]];
				}
			}
			if(!$PDF){echo "<tr><td></td><td colspan=3><hr></td></tr>";}
			if(!$PDF){echo "<tr align='right' style='font-weight:bold'><td>TOTAL</td><td>".number_format($SumDebe,2)."</td><td>".number_format($SumHaber,2)."</td></tr>";}
			$NumRec++;
			$Datos[$NumRec]=array("TOTAL",$SumDebe,$SumHaber);
			$Nivel=$NivelSel;

			$TotDebe=$TotDebe+$SumDebe;$TotHaber=$TotHaber+$SumHaber;
			$SumDebe=0;$SumHaber=0;

			if(!$PDF){echo "</table>";
			echo "</td></tr>";}
		}
	}
	$NumRec++;
	$Datos[$NumRec]=array("SUMAS IGUALES",$TotDebe,$TotHaber);
	if(!$PDF){
	echo "</table>";
	echo "<table align='right' style='font-family:$Estilo[8];font-size:12px;font-style:$Estilo[10]'>";
	echo "<tr><td colspan=4><br><br><hr></td></tr>";
	echo "<tr align='right' style='font-weight:bold'><td colspan=2>SUMAS IGUALES</td><td>".number_format($TotDebe,2)."</td><td>".number_format($TotHaber,2)."</td></tr>";
	echo "</table>";}

class PDF extends FPDF
{
	function BasicTable($data)
	{
		$Anchos=array(96,50,50);
		if(count($data)==0){exit;}
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
				if($SUBTOTAL && $x==0){$SUBTOTAL=0;}
				if($x>0){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				if(substr($col,0,6)=="CUENTA"){$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',8);$Ancho=196;}else{$fill=0;$Ancho=$Anchos[$x];$this->SetFont('Arial','',8);}
				if($col=="TOTAL"){$SUBTOTAL=1;}
				if($SUBTOTAL){$Alinea="R";$this->SetFont('Arial','B',8);}
				if($col=="SUMAS IGUALES"){$FINAL=1;}
				if($FINAL){$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',8);$Alinea="R";}
				$this->Cell($Ancho,5,$col,1,0,$Alinea,$fill);
				$x++;
			}
			$this->Ln();
		}
	}

//Cabecera de página
function Header()
{
	global $Compania;global $PerFin;global $PerIni;
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
    $this->Cell(0,8,"LIBRO DIARIO",0,0,'C');
    $this->Ln(5);
    $this->Cell(0,8,"PERIODO: $PerIni a $PerFin",0,0,'C');
    $this->Ln(10);
	$this->SetFillColor(218,218,218);

    $this->SetFont('Arial','B',8);
    $this->Cell(96,5,"Comprobante",1,0,'L',1);
    $this->Cell(50,5,"Debitos",1,0,'C',1);
    $this->Cell(50,5,"Creditos",1,0,'C',1);
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

if($PDF){$pdf->Output();}	
?>