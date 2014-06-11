	<?
			if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include("Informes.php");
		include_once("General/Configuracion/Configuracion.php");
		global $borderTablaInfContable, $bordercolorTablaInfContable, $cellspacingTablaInfContable, $cellpaddingTablaInfContable; 
		if(!$CuentaIni){$CuentaIni=0;}
		if(!$CuentaFin){$CuentaFin=9999999999;}
		$ND=getdate();
		$PerIni="$Anio-$MesIni-$DiaIni";
		$PerFin="$Anio-$MesFin-$DiaFin";

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
						global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;
						global $borderTablaInfContable, $bordercolorTablaInfContable, $cellspacingTablaInfContable, $cellpaddingTablaInfContable; 
						?>
						<table   class="tablaInformeContable" style="text-align: center;"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
							<tr>
								<td  class="encabezadoInformeContable" colspan="10" style="text-align:center"><?echo strtoupper($Compania[0])?><br>
								<?echo $Compania[1]?><br>BALANCE DE PRUEBA<br>CORTE A: <?echo $PerFin?><br>
								FECHA DE IMPRESI&Oacute;N <?echo "$ND[year]-$ND[mon]-$ND[mday]"?>
								</td>
							</tr>
							
							<tr>
								<td class='encabezado2HorizontalInfCont' rowspan="2">C&Oacute;DIGO</td>
								<?
									if($IncluyeCC=="on")
									{
										echo "<td class='encabezado2HorizontalInfCont' rowspan=2>CC</td>";
									}
								?>
								<td class='encabezado2HorizontalInfCont' rowspan="2" colspan="3">DESCRIPCI&Oacute;N</td>
								<td class='encabezado2HorizontalInfCont' colspan="2">SALDO ANTERIOR</td>
								<td class='encabezado2HorizontalInfCont' colspan="2">MOVIMIENTOS DEL PERIODO</td>
								<td class='encabezado2HorizontalInfCont' colspan="2">SALDO FINAL</td></tr>
							<tr>
								<td class='encabezado2HorizontalInfCont'>D&Eacute;BITO</td>
								<td class='encabezado2HorizontalInfCont'>CR&Eacute;DITO</td>
								<td class='encabezado2HorizontalInfCont'>D&Eacute;BITO</td>
								<td class='encabezado2HorizontalInfCont'>CR&Eacute;DITO</td>
								<td class='encabezado2HorizontalInfCont'>D&Eacute;BITO</td>
								<td class='encabezado2HorizontalInfCont'>CR&Eacute;DITO</td>
											
							</tr>
						<?
					}
						$NumRec=0;$NumPag=1;
						Encabezados();

						$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
						$res=ExQuery($cons,$conex);echo ExError();
						while($fila=ExFetchArray($res))
						{
							$Nivel++;$TotNivel++;
							if(!$fila[0]){$fila[0]="-100";}
							$TotCaracteres=$TotCaracteres+$fila[0];
							$Digitos[$Nivel]=$TotCaracteres;
						}

						$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as Anio from Contabilidad.Movimiento 
						where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
						and $ExcluyeComprobantes Group By Cuenta,Anio,Fecha Order By Cuenta";
						$res2=ExQuery($cons2);echo ExError();
						while($fila2=ExFetch($res2))
						{
							$CuentaMad=substr($fila2[2],0,1);
							if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[3]){}
							else{
							for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
							{
								$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
								if($ParteAnt!=$ParteCuenta){
								$SICuenta[$ParteCuenta]['Debitos']=$SICuenta[$ParteCuenta]['Debitos']+$fila2[0];
								$SICuenta[$ParteCuenta]['Creditos']=$SICuenta[$ParteCuenta]['Creditos']+$fila2[1];}
								$ParteAnt=$ParteCuenta;
							}}
						}

						$cons3="Select sum(Debe),sum(Haber),Cuenta from Contabilidad.Movimiento 
						where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Cuenta!='0' and Cuenta!='1' 
						and $ExcluyeComprobantes Group By Cuenta Order By Cuenta";
						$res3=ExQuery($cons3);echo ExError();
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
						
						
					///////////////////////////////BALANCE DE TERCEROS //////////////////////////////////////
					//////////////SALDOS INICIALES////////////////////
						$cons200="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as Anio,Identificacion from Contabilidad.Movimiento 
						where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
						and $ExcluyeComprobantes Group By Cuenta,Anio,Fecha,Identificacion Order By Cuenta";

						$res200=ExQuery($cons200);echo ExError();
						while($fila200=ExFetch($res200))
						{
							$CuentaMad=substr($fila200[2],0,1);
							if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila200[3]){}
							else{
							for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
							{
								$ParteCuenta=substr($fila200[2],0,$Digitos[$Nivel]);
								if($ParteAnt!=$ParteCuenta){
								$SITCuenta[$ParteCuenta][$fila200[4]]['Debitos']=$SITCuenta[$ParteCuenta][$fila200[4]]['Debitos']+$fila200[0];
								$SITCuenta[$ParteCuenta][$fila200[4]]['Creditos']=$SITCuenta[$ParteCuenta][$fila200[4]]['Creditos']+$fila200[1];}
								$ParteAnt=$ParteCuenta;
							}}
						}

					//////////////////MOVIMIENTOS///////////////////////

						$cons3="Select sum(Debe),sum(Haber),Cuenta,Identificacion from Contabilidad.Movimiento 
						where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Cuenta!='0' and Cuenta!='1' and $ExcluyeComprobantes 
						Group By Cuenta,Identificacion Order By Cuenta";

						$res3=ExQuery($cons3);echo ExError();
						while($fila3=ExFetch($res3))
						{
							for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
							{
								$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
								if($ParteAnt!=$ParteCuenta){
								$MPTCuenta[$ParteCuenta][$fila3[3]]['Debitos']=$MPTCuenta[$ParteCuenta][$fila3[3]]['Debitos']+$fila3[0];
								$MPTCuenta[$ParteCuenta][$fila3[3]]['Creditos']=$MPTCuenta[$ParteCuenta][$fila3[3]]['Creditos']+$fila3[1];}
								$ParteAnt=$ParteCuenta;
							}
						}
					////////DATOS DEL TERCERO/////////
						$cons89="Select PrimApe,SegApe,PrimNom,SegNom,Terceros.Identificacion,Cuenta from Central.Terceros,Contabilidad.Movimiento 
						where Movimiento.Identificacion=Terceros.Identificacion and Terceros.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]'
						and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Cuenta!='1' and Cuenta!='0'
						and Movimiento.Estado='AC' Group By Terceros.Identificacion,Cuenta,PrimApe,SegApe,PrimNom,SegNom";
						$res89=ExQuery($cons89);
						while($fila89=ExFetch($res89))
						{
							$MatTerceros[$fila89[5]][$fila89[4]]=array($fila89[0],$fila89[1],$fila89[2],$fila89[3],$fila89[4]);
						}
						
						$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos,Tercero from Contabilidad.PlanCuentas 
						where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Compania='$Compania[0]' and Anio=$Anio
						and length(Cuenta)<=$NoDigitos Order By Cuenta";
						$resCta=ExQuery($consCta);echo ExError();

						while($filaCta=ExFetchArray($resCta))
						{
							if($NumRec>=$Encabezados)
							{
								echo "</table><P>&nbsp;</P>";
								$NumPag++;
								Encabezados();
								$NumRec=0;
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

								echo "<tr bgcolor='$BG'>";
								echo "<td>";
								echo "$filaCta[0]</td><td colspan=3>$filaCta[1]</td>";

								if($SaldoI<0 && $MovSI=="Debito"){$MovSI="Credito";$SaldoI=abs($SaldoI);}
								if($SaldoI<0 && $MovSI=="Credito"){$MovSI="Debito";$SaldoI=abs($SaldoI);}
								if($MovSI=="Debito"){echo "<td align='right'>".number_format($SaldoI,2)."</td><td align='right'>0.00</td>";
								if(strlen($filaCta[0])==1){$TotDebitosSI=$TotDebitosSI+$SaldoI;}}
								else{echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoI,2)."</td>";
								if(strlen($filaCta[0])==1){$TotCreditosSI=$TotCreditosSI+$SaldoI;}}

								echo "<td align='right'>".number_format($Debitos,2)."</td><td align='right'>".number_format($Creditos,2)."</td>";

								if($filaCta[3]=="Debito")
								{
									if($SaldoF<0){$SaldoF=$SaldoF*-1;echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";
									if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
									else{echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";
									if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
								}
								elseif($filaCta[3]=="Credito")
								{
									if($SaldoF<0){$SaldoF=$SaldoF*-1;echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";
									if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
									else{echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";
									if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
								}
								if(strlen($filaCta[0])==1)
								{
									$TotDebitosMov=$TotDebitosMov+$Debitos;
									$TotCreditosMov=$TotCreditosMov+$Creditos;
								}
								if($filaCta[5]==1)
								{
									$NumRec++;
									if(count($MatTerceros[$filaCta[0]])>0){
									foreach($MatTerceros[$filaCta[0]] as $Identificacion)
									{
										$DebitosSI=$SITCuenta[$filaCta[0]][$Identificacion[4]]['Debitos'];
										$CreditosSI=$SITCuenta[$filaCta[0]][$Identificacion[4]]['Creditos'];
										
										$Debitos=$MPTCuenta[$filaCta[0]][$Identificacion[4]]['Debitos'];
										$Creditos=$MPTCuenta[$filaCta[0]][$Identificacion[4]]['Creditos'];

							
										if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
										if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
										elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}

										if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
										elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}

										if($SaldoI || $SaldoF || $Debitos || $Creditos){$Muestre=1;}
										if($IncluyeCeros=="on"){$Muestre=1;}

										if($Muestre)
										{$Muestre=0;
											if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
											else{$BG="white";$Fondo=1;}
											echo "<tr bgcolor='$BG'>";
											$Identid=explode("-",$Identificacion[4]);
											echo "<td>$filaCta[0]</td><td><ul>$Identid[0]</td><td>$Identid[1]</td><td>$Identificacion[0] $Identificacion[1] $Identificacion[2] $Identificacion[3]</td>";

											if($SaldoI<0 && $MovSI=="Debito"){$MovSI="Credito";$SaldoI=abs($SaldoI);}
											if($SaldoI<0 && $MovSI=="Credito"){$MovSI="Debito";$SaldoI=abs($SaldoI);}
											if($MovSI=="Debito"){echo "<td align='right'>".number_format($SaldoI,2)."</td><td align='right'>0.00</td>";
											if(strlen($filaCta[0])==1){$TotDebitosSI=$TotDebitosSI+$SaldoI;}}
											else
											{
												echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoI,2)."</td>";
												if(strlen($filaCta[0])==1){$TotCreditosSI=$TotCreditosSI+$SaldoI;}
											}

											echo "<td align='right'>".number_format($Debitos,2)."</td><td align='right'>".number_format($Creditos,2)."</td>";

											if($filaCta[3]=="Debito")
											{
												if($SaldoF<0){$SaldoF=$SaldoF*-1;echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";
												if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
												else{echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";
												if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
											}
											elseif($filaCta[3]=="Credito")
											{
												if($SaldoF<0){$SaldoF=$SaldoF*-1;echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";
												if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
												else{echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";
												if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
											}
										}
									}}
								}
							}
							if($filaCta[0]=="1"){$TotActivo=$SaldoF;}
							if($filaCta[0]=="2"){$TotPasivo=$SaldoF;}
							if($filaCta[0]=="3"){$TotPatrimonio=$SaldoF;}
							$Muestre="N";
							$SaldoI=0;
						}

						echo "<tr bgcolor='#e5e5e5'>";
						if($IncluyeCC=="on"){echo "<td colspan=3 class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>";}
						else{echo "<td colspan=4 class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>";}
						echo "SUMAS IGUALES</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotDebitosSI,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotCreditosSI,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotDebitosMov,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotCreditosMov,2)."</td>";

						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotSFDeb,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right:10px;'>".number_format($TotSFCred,2)."</td>";
						echo "</tr>";
					?>
					</table>
					<br><center>

					<br><br>
					<?
						$Firmas=Firmas($PerFin,$Compania);
					?>

					<table border="0">
						<tr>
							<td>______________________________</td>
							<td style="width:130px;"></td>
							<td>______________________________</td>
							<td style="width:130px;"></td>
						</tr>
						<tr>
							<td style="font-weight:bold; text-align: center;"><? echo $Firmas['Representante'][0]?></td>
							<td style="font-weight:bold; text-align: center;"> &nbsp; </td>
							<td style="font-weight:bold; text-align: center;"><? echo $Firmas['Contador'][0]?></td>
							<td style="font-weight:bold; text-align: center;"> &nbsp;</td>
						</tr>
						<tr>
							<td style="font-weight:bold; text-align: center;"><? echo $Firmas['Representante'][1]?></td>
							<td style="font-weight:bold; text-align: center;">&nbsp;</td>
							<td style="font-weight:bold; text-align: center;"><? echo $Firmas['Contador'][1] ?></td>
						</tr>
					</table>
				</div>
			</body>
	</html>		