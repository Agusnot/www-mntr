<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	ini_set("memory_limit","512M");
	include("Informes.php");
	require('LibPDF/fpdf.php');
	if(!$CuentaIni){$CuentaIni=0;}
	if(!$CuentaFin){$CuentaFin=9999999999;}
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";
	$ND=getdate();
	if(!$PDF)
	{
?>
		<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
		<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>AUXILIAR CON SALDOS<br>Entre <? echo $PerIni ?> a <? echo $PerFin?></strong></center></td></tr>
		<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td></tr>

		<tr style="font-weight:bold;text-align:center;" bgcolor="#e5e5e5">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<?
	}
	
	if($Tercero){$condAdc1=" and Movimiento.Identificacion='$Tercero'";}
	if($Comprobante){$condAdc2=" and Movimiento.Comprobante='$Comprobante'";}
	if($CC){$CondAdc3=" and CC='$CC'";}
	$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as Anio from Contabilidad.Movimiento 
	where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' $condAdc1 $condAdc2 
	and $ExcluyeComprobantes $CondAdc3 Group By Cuenta,Anio,Fecha Order By Cuenta,Fecha";
	
	$res2=ExQuery($cons2);echo ExError();
	while($fila2=ExFetch($res2))
	{
		$CuentaMad=substr($fila2[2],0,1);
		if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[3]){}
		else{
			$DatSaldoI[$fila2[2]]=array($DatSaldoI[$fila2[2]][0]+$fila2[0],$DatSaldoI[$fila2[2]][1]+$fila2[1]);
		}
	}


		$cons="Select Cuenta,Comprobante,Numero,Fecha,Debe,Haber,Detalle,PrimApe || ' ' || SegApe || ' '  || PrimNom || ' ' || SegNom as Tercero,Movimiento.Identificacion,Movimiento.Detalle,NoCheque,DocSoporte,AutoId,CC
		from Contabilidad.Movimiento,Central.Terceros 
		where Movimiento.Identificacion=Terceros.Identificacion and Fecha>='$PerIni' and Fecha<='$PerFin' and Movimiento.Compania='$Compania[0]' $condAdc1 $condAdc2 and Estado='AC'
		and Terceros.Compania='$Compania[0]' and $ExcluyeComprobantes $CondAdc3 Order By Cuenta,Fecha";
		//echo $cons;
		$res=ExQuery($cons);echo ExError($res);
		while($fila=ExFetch($res))
		{
			$DatCuenta[$fila[0]][$fila[1].$fila[2].$fila[3].$fila[4].$fila[5].$fila[12]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[13]);
		}



		$consPrev="Select Cuenta,Nombre,Naturaleza from Contabilidad.PlanCuentas
		where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Tipo='Detalle' and Compania='$Compania[0]' and Anio=$Anio
		Group By Cuenta,Nombre,Naturaleza Order By Cuenta";
		$resPrev=ExQuery($consPrev);echo ExError($resPrev);
		while($filaPrev=ExFetch($resPrev))
		{

			$SaldoI=0;

			$DebitosSI=$DatSaldoI[$filaPrev[0]][0];$CreditosSI=$DatSaldoI[$filaPrev[0]][1];
			if($filaPrev[2]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
			elseif($filaPrev[2]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}
		
			if(count($DatCuenta[$filaPrev[0]])>0){$Muestre=1;}
			if($SaldoI!=0){$Muestre=1;}
			if($Muestre==1){
			$NumRec++;
			$Datos[$NumRec]=array("CUENTA : $filaPrev[0] $filaPrev[1]",number_format($SaldoI,2),"*");
				
			if(!$PDF){
			echo "<tr><td>&nbsp;</td></tr>";
			echo "<tr><td>&nbsp;</td></tr>";
			echo "<tr><td>&nbsp;</td></tr>";
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold;'><td>Cuenta</td><td align='center' colspan=9>$filaPrev[0] - $filaPrev[1]</td><td>Saldo</td>";
			echo "<td align='right'>".number_format($SaldoI,2)."</td>";
			echo "</tr>";
			echo "<tr style='font-weight:bold;text-align:center'><td>Fecha</td><td>Comprobante</td><td>Numero</td><td>Cheque</td><td>Doc Ref</td><td>CC</td><td>Descripcion</td><td>Tercero</td><td>Identificacion</td><td>Debitos</td><td>Creditos</td><td>Saldo</td>";
			}

			if(count($DatCuenta[$filaPrev[0]])>0)
			{
				foreach($DatCuenta[$filaPrev[0]] as $DatoCuenta)
				{
					if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
					else{$BG="white";$Fondo=1;}
			
					if($filaPrev[2]=="Debito"){$SaldoF=$SaldoI+$DatoCuenta[4]-$DatoCuenta[5];}
					elseif($filaPrev[2]=="Credito"){$SaldoF=$SaldoI+$DatoCuenta[5]-$DatoCuenta[4];}
			
					if(!$PDF){echo "<tr bgcolor='$BG'><td>".$DatoCuenta[3]."</td><td>".$DatoCuenta[1]."</a></td><td>".
					$DatoCuenta[2]."</td>";
					echo "<td>".$DatoCuenta[10]."</td>";
					echo "<td>".$DatoCuenta[11]."</td>";
					echo "<td>".$DatoCuenta[12]."</td>";
					echo "<td>".$DatoCuenta[6]
					."</td><td>".strtoupper($DatoCuenta[7])."</td><td>".$DatoCuenta[8]."</td><td align='right'>".number_format($DatoCuenta[4],2)."</td>
					<td align='right'>".number_format($DatoCuenta[5],2)."</td><td align='right'>".number_format($SaldoF,2)."</td>";
					echo "</tr>";}
					$SaldoI=$SaldoF;
					$SumDebitos=$SumDebitos+$DatoCuenta[4];$SumCreditos=$SumCreditos+$DatoCuenta[5];
					$NumRec++;
					$Datos[$NumRec]=array($DatoCuenta[3],substr($DatoCuenta[1],0,30),substr($DatoCuenta[2],0,12),substr($DatoCuenta[10],0,10),substr($DatoCuenta[11],0,12),$DatoCuenta[12],substr($DatoCuenta[6],0,17),substr($DatoCuenta[7],0,19),$DatoCuenta[8],number_format($DatoCuenta[4],2),number_format($DatoCuenta[5],2),number_format($SaldoF,2));
					$SubDebitos=$SubDebitos+$DatoCuenta[4];
					$SubCreditos=$SubCreditos+$DatoCuenta[5];
				}
			}
			$Fondo=0;
			$Muestre=0;

			$NumRec++;
			$Datos[$NumRec]=array("SUMAS",number_format($SubDebitos,2),number_format($SubCreditos,2));

			if(!$PDF){echo "<tr bgcolor='#e5e5e5' align='right' style='font-weight:bold'><td colspan=9>SUMAS</td><td>".number_format($SubDebitos,2)."</td><td>".number_format($SubCreditos,2)."</td></tr>";}
			
			}
			$SubCreditos=0;$SubDebitos=0;
		}
		$NumRec++;
		$Datos[$NumRec]=array("SUMA TOTAL",number_format($SumDebitos,2),number_format($SumCreditos,2));
		if(!$PDF){echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td colspan=9>SUMA TOTAL</td><td>".number_format($SumDebitos,2)."</td><td>".number_format($SumCreditos,2)."</td></tr>";

?>
</table>
<?	}
	
class PDF extends FPDF
{
	function BasicTable($data)
	{
		$Anchos=array(16,35,17,14,17,17,30,35,21,20,20,20);
		$AnchosTit=array(232,30);
		if(count($data)==0){exit;}
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
					
				if($col=="SUMAS" || $col=="SUMA TOTAL")
				{
					$this->Cell(181,5,"",0,0,"R",0);
					$x=8;
					
				}
				if($x>8){$Alinea='R';}else{$Alinea="L";}
				if(substr($col,0,6)=="CUENTA"){$TITULO=1;$Alinea="R";}
				if($TITULO && $col=="*"){$TITULO=0;}
				if($col!="*"){
				if($TITULO)
				{
					$this->SetFillColor(218,218,218);
					$this->SetFont('Arial','B',8);
					$this->Cell($AnchosTit[$x],5,strtoupper($col),1,0,"R",1);
				}
				else{
					$this->SetFont('Arial','',7);
					$this->Cell($Anchos[$x],5,"$col",1,0,$Alinea,0);
				}}
				$x++;
			}
			$this->Ln();
		}
	}

