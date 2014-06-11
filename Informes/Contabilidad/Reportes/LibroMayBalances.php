<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	include_once("General/Configuracion/Configuracion.php");
	global $borderTablaInfContable, $bordercolorTablaInfContable, $cellspacingTablaInfContable, $cellpaddingTablaInfContable; 
	require('LibPDF/fpdf.php');
	if(!$CuentaIni){$CuentaIni=0;}
	if(!$CuentaFin){$CuentaFin=9999999999;}
	$ND=getdate();
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";
	if(!$PDF)
	{
?>


	<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
					<style>
					P{
						page-break-after:always
					}
				</style>
				</head>	
				<body <?php echo $backgroundBodyInfContableMentor;?>>
					<div class="divInformeContable" <?php echo $alignDivInformeContable;?>>


					<?
						function Encabezados()	{
							global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;global $NoDigitos;
							global $borderTablaInfContable, $bordercolorTablaInfContable, $cellspacingTablaInfContable, $cellpaddingTablaInfContable; 
							?>
							<table   class="tablaInformeContable" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?> >
							<tr>
								<td colspan="8" class="encabezadoInformeContable"><?echo strtoupper($Compania[0])?><br>
								<?echo $Compania[1]?><br>LIBRO MAYOR Y BALANCE <br> DIGITOS: <? echo $NoDigitos?> <br>CORTE A: <?echo $PerFin?> <br>
								FECHA DE IMPRESI&Oacute;N <?echo "$PerFin"?>								
								</td>
							</tr>
							
							<tr>
								<td class='encabezado2HorizontalInfCont' rowspan="2" > C&Oacute;DIGO</td>
								<?
									if($IncluyeCC=="on")
									{
										echo "<td class='encabezado2HorizontalInfCont' rowspan=2>CC</td>";
									}
								?>
								<td  class='encabezado2HorizontalInfCont' rowspan="2">DESCRIPCI&Oacute;N</td>
								<td class='encabezado2HorizontalInfCont' colspan="2">SALDO ANTERIOR</td>
								<td class='encabezado2HorizontalInfCont' colspan="2">MOVIMIENTOS DEL PERIODO</td>
								<td class='encabezado2HorizontalInfCont' colspan="2">SALDO FINAL</td>
							</tr>
								<tr>
									<td class='encabezado2HorizontalInfCont'>D&Eacute;BITO</td>
									<td class='encabezado2HorizontalInfCont'>CR&Eacute;DITO</td>
									<td class='encabezado2HorizontalInfCont'>D&Eacute;BITO</td>
									<td class='encabezado2HorizontalInfCont'>CR&Eacute;DITO</td>
									<td class='encabezado2HorizontalInfCont'>D&Eacute;BITO</td>
									<td class='encabezado2HorizontalInfCont'>CR&Eacute;DITO</td>
								</tr>
							
					<?	}
						$NumRec=0;$NumPag=1;
						Encabezados();
						}
						$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
						$res=ExQuery($cons,$conex);
						while($fila=ExFetch($res))
						{
							$Nivel++;
							if(!$fila[0]){$fila[0]="-100";}
							$TotCaracteres=$TotCaracteres+$fila[0];
							$Digitos[$Nivel]=$TotCaracteres;
						}
						$NivelMax=$Nivel;
						$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as Anio from Contabilidad.Movimiento 
						where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' 
						and $ExcluyeComprobantes Group By Cuenta,Anio,Fecha Order By Cuenta";
						$res2=ExQuery($cons2);
						while($fila2=ExFetch($res2))
						{
							$CuentaMad=substr($fila2[2],0,1);
							if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[3]){}
							else{
							for($Nivel=1;$Nivel<=$NivelMax;$Nivel++)
							{
								$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
								if($ParteAnt!=$ParteCuenta){
								$SICuenta[$ParteCuenta]['Debitos']=$SICuenta[$ParteCuenta]['Debitos']+$fila2[0];
								$SICuenta[$ParteCuenta]['Creditos']=$SICuenta[$ParteCuenta]['Creditos']+$fila2[1];}
								$ParteAnt=$ParteCuenta;
							}}
						}

						$cons3="Select sum(Debe),sum(Haber),Cuenta from Contabilidad.Movimiento 
						where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and $ExcluyeComprobantes and
						Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin'
						Group By Cuenta Order By Cuenta";

						$res3=ExQuery($cons3);
						while($fila3=ExFetch($res3))
						{
							for($Nivel=1;$Nivel<=$NivelMax;$Nivel++)
							{
								$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
								if($ParteAnt!=$ParteCuenta){
								$MPCuenta[$ParteCuenta]['Debitos']=$MPCuenta[$ParteCuenta]['Debitos']+$fila3[0];
								$MPCuenta[$ParteCuenta]['Creditos']=$MPCuenta[$ParteCuenta]['Creditos']+$fila3[1];}
								$ParteAnt=$ParteCuenta;
							}
						}
						
						$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos from Contabilidad.PlanCuentas 
						where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Compania='$Compania[0]' and Anio=$Anio
						Group By Cuenta,Nombre,Tipo,Naturaleza
						having length(Cuenta)=$NoDigitos Order By Cuenta";
						$resCta=ExQuery($consCta);

						while($filaCta=ExFetchArray($resCta))
						{
							if(!$PDF){
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

							if($DebitosSI || $CreditosSI || $Debitos || $Creditos){$Muestre=1;}
							if($IncluyeCeros=="on"){$Muestre=1;}
							if($Muestre==1)
							{
								$NumRec++;

								if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
								else{$BG="white";$Fondo=1;}

								if(!$PDF){
								echo "<tr bgcolor='$BG'>";
								if($IncluyeCC=="on"){echo "<td colspan=2>";}
								else{echo "<td>";}
								echo "$filaCta[0]</td><td>".substr($filaCta[1],0,40)."</td>";
								}

								if($SaldoI<0 && $MovSI=="Debito"){$MovSI="Credito";$SaldoI=abs($SaldoI);}
								if($SaldoI<0 && $MovSI=="Credito"){$MovSI="Debito";$SaldoI=abs($SaldoI);}
								if($MovSI=="Debito"){if(!$PDF){echo "<td style='text-align: right; padding-right: 10px;>".number_format($SaldoI,2)."</td><td style='text-align: right; padding-right: 10px;>0.00</td>";}$SaldoIDB=$SaldoI;
								if(strlen($filaCta[0])==6){$TotDebitosSI=$TotDebitosSI+$SaldoI;}}
								else{if(!$PDF){echo "<td align='right'>0.00</td><td style='text-align: right; padding-right: 10px;>".number_format($SaldoI,2)."</td>";}$SaldoICR=$SaldoI;
								if(strlen($filaCta[0])==6){$TotCreditosSI=$TotCreditosSI+$SaldoI;}}

								if(!$PDF){echo "<td align='right'>".number_format($Debitos,2)."</td><td style='text-align: right; padding-right: 10px;>".number_format($Creditos,2)."</td>";}

								if($filaCta[3]=="Debito")
								{
									if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td style='text-align: right; padding-right: 10px;>0.00</td><td style='text-align: right; padding-right: 10px;>".number_format($SaldoF,2)."</td>";}$SaldoFCR=$SaldoF;
									if(strlen($filaCta[0])==6){$TotSFCred=$TotSFCred+$SaldoF;}}
									else{if(!$PDF){echo "<td style='text-align: right; padding-right: 10px;>".number_format($SaldoF,2)."</td><td style='text-align: right; padding-right: 10px;>0.00</td>";}$SaldoFDB=$SaldoF;
									if(strlen($filaCta[0])==6){$TotSFDeb=$TotSFDeb+$SaldoF;}}
								}
								elseif($filaCta[3]=="Credito")
								{
									if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td style='text-align: right; padding-right: 10px;>".number_format($SaldoF,2)."</td><td style='text-align: right; padding-right: 10px;>0.00</td>";}$SaldoFDB=$SaldoF;
									if(strlen($filaCta[0])==6){$TotSFDeb=$TotSFDeb+$SaldoF;}}
									else{if(!$PDF){echo "<td align='right'>0.00</td><td style='text-align: right; padding-right: 10px;>".number_format($SaldoF,2)."</td>";}$SaldoFCR=$SaldoF;
									if(strlen($filaCta[0])==6){$TotSFCred=$TotSFCred+$SaldoF;}}
								}
								if(strlen($filaCta[0])==6)
								{
									$TotDebitosMov=$TotDebitosMov+$Debitos;
									$TotCreditosMov=$TotCreditosMov+$Creditos;
								}
								$Datos[$NumRec]=array($filaCta[0],$filaCta[1],$SaldoIDB,$SaldoICR,$Debitos,$Creditos,$SaldoFDB,$SaldoFCR);
								$SaldoIDB=0;$SaldoICR=0;$SaldoFDB=0;$SaldoFCR=0;
							}
							$Muestre="N";
							$SaldoI=0;
						}
						
						
						$TotDebitosSI=0;$TotCreditosSI=0;$TotDebitosMov=0;$TotCreditosMov=0;$TotSFDeb=0;$TotSFCred=0;

						$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos from Contabilidad.PlanCuentas 
						where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Compania='$Compania[0]'  and Anio=$Anio
						Group By Cuenta,Nombre,Tipo,Naturaleza
						having length(Cuenta)=1 Order By Cuenta";
						$resCta=ExQuery($consCta);

						while($filaCta=ExFetchArray($resCta))
						{
							$Debitos=$MPCuenta[$filaCta[0]]['Debitos'];
							$Creditos=$MPCuenta[$filaCta[0]]['Creditos'];
							$DebitosSI=$SICuenta[$filaCta[0]]['Debitos'];
							$CreditosSI=$SICuenta[$filaCta[0]]['Creditos'];

							if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
							if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
							elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}

							if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
							elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}

							if($SaldoI<0 && $MovSI=="Debito"){$MovSI="Credito";$SaldoI=abs($SaldoI);}
							if($SaldoI<0 && $MovSI=="Credito"){$MovSI="Debito";$SaldoI=abs($SaldoI);}

							if($MovSI=="Debito"){$TotDebitosSI=$TotDebitosSI+$SaldoI;}
							if($MovSI=="Credito"){$TotCreditosSI=$TotCreditosSI+$SaldoI;}

							if($filaCta[3]=="Debito")
							{
								if($SaldoF<0){$SaldoF=$SaldoF*-1;$TotSFCred=$TotSFCred+$SaldoF;}
								else{$TotSFDeb=$TotSFDeb+$SaldoF;}
							}
							elseif($filaCta[3]=="Credito")
							{
								if($SaldoF<0){$SaldoF=$SaldoF*-1;$TotSFDeb=$TotSFDeb+$SaldoF;}
								else{$TotSFCred=$TotSFCred+$SaldoF;}
							}
							$TotDebitosMov=$TotDebitosMov+$Debitos;
							$TotCreditosMov=$TotCreditosMov+$Creditos;
						}
						$NumRec++;
						$Datos[$NumRec]=array("SUMAS","",$TotDebitosSI,$TotCreditosSI,$TotDebitosMov,$TotCreditosMov,$TotSFDeb,$TotSFCred);
					if(!$PDF){
						echo "<tr>";
						echo "<td  class='filaTotalesInfContable' style='text-align:right; padding-right:10px;' colspan=2><strong>SUMAS IGUALES</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;' >".number_format($TotDebitosSI,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotCreditosSI,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotDebitosMov,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotCreditosMov,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotSFDeb,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotSFCred,2)."</td>";

						echo "</tr>";
					?>
					</table>
				</div>	
			</body>
	</html>	
			
			
			<?
		}
					
					
class PDF extends FPDF
{
	function BasicTable($data)
	{
		$Anchos=array(25,90,25,25,25,25,25,25);
		if(count($data)>0){
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
		}}
	}

//Cabecera de página
function Header()
{
	global $Compania;global $PerFin;global $NoDigitos;
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
    $this->Cell(0,8,"LIBRO MAYOR Y BALANCE ($NoDigitos DIGITOS)",0,0,'C');
    $this->Ln(5);
    $this->Cell(0,8,"CORTE: $PerFin",0,0,'C');
    $this->Ln(10);
    $this->Cell(25,10,"Codigo",1,0,'C');
    $this->Cell(90,10,"Descripcion",1,0,'C');
    $this->Cell(50,5,"Saldo Anterior",1,0,'C');
    $this->Cell(50,5,"Movimientos del Periodo",1,0,'C');
    $this->Cell(50,5,"Saldo Final",1,0,'C');
    $this->Ln(5);
    $this->Cell(115,5,"",0,0,'C');
    $this->Cell(25,5,"Debitos",1,0,'C');
    $this->Cell(25,5,"Creditos",1,0,'C');

    $this->Cell(25,5,"Debitos",1,0,'C');
    $this->Cell(25,5,"Creditos",1,0,'C');

    $this->Cell(25,5,"Debitos",1,0,'C');
    $this->Cell(25,5,"Creditos",1,0,'C');

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

if($PDF){$pdf->Output();}

?>