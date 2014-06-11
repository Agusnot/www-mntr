<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	include_once("General/Configuracion/Configuracion.php");
	require('LibPDF/fpdf.php');
	if($ExcluyeComprobantes=="1"){$ExcluyeComprobantes="1=1";}
	if(!$CuentaIni){$CuentaIni=0;}
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
		<style>
			P{
				PAGE-BREAK-AFTER: always;
			}
		</style>
	</head>	
	<body <?php echo $backgroundBodyInfContableMentor;?>>
					
					<?

						function Encabezados()
						{
							global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;global $CC;
							?>
							<table  rules="groups" width="100%" class="tablaInformeContable" style="text-align:justify;" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?> >
							<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
							<?echo $Compania[1]?><br>BALANCE DE PRUEBA
							<? if($CC){ echo "<br>CENTRO DE COSTOS: $CC";}?>
							<br>CORTE A: <?echo $PerFin?></td></tr>
							<tr><td colspan="8" style="text-align:center;">FECHA DE IMPRESI&Oacute;N <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
							</tr>
							<tr>
								<td class='encabezado2HorizontalInfCont' rowspan="2">C&Oacute;DIGO</td>
									<?
										if($IncluyeCC=="on")
										{
											echo "<td rowspan=2>CC</td>";
										}
									?>
								<td class='encabezado2HorizontalInfCont' rowspan="2">DESCRIPCI&Oacute;N</td>
								<td class='encabezado2HorizontalInfCont' colspan="2">SALDO ANTERIOR</td>
								<td class='encabezado2HorizontalInfCont' colspan="2">MOVIMIENTOS DEL PERIODO</td>
								<td class='encabezado2HorizontalInfCont' colspan="2">SALDO FINAL</td>
							</tr>
							<tr>
								<td class='encabezado2HorizontalInfCont' style="font-size:11px;">DEBITO</td>
								<td class='encabezado2HorizontalInfCont' style="font-size:11px;">CREDITO</td>
								<td class='encabezado2HorizontalInfCont' style="font-size:11px;">DEBITO</td>
								<td class='encabezado2HorizontalInfCont' style="font-size:11px;">CREDITO</td>
								<td class='encabezado2HorizontalInfCont' style="font-size:11px;">DEBITO</td>
								<td class='encabezado2HorizontalInfCont' style="font-size:11px;">CREDITO</td>
							</tr>
							
					<?	}
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
						if($CC){$CondCC=" and CC='$CC' ";}
						echo $cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as MovAnio from Contabilidad.Movimiento 
						where Fecha<'$PerIni'  and Estado='AC'   and $ExcluyeComprobantes and
						Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' $CondCC
						Group By Cuenta,MovAnio Order By Cuenta";
						echo "<br><br>";
						//echo $cons2;

						$res2=ExQuery($cons2);
						while($fila2=ExFetch($res2)){
							$CuentaMad=substr($fila2[2],0,1);
							if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[3]){}
							else{
							for($Nivel=1;$Nivel<=$TotNivel;$Nivel++){
								$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
								if($ParteAnt!=$ParteCuenta){
								$SICuenta[$ParteCuenta]['debitos']=$SICuenta[$ParteCuenta]['debitos']+$fila2[0];
								$SICuenta[$ParteCuenta]['creditos']=$SICuenta[$ParteCuenta]['creditos']+$fila2[1];}
								$ParteAnt=$ParteCuenta;
							}
							}
						}

						echo $cons3="Select sum(Debe),sum(Haber),Cuenta from Contabilidad.Movimiento 
						where Fecha>='$PerIni' and Fecha<='$PerFin'  and Estado='AC'   and $ExcluyeComprobantes and
						Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin'  $CondCC
						Group By Cuenta Order By Cuenta";
						echo "<br><br>";
						$res3=ExQuery($cons3);
						//echo $cons3;
						while($fila3=ExFetch($res3))
						{
							for($Nivel=1;$Nivel<=$TotNivel;$Nivel++){
							
								$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
								if($ParteAnt!=$ParteCuenta){
									$cons99="Select Cuenta from Contabilidad.Plancuentas where Cuenta='$ParteCuenta' and Anio=$Anio and Compania='$Compania[0]'";
									$res99=ExQuery($cons99);
									if(ExNumRows($res99)==0){
										echo "CUENTA NO EXISTE . " . $ParteCuenta."<br>";
									}
								$MPCuenta[$ParteCuenta]['debitos']=$MPCuenta[$ParteCuenta]['debitos']+$fila3[0];
								$MPCuenta[$ParteCuenta]['creditos']=$MPCuenta[$ParteCuenta]['creditos']+$fila3[1];}
								$ParteAnt=$ParteCuenta;
							}
						}

						echo $consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos from Contabilidad.PlanCuentas 
						where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Compania='$Compania[0]' and Anio=$Anio
						and length(Cuenta)<=$NoDigitos Order By Cuenta";
						echo "<br> <br>";
						$resCta=ExQuery($consCta);
						
						while($filaCta=ExFetchArray($resCta))
						{

							$Debitos=$MPCuenta[$filaCta[0]]['debitos'];
							$Creditos=$MPCuenta[$filaCta[0]]['creditos'];
							$DebitosSI=$SICuenta[$filaCta[0]]['debitos'];
							$CreditosSI=$SICuenta[$filaCta[0]]['creditos'];
							
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

								if(!$PDF){echo "<tr bgcolor='$BG'>";}
								if(!$PDF){echo "<td>";
								echo "$filaCta[0]</td><td>".substr($filaCta[1],0,40)."</td>";}

								if($SaldoI<0 && $MovSI=="Debito"){$MovSI="Credito";$SaldoI=abs($SaldoI);}
								if($SaldoI<0 && $MovSI=="Credito"){$MovSI="Debito";$SaldoI=abs($SaldoI);}
								if($MovSI=="Debito"){if(!$PDF){echo "<td align='right'>".number_format($SaldoI,2)."</td><td align='right'>0.00</td>";};$SaldoIBD=$SaldoI;
								if(strlen($filaCta[0])==1){$TotDebitosSI=$TotDebitosSI+$SaldoI;}}
								else{if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoI,2)."</td>";};$SaldoICR=$SaldoI;
								if(strlen($filaCta[0])==1){$TotCreditosSI=$TotCreditosSI+$SaldoI;}}

								if(!$PDF){echo "<td align='right'>".number_format($Debitos,2)."</td><td align='right'>".number_format($Creditos,2)."</td>";}

								if($filaCta[3]=="Debito")
								{
									if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";};$SaldoFCR=$SaldoF;
									if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
									else{if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";};$SaldoFDB=$SaldoF;
									if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
								}
								elseif($filaCta[3]=="Credito")
								{
									if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";};$SaldoFDB=$SaldoF;
									if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
									else{if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";};$SaldoFCR=$SaldoF;
									if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
								}
								if(strlen($filaCta[0])==1)
								{
									$TotDebitosMov=$TotDebitosMov+$Debitos;
									$TotCreditosMov=$TotCreditosMov+$Creditos;
								}
								$Datos[$NumRec]=array($filaCta[0],$filaCta[1],$SaldoIBD,$SaldoICR,$Debitos,$Creditos,$SaldoFDB,$SaldoFCR);
								$SaldoIBD=0;$SaldoICR=0;$SaldoFCR=0;$SaldoFDB=0;
							}
							if($filaCta[0]=="1"){$TotActivo=$SaldoF;}
							if($filaCta[0]=="2"){$TotPasivo=$SaldoF;}
							if($filaCta[0]=="3"){$TotPatrimonio=$SaldoF;}
							$Muestre="N";
							$SaldoI=0;
						}
						$NumRec++;
						$Datos[$NumRec]=array("SUMAS","",$TotDebitosSI,$TotCreditosSI,$TotDebitosMov,$TotCreditosMov,$TotSFDeb,$TotSFCred);

						$BuscCargos=array("Representante","Contador");
						foreach($BuscCargos as $GenCargos)
						{
							$cons="Select Nombre,Cargo from Central.CargosxCompania where Compania='$Compania[0]' and FechaIni<='$PerFin' and FechaFin>='$PerFin' and Categoria='$GenCargos'";
							$res=ExQuery($cons);
							$fila=ExFetch($res);

							$DatoCargo[$GenCargos][0]=$fila[0];
							$DatoCargo[$GenCargos][1]=$fila[1];
						}


					if(!$PDF){
							echo "<tr bgcolor='#e5e5e5'>";
							if($IncluyeCC=="on"){echo "<td colspan=3 align='right'>";}
							else{echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;' colspan=2 >";}
							echo "<strong>SUMAS IGUALES</td>";
							echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($TotDebitosSI,2)."</td>";
							echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($TotCreditosSI,2)."</td>";
							echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($TotDebitosMov,2)."</td>";
							echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($TotCreditosMov,2)."</td>";

							echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($TotSFDeb,2)."</td>";
							echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($TotSFCred,2)."</td>";
							echo "</tr>";
						?>
						</table>
						<br><center>

						<br><br>

						<table border="0">
						<tr><td>______________________________</td><td style="width:130px;"></td><td>______________________________</td><td style="width:130px;"></td></tr>
						<tr style="font-weight:bold;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
						<td><? echo $DatoCargo['Representante'][0]?></td><td></td><td><? echo $DatoCargo['Contador'][0]?></td><td></td></tr>
						<tr style="font-weight:bold;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
						<td><? echo $DatoCargo['Representante'][1]?></td><td></td><td><? echo $DatoCargo['Contador'][1] ?></td></tr>
						</table>
						</div>
						</body>
						<? 	
					}
class PDF extends FPDF
{//192,168.1.110
	function BasicTable($data)
	{
		$Anchos=array(25,90,25,25,25,25,25,25);
		$fill=false;$this->SetFillColor(248,248,248);
		if(!$data){exit;}
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
				$POSY=$this->GetY();
				if($x==1){$col=substr($col,0,48);}
				if($x>1){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				if($col=="SUMAS"){$Final=1;}
				if($Final)
				{
					$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',7);$Lines="LRBT";
				}
				else
				{
				if($POSY>=190 && $POSY<195){$Lines="LRB";}
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
	global $Compania;global $PerFin;global $CC;
    //Logo
//    $this->Image('/Imgs/Logo.jpg',10,8,33);
    //Arial bold 15
    $this->SetFont('Arial','B',12);
    //Movernos a la derecha

    //Título
    $this->Cell(0,8,strtoupper($Compania[0]),0,0,'C');
    //Salto de línea
    $this->Ln(5);
    $this->SetFont('Arial','B',9);
    $this->Cell(0,8,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(5);
    $this->Cell(0,8,"BALANCE DE PRUEBA",0,0,'C');
	if($CC)
	{
	    $this->Ln(5);
	    $this->Cell(0,8,"CENTRO DE COSTOS: $CC",0,0,'C');
	}
    $this->Ln(5);
    $this->Cell(0,8,"CORTE: $PerFin",0,0,'C');
    $this->Ln(10);
	$this->SetFillColor(228,228,228);
    $this->SetFont('Arial','B',8);

    $this->Cell(25,10,"Codigo",1,0,'C',1);
    $this->Cell(90,10,"Descripcion",1,0,'C',1);
    $this->Cell(50,5,"Saldo Anterior",1,0,'C',1);
    $this->Cell(50,5,"Movimientos del Periodo",1,0,'C',1);
    $this->Cell(50,5,"Saldo Final",1,0,'C',1);
    $this->Ln(5);
    $this->Cell(115,5,"",0,0,'C');
    $this->Cell(25,5,"Debitos",1,0,'C',1);
    $this->Cell(25,5,"Creditos",1,0,'C',1);

    $this->Cell(25,5,"Debitos",1,0,'C',1);
    $this->Cell(25,5,"Creditos",1,0,'C',1);

    $this->Cell(25,5,"Debitos",1,0,'C',1);
    $this->Cell(25,5,"Creditos",1,0,'C',1);

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
$pdf->Cell(265,0,"","B",0,'C');

$pdf->Ln(20);
$pdf->Cell(120,8,"____________________________________",0,0,'C');
$pdf->Cell(120,8,"____________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(120,8,$DatoCargo['Representante'][0],0,0,'C');
$pdf->Cell(120,8,$DatoCargo['Contador'][0],0,0,'C');
$pdf->Ln(5);
$pdf->Cell(120,8,$DatoCargo['Representante'][1],0,0,'C');
$pdf->Cell(120,8,$DatoCargo['Contador'][1],0,0,'C');

if($PDF){$pdf->Output();}
?>