//Cabecera de página
function Header()
{
	global $Compania;global $PerFin;global $PerIni;global $CC;
    //Logo
//    $this->Image('/Imgs/Logo.jpg',10,8,33);
    //Arial bold 15
    $this->SetFont('Arial','B',12);

    $this->Cell(0,5,strtoupper($Compania[0]),0,0,'C');
    $this->Ln(4);
    $this->SetFont('Arial','B',8);
    $this->Cell(0,5,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(4);

    $this->Cell(0,5,"AUXILIAR CON SALDOS",0,0,'C');
    $this->Ln(4);
	if($CC)
	{
	    $this->Cell(0,5,"CENTRO DE COSTOS $CC",0,0,'C');
	    $this->Ln(4);
	}
    $this->Cell(0,5,"PERIODO: $PerIni a $PerFin",0,0,'C');
    $this->Ln(5);


	$this->SetFillColor(218,218,218);

    $this->Cell(16,5,"Fecha",1,0,'C',1);
    $this->Cell(35,5,"Comprobante",1,0,'C',1);
    $this->Cell(17,5,"Numero",1,0,'C',1);
    $this->Cell(14,5,"Cheque",1,0,'C',1);
    $this->Cell(17,5,"Doc Ref",1,0,'C',1);
    $this->Cell(17,5,"CC",1,0,'C',1);
    $this->Cell(30,5,"Descripcion",1,0,'C',1);
    $this->Cell(35,5,"Tercero",1,0,'C',1);
    $this->Cell(21,5,"Identificacion",1,0,'C',1);
    $this->Cell(20,5,"Debitos",1,0,'C',1);
    $this->Cell(20,5,"Creditos",1,0,'C',1);
    $this->Cell(20,5,"Saldo",1,0,'C',1);
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
$pdf->SetFont('Arial','',7);
$pdf->BasicTable($Datos);


if($PDF){$pdf->Output();}
	
?>