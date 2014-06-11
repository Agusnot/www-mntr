		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("ObtenerSaldos.php");
			include("FuncionesUnload.php");
			include_once("General/Configuracion/Configuracion.php");
			@require_once ("xajax/xajax_core/xajax.inc.php");
			$obj = new xajax();
			$obj->registerFunction("Borrar_Lotes");
			$obj->registerFunction("Edicion_Lotes");
			$obj->processRequest(); 
			$ND=getdate();
				if($ND[mon]<10){$MesX = "0".$ND[mon];}else{$MesX = $ND[mon];}
				if($ND[mday]<10){$DiaX = "0".$ND[mday];}else{$DiaX = $ND[mday];}
				//UM:27-04-2011
		?>
			<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">				
					<? $obj->printJavascript("../xajax");?>
				</head>
				<?
				if(!$Dia){$Dia=$ND[mday];}
				if($Dia<10 && !$Edit){$Dia="0".$Dia;}
				if(!$Anio){$Anio=$ND[year];}
					//echo $Tipo;
					if($Tipo=="Entradas")
					{
						$cons = "Select Autoid,Tarifario,PorcActAut 
						from Consumo.TarifasxProducto
						Where Compania = '$Compania[0]'
						and AlmacenPpal = '$AlmacenPpal'
						and Anio = $Anio and PorcActAut is Not Null order by Autoid";
						$res = ExQuery($cons);
						while($fila = ExFetch($res))
						{
							$C[$fila[0]] = $C[$fila[0]] + 1;
							$Actualiza[$fila[0]][$C[$fila[0]]] = array($fila[1],$fila[2]);
							//echo "Actualiza\[$fila[0]][C\[$fila[0]]] = array($fila[1],$fila[2]);<br>";
						}
					}
					if($Tipo=="Orden de Compra")
					{
						$cons = "Select RequiereVoBo,presupuesto,comprobantepresup from Consumo.Comprobantes Where Compania='$Compania[0]' and Tipo='$Tipo' and Comprobante='$Comprobante'";
						$res = ExQuery($cons);
						$fila = ExFetch($res);
						$RequiereVoBo = $fila[0];
						$Presupuesto = $fila[1];
						$ComprobantePresup = $fila[2];
					}
				$cons = "Select AutoId,Grupo from Consumo.CodProductos Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio Group by AutoId,Grupo";
				$res = ExQuery($cons);
				while($fila = ExFetch($res)){$Grupo[$fila[0]]=$fila[1];	}
				if(!$Numero){$Numero=ConsecutivoComp($Comprobante,$Anio,"Consumo");}
				$cons="Select ComprobanteContable,Formato,DesvioTotal,CtaTipoVenta 
				from Consumo.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$CompContable=$fila[0];$Archivo=$fila[1];$Desvio=$fila[2];$CtaVenta=$fila[3];
				
				if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}

				if($Cancelar)
				{
					$con = "Delete from Consumo.EntradasxRemisiones Where TMPCOD='$TMPCOD'";
					$res = ExQuery($cons);
					?><script language="javascript">location.href="Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Salidas&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>";</script><?
				}
				//unset($Guardar);
				if($Guardar)
				{
							$MesTrabajo=$Mes;
							if($MesTrabajo<10){$MesTrabajo="0".$MesTrabajo;}
							if($Tipo=="Traslados"){ $Cedula = "99999999999-0";}
							if(!$Editar){$Numero=ConsecutivoComp($Comprobante,$Anio,"Consumo");}
							if($Tipo=="Entradas")
							{
								$consL = "Update Consumo.Lotes set Numero='$Numero',TMPCOD=NULL Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and TMPCOD='$TMPCOD'";
								$resL = ExQuery($consL);
					}
						$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-$Mes-01");
						$VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-$Mes-01","$Anio-$Mes-$Dia");
						$VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-$Mes-01","$Anio-$Mes-$Dia");
									$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01","$Anio-$Mes-$Dia");
						if($Tipo=="Salidas")
									{
										$consxyz = "Select AutoId,SUM(Cantidad) from Consumo.TmpMovimiento where TMPCOD='$TMPCOD' group by AutoId";
										$resxyz = ExQuery($consxyz);
										while($filaxyz = ExFetch($resxyz))
										{
											$Cantidadxyz[$filaxyz[0]]=$filaxyz[1];
										}
									}
									$cons1="Select AutoId,Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIVA,VrIVA,PorcReteFte,
								VrReteFte,PorcDescto,VrDescto,PorcICA,VrICA,CentroCosto,NumeroContrato,
								TipoTraslado,AlmacenPpalD,IDTraslado,IncluyeIVA,conceptortefte  
								from Consumo.TmpMovimiento where TMPCOD='$TMPCOD'";
									//echo $cons1;exit;
						$res1=ExQuery($cons1);
									while($fila1=ExFetch($res1))
						{
							if($Editar)
							{
								$consxx="Select Cantidad,TotCosto from Consumo.Movimiento where AutoId='$fila1[0]' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' 
								and TipoComprobante='$Tipo' and Numero='$Numero'";
								$resxx=ExQuery($consxx);
								$filaxx=ExFetch($resxx);
								$CantidadProd = $filaxx[0];
								$ValorProd = $filaxx[1];
							}
							$cons2="Select NombreProd1,Presentacion,UnidadMedida,Min,Max from Consumo.CodProductos where AutoId=$fila1[0] and Compania='$Compania[0]'
							and AlmacenPpal='$AlmacenPpal' and Anio = $Anio";

							$res2=ExQuery($cons2);
							$fila2=ExFetch($res2);
							$Minimo=$fila2[3];$Maximo=$fila2[4];
							
							if($Tipo=="Salidas")
							{
								$SumCantExistencias=($VrSaldoIni[$fila1[0]][0]+$VrEntradas[$fila1[0]][0]-$VrSalidas[$fila1[0]][0]+$VrDevoluciones[$fila1[0]][0])+$CantidadProd;
							}
							else
							{
								$SumCantExistencias=$VrSaldoIni[$fila1[0]][0]+$VrEntradas[$fila1[0]][0]-$VrSalidas[$fila1[0]][0]+$VrDevoluciones[$fila1[0]][0];
							}
							if($Tipo=="Salidas")
							{
								if(($SumCantExistencias-$Cantidadxyz[$fila1[0]])<0)
								{
									$NoInsert=1;
									?>
									<script language="javascript">
									alert("El producto <?echo "$fila2[0] $fila2[1] $fila2[2]"?> no puede estar debajo de cero!");
									</script>									
								<? }
							}
						}

						if(!$NoInsert){
						
						if($Editar)
						{
							$cons="Delete from Consumo.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
							$res=ExQuery($cons);
						} 
						if($RequiereVoBo!=1){$IVobo=",VoBo";$VVobo=",1";}
						$cons1="Select AutoId,Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIVA,VrIVA,PorcReteFte,VrReteFte,PorcDescto,VrDescto,PorcICA,
								VrICA,CentroCosto,IdSolicitud,NoDocAfectado,DocAfectado,NumeroContrato,TipoTraslado,AlmacenPpalD,IDTraslado,IncluyeIVA,conceptortefte  
								from Consumo.TmpMovimiento where TMPCOD='$TMPCOD'";
						$res1=ExQuery($cons1);
						if(ExNumRows($res1)>=1)
						{
							while($fila1=ExFetch($res1))
							{
												if($Tipo=="Entradas")
												{
													if($fila1[2])
													{
														for($i=1;$i<=$C[$fila1[0]];$i++)
														{
															$consAct = "Update Consumo.TarifasxProducto set
															ValorVenta = $fila1[2]+($fila1[2]*PorcActAut)/100,
															UsuUltMod = '$usuario[0]',
															UltMod = '$ND[year]-$MesX-$DiaX $ND[hours]:$ND[minutes]:$ND[seconds]'
															Where Compania = '$Compania[0]' and AlmacenPpal='$AlmacenPpal'
															and Anio = $Anio and Tarifario = '".$Actualiza[$fila1[0]][$i][0]."'
															and AutoId = $fila1[0]";
															$resAct = ExQuery($consAct);echo $consAct;
														}
														//exit;
													}
												}
												if(!$fila1[4]){$fila1[4]=0;}if(!$fila1[5]){$fila1[5]=0;}if(!$fila1[6]){$fila1[6]=0;}if(!$fila1[7]){$fila1[7]=0;}
												if(!$fila1[8]){$fila1[8]=0;}if(!$fila1[9]){$fila1[9]=0;}if(!$fila1[10]){$fila1[10]=0;}if(!$fila1[11]){$fila1[11]=0;}
												if(!$fila1[12]){$fila1[12]=0;}if(!$fila1[13]){$fila1[13]=0;}
												if(!$fila1[14]){$fila1[14]="000";}
												if($NoFactura){ $InsertNoFactura = ",NoFactura"; $valuesNoFactura = ",'$NoFactura'";}
												if($TotFactura){ $InsertTotFactura = ",VrFactura"; $valuesTotFactura = ",'$TotFactura'";}
												if(!$fila1[15]){$fila1[15]="NULL";}else{$fila1[15]="'$fila1[15]'";}
												$fila1[3] = round($fila1[3]);
												$fila1[7] = round($fila1[7]);
												$cons="Insert into Consumo.Movimiento
												(Compania,AlmacenPpal,Fecha,Comprobante,TipoComprobante,Numero,Cedula,Detalle,AutoId,UsuarioCre,FechaCre,
												Estado,Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIVA,VrIVA,PorcReteFte,VrReteFte,PorcDescto,VrDescto,
												PorcICA,VrICA,CentroCosto,IdSolicitud,NoDocAfectado,DocAfectado,NumeroContrato,TipoTraslado,AlmacenPpalD,IDTraslado,Anio,IncluyeIVA
												$InsertNoFactura $InsertTotFactura,conceptortefte,compcontable,numcompcont,Grupo$IVobo) values
												('$Compania[0]','$AlmacenPpal','$Anio-$Mes-$Dia','$Comprobante','$Tipo','$Numero','$Cedula','$Detalle',$fila1[0],'$usuario[0]',
												'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC',$fila1[1],$fila1[2],$fila1[3],$fila1[4],$fila1[5],
												$fila1[6],$fila1[7],$fila1[8],$fila1[9],$fila1[10],$fila1[11],$fila1[12],$fila1[13],'$fila1[14]',$fila1[15],'$fila1[16]','$fila1[17]','$fila1[18]',
												'$fila1[19]','$fila1[20]','$fila1[21]',$Anio,$fila1[22] $valuesNoFactura $valuesTotFactura,'$fila1[23]'
												,'$EditCompContable','$EditNumCompContable','".$Grupo[$fila1[0]]."'$VVobo)";
												$res=ExQuery($cons);
												//echo $cons;
												//exit;
							}
							
							$cons9="Update Consumo.EntradasxRemisiones set AlmacenPpal='$AlmacenPpal',CompEntrada='$Comprobante',NoCompEntrada='$Numero',TMPCOD='' where
							Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
							$res9=ExQuery($cons9);				
							
							
							$cons = "Delete from Consumo.TmpMovimiento where TMPCOD='$TMPCOD'";
							$res = ExQuery($cons);
							
							
							/////////////////////////////CAUSACION CONTABLE  ///////////////////////
							$Causacion=0;
											////////////////////////////CAUSACION CONTABLE DE SALIDAS CANCELADA////////////////////////
			//					if($Tipo=="Salidas")
			//					{
			//						$NumeroCont=ConsecutivoComp($CompContable,$Anio,"Contabilidad");
			//						$cons="Select TipoComprobant,CompPresupuesto from Contabilidad.Comprobantes where Comprobante='$CompContable' and Compania='$Compania[0]'";
			//						$res=ExQuery($cons);
			//						$fila=ExFetch($res);
			//						$TipoCompCont=$fila[0];
			//
			//
			//						$cons2="SELECT sum( TotVenta ) FROM Consumo.Movimiento WHERE
			//						Movimiento.Compania = '$Compania[0]' AND Movimiento.AlmacenPpal = '$AlmacenPpal'
			//						AND Comprobante = '$Comprobante' AND Numero = '$Numero' and Movimiento.Anio=$Anio";
			//						$res2=ExQuery($cons2);
			//						while($fila2=ExFetch($res2))
			//						{
			//							if($fila2[0]>0){$Causacion=1;
			//							$AutoId++;
			//							$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
			//							values('$TMPCOD',$AutoId,'$CompContable',$CtaVenta,'$Cedula',0,$fila2[0],'000','$NumeroCont','$Compania[0]','$Detalle')";
			//							$res=ExQuery($cons9);}
			//
			//						}
			//
			//
			//						$cons2="Select sum(TotCosto), CtaContable from Consumo.Movimiento,Consumo.Grupos
			//						where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
			//						and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
			//						and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' Group by CtaContable";
			//						$res2=ExQuery($cons2);
			//
			//						while($fila2=ExFetch($res2))
			//						{
			//							if($fila2[0]>0 && $fila2[1]){$Causacion=1;
			//							$AutoId++;
			//							$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
			//							values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',0,$fila2[0],'000','$NumeroCont','$Compania[0]','$Detalle')";
			//							$res=ExQuery($cons9);}
			//						}
			//
			//						$cons2="Select tipo from Consumo.TipoCausalida where Compania='$Compania[0]'";
			//						$res2=ExQuery($cons2);
			//						$fila2=ExFetch($res2);
			//						$TipoCausalida=$fila2[0];
			//						if($TipoCausalida==1)
			//						{
			//						$cons2="
			//							Select sum(TotCosto),Cuenta,CuentasxCC.CentroCostos from Consumo.CodProductos
			//							Inner Join Consumo.Movimiento On CodProductos.AutoId=Movimiento.AutoId
			//							Inner Join Consumo.CuentasxCC On Movimiento.CentroCosto=CuentasxCC.CentroCostos and CodProductos.Grupo=CuentasxCC.Grupo
			//							and Movimiento.Anio=CuentasxCC.Anio and CodProductos.Anio=Movimiento.Anio
			//                                                        and Movimiento.AlmacenPpal=CodProductos.AlmacenPpal and CodProductos.AlmacenPpal=CuentasxCC.ALmacenPpal
			//							where
			//
			//							Comprobante = '$Comprobante' AND Numero = '$Numero' GROUP BY Cuenta,Movimiento.CentroCosto,CodProductos.Grupo,CuentasxCC.CentroCostos";
			//						}
			//						elseif($TipoCausalida==2)
			//						{
			//							$cons2="SELECT sum( TotCosto ) , CtaGasto,Movimiento.CentroCosto
			//							from Consumo.Movimiento,Consumo.Grupos
			//							where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
			//							and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
			//							and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' GROUP BY CtaGasto,Movimiento.CentroCosto";
			//
			//						}
			//						$res2=ExQuery($cons2);
			//						while($fila2=ExFetch($res2))
			//						{
			//							if($fila2[0]>0 && $fila2[1]){$Causacion=1;
			//							$AutoId++;
			//							$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
			//							values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',$fila2[0],0,'$fila2[2]','$NumeroCont','$Compania[0]','$Detalle')";
			//							$res=ExQuery($cons9);}
			//
			//						}
			//						$cons2="SELECT sum( Movimiento.VrIVA ) ,CtaIVAS
			//						from Consumo.Movimiento,Consumo.Grupos
			//						where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
			//						and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
			//						and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' GROUP BY CtaIVAS";
			//
			//						$res2=ExQuery($cons2);
			//						while($fila2=ExFetch($res2))
			//						{
			//							if($fila2[0]>0 && $fila2[1]){$Causacion=1;
			//							$AutoId++;
			//							$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
			//							values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',$fila2[0],0,'000','$NumeroCont','$Compania[0]','$Detalle')";
			//							$res=ExQuery($cons9);}
			//
			//						}
			//
			//						$cons2="SELECT sum( VrReteFte ) ,CtaReteFteS
			//						from Consumo.Movimiento,Consumo.Grupos
			//						where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
			//						and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
			//						and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' GROUP BY CtaReteFteS";
			//
			//						$res2=ExQuery($cons2);
			//						while($fila2=ExFetch($res2))
			//						{
			//							if($fila2[0]>0 && $fila2[1]){$Causacion=1;
			//							$AutoId++;
			//							$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
			//							values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',0,$fila2[0],'000','$NumeroCont','$Compania[0]','$Detalle')";
			//							$res=ExQuery($cons9);}
			//
			//						}
			//
			//						$cons2="SELECT sum( VrICA ) ,CtaReteICAS
			//						from Consumo.Movimiento,Consumo.Grupos
			//						where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
			//						and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
			//						and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' GROUP BY CtaReteICAS";
			//
			//						$res2=ExQuery($cons2);
			//						while($fila2=ExFetch($res2))
			//						{
			//							if($fila2[0]>0 && $fila2[1]){$Causacion=1;
			//							$AutoId++;
			//							$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
			//							values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',0,$fila2[0],'000','$NumeroCont','$Compania[0]','$Detalle')";
			//							$res=ExQuery($cons9);}
			//						}
			//
			//					}
								if($Tipo=="Entradas")
								{
									$NumeroCont=ConsecutivoComp($CompContable,$Anio,"Contabilidad");

									$cons="Select TipoComprobant,CompPresupuesto from Contabilidad.Comprobantes where Comprobante='$CompContable' and Compania='$Compania[0]'";
									$res=ExQuery($cons);
									$fila=ExFetch($res);
									$TipoCompCont=$fila[0];
					
									//$cons2="Select sum(TotCosto) + sum( Movimiento.VrIVA ) , CtaContable from Consumo.Movimiento,Consumo.Grupos
									$cons2="Select sum(TotCosto), CtaContable from Consumo.Movimiento,Consumo.Grupos
									where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
									and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
									and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' Group by CtaContable";


									$res2=ExQuery($cons2);
									
									while($fila2=ExFetch($res2))
									{
										if($fila2[0]>0 && $fila2[1]){$Causacion=1;
										$AutoId++;
										$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
										values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',$fila2[0],0,'000','$NoFactura','$Compania[0]','$Detalle')";
										$res=ExQuery($cons9);}
										
									}
				
									$cons2="SELECT sum( TotCosto ) ,CtaProveedor,sum(VrReteFte),sum(Movimiento.VrIVA),sum(VrICA)
									from Consumo.Movimiento,Consumo.Grupos
									where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
									and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
									and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' Group by CtaProveedor";


									$res2=ExQuery($cons2);
									while($fila2=ExFetch($res2))
									{
										$AutoId++;$Total=$fila2[0]-$fila2[2]+$fila2[3]-$fila2[4];
										if($Total>0 && $fila2[1]){$Causacion=1;$Total=round($Total,0);
										$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
										values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',0,$Total,'000','$NoFactura','$Compania[0]','$Detalle')";
										$res=ExQuery($cons9);}
										
									}
				
									$cons2="SELECT sum( Movimiento.VrIVA ) ,CtaIVAE
									from Consumo.Movimiento,Consumo.Grupos
									where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
									and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
									and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' Group by CtaIVAE";

									$res2=ExQuery($cons2);
									while($fila2=ExFetch($res2))
									{
										if($fila2[0]>0 && $fila2[1]){$Causacion=1;
										$AutoId++;
										$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
										values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',$fila2[0],0,'000','$NoFactura','$Compania[0]','$Detalle')";
										$res=ExQuery($cons9);}
										
									}
				
									$cons2="SELECT sum( VrReteFte ) ,CtaReteFteE,conceptortefte,porcretefte,sum( TotCosto  )+ sum( Movimiento.VrIVA ) 
									from Consumo.Movimiento,Consumo.Grupos
									where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
									and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
									and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' Group by CtaReteFteE,conceptortefte,porcretefte";
									
									
									$res2=ExQuery($cons2);
									while($fila2=ExFetch($res2))
									{
										if($fila2[0]>0 && $fila2[1]){$Causacion=1;
										$AutoId++;
										if(!$fila2[3]){$fila2[3]=0;}
										$cons9="Insert into Contabilidad.TmpMovimiento
										(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle,base,conceptorte,porcretenido)
										 values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',0,$fila2[0],'000','$NoFactura',
										'$Compania[0]','$Detalle',$fila2[4],'$fila2[2]',$fila2[3])";
										$res=ExQuery($cons9);}
									}
									$cons2="SELECT sum( VrICA ) ,CtaReteICAE
									from Consumo.Movimiento,Consumo.Grupos
									where Movimiento.Grupo=Grupos.Grupo and Movimiento.Compania='$Compania[0]' and Grupos.Compania='$Compania[0]'
									and Movimiento.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
									and Movimiento.Anio=$Anio and Grupos.Anio=$Anio and Comprobante='$Comprobante' and Numero='$Numero' Group by CtaReteICAE";

									$res2=ExQuery($cons2);
									while($fila2=ExFetch($res2))
									{
										if($fila2[0]>0 && $fila2[1]){$Causacion=1;
										$AutoId++;
										$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
										values('$TMPCOD',$AutoId,'$CompContable',$fila2[1],'$Cedula',0,$fila2[0],'000','$NoFactura','$Compania[0]','$Detalle')";
										$res=ExQuery($cons9);}
									}
								}
						}

					?><script language="javascript">
					<? if($Causacion){
						
					$cons459="Select * from Contabilidad.Movimiento 
					where Compania='$Compania[0]' and Comprobante='$EditCompContable' and Numero='$EditNumCompContable' and Estado='AC'";
					$res459=ExQuery($cons459);
					if(ExNumRows($res459)>0)
					{
						$CompContable=$EditCompContable;
						$NumeroCont=$EditNumCompContable;
						$NoCargue=1;
					}
					else
					{
						$NoCargue=0;
					}
					?>
					location.href="/Contabilidad/NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&DocGen=Consumo&NoCargue=$NoCargue&DocConsumo=<? echo $Comprobante?>&NumDocConsumo=<? echo $Numero?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $CompContable?>&Numero=<? echo $NumeroCont?>&Tipo=<? echo $TipoCompCont?>&Detalle=<? echo $Detalle?>&Tercero=<? echo $Tercero?>&Identificacion=<? echo $Cedula?>&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Dia=<? echo $Dia?>&Edit=1&NoFactura=<? echo $NoFactura?>&NUMREG=<? echo $TMPCOD?>&Archivo=/Consumo/ImpCausacion.php&phpMovimiento=_Consumo_Movimiento.php&ParamsAdc=Tipo_<? echo $Tipo?>*AlmacenPpal_<? echo $AlmacenPpal?>*Comprobante_<? echo $Comprobante?>*Anio_<? echo $Anio?>*Mes_<? echo $Mes?>";
					open('/Informes/Almacen/<? echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $Numero?>&Comprobante=<? echo $Comprobante?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>&NoFactura=<? echo $NoFactura?>','','width=700,height=500,scrollbars=yes')

					<? }
					else{
					?>
					open('/Informes/Almacen/<? echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $Numero?>&Comprobante=<? echo $Comprobante?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>&NoFactura=<? echo $NoFactura?>','','width=700,height=500,scrollbars=yes')
					location.href="Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo ?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>";  <? }?>
					</script><?
				}
						
					}//HASTA AQUI FIN DE IF GUARDAR


				if($Editar)
				{
					$cons="Select distinct(AutoId),Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIVA,VrIVA,PorcReteFte,VrReteFte,PorcDescto,VrDescto,PorcICA,
					VrICA,CentroCosto,IdSolicitud,NoDocAfectado,DocAfectado,NumeroContrato,TipoTraslado,AlmacenPpalD,IDTraslado,IncluyeIVA,compcontable,numcompcont  
					from Consumo.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio
					and Estado='AC'";
					//echo $cons;
					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{
						if(!$fila[15]){$fila[15]="NULL";}else{$fila[15]="'$fila[15]'";}
						if(!$fila[16]){$fila[16]="0";}
						if(!$fila[22]){$fila[22]="0";}
						$EditCompContable=$fila[23];$EditNumCompContable=$fila[24];
						$cons2="Insert into Consumo.TmpMovimiento 
						(TMPCOD,AutoId,Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIVA,VrIVA,PorcReteFte,VrReteFte,PorcDescto,VrDescto,PorcICA,VrICA,CentroCosto,
						IdSolicitud,NoDocAfectado,DocAfectado,NumeroContrato,TipoTraslado,AlmacenPpalD,IDTraslado,IncluyeIVA)
						values('$TMPCOD',$fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],
						'$fila[14]',$fila[15],$fila[16],'$fila[17]','$fila[18]','$fila[19]','$fila[20]','$fila[21]','$fila[22]')";
						//echo $cons2;
						$res2=ExQuery($cons2);echo ExError($res2);
									if(!$AutoIds){$AutoIds=$fila[0];}
									else{$AutoIds=$AutoIds.",".$fila[0];}
							}
							if($Tipo=="Entradas" && !$Guardar)
							{
								$consL = "Update Consumo.Lotes set TMPCOD='$TMPCOD' Where Numero='$Numero' and Compania='$Compania[0]' 
								and AlmacenPpal='$AlmacenPpal' and Tipo='$Comprobante'";
								//echo $consL;
								$resL = ExQuery($consL);
							}

				}
			if($Tipo=="Entradas")
			{
				$consAlm="Select * from Consumo.AlmacenesPpales Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and SSFarmaceutico=1";
				$resAlm = ExQuery($consAlm);
				if(ExNumRows($resAlm)>0){$SSFarma=1;}
				$consxxx = "Select AutoId,Cantidad,Lote,Vence,cerrado from consumo.lotes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Numero='$Numero'
				and Tipo='Entradas' and Numero='$Numero' and TMPCOD='$TMPCOD' order by AutoId,Cantidad,Lote,Vence,cerrado";
				$resxxx = ExQuery($consxxx);
				while($filaxxx=ExFetch($resxxx))
				{
					if(!$AutoIdLotes){$AutoIdLotes = $filaxxx[0];}
					else{$AutoIdLotes=$AutoIdLotes."|$filaxxx[0]";}
					if(!$CantidadLotes){$CantidadLotes = $filaxxx[1];}
					else{$CantidadLotes = $CantidadLotes."|$filaxxx[1]";}
					if(!$LoteLotes){$LoteLotes=$filaxxx[2];}
					else{$LoteLotes=$LoteLotes."|$filaxxx[2]";}
					if(!$VenceLotes){$VenceLotes=$filaxxx[3];}
					else{$VenceLotes=$VenceLotes."|$filaxxx[3]";}
					if(!$CerradoLotes){$CerradoLotes=$filaxxx[4];}
					else{$CerradoLotes=$CerradoLotes."|$filaxxx[4]";}
				}
			}
			//echo $AutoIdLotes.$CantidadLotes.$LoteLotes.$VenceLotes.$CerradoLotes;
			?>
			<body <?php echo $backgroundBodyMentor; ?> <? if($Tipo!="Traslados"){ ?>onLoad="frames.NuevoMovimiento.Valores();document.FORMA.Tercero.focus();" <? } ?> onFocus="Ocultar()" onUnload="if(document.FORMA.NoEliminar.value == '')
				{
					<? if($Tipo=="Entradas" && $SSFarma){?>xajax_Borrar_Lotes('<? echo $Compania[0]?>','<? echo $AlmacenPpal?>','<? echo $Numero?>','<? echo $TMPCOD?>','<? echo $AutoIds?>');<?}?>
				};<? if($AutoIdLotes){?>xajax_Edicion_Lotes('<? echo $Compania[0]?>','<? echo $AlmacenPpal?>','<? echo $Numero?>','<? echo $TMPCOD?>','<? echo $AutoIdLotes?>','<? echo $CantidadLotes?>','<? echo $LoteLotes?>','<? echo $VenceLotes?>','<? echo $CerradoLotes?>')<? }?>;">
				
				<?php	
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "REGISTRO DE MOVIMIENTOS";
					if (!empty($Tipo)){
						$rutaarchivo[2] = strtoupper($Tipo);
						$rutaarchivo[3] = "NUEVO";
					}
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>	
			
			<script language="javascript" src="/Funciones.js"></script>
			<script language="javascript">
				function Validar()
				{
					if(document.FORMA.MesInvalido.value==1){alert("Periodo cerrado, no se puede asignar documento a este mes!!!");return false;}
					if(document.FORMA.Cedula.value=="0" || document.FORMA.Cedula.value==""){alert("Tercero Inválido");return false;}
					if(document.FORMA.Detalle.value==""){alert("Debe registrar un Detalle");return false;}
					if(document.FORMA.TotFactura.value == ""){alert("Escriba el Valor de la Factura");return false;}
					if(document.FORMA.NoFactura.value == ""){alert("Escriba el Numero de la Factura");return false;}
							if(document.FORMA.totprods.value != document.FORMA.totchecks.value)
							{
								alert("Asegurese de llenar todos los datos tecnicos para los productos en entrada."); return false;
							}
					if(document.FORMA.Tipo.value=="Entradas")
					{
						//if(document.FORMA.TotFactura.value==0){alert("Ingrese el valor de la Factura!");return false;}
						if(document.FORMA.TotFactura.value!="0")
						{
							Factor1=(document.FORMA.TotFactura.value*1)+(document.FORMA.Desvio.value*1);
							Factor2=(document.FORMA.TotFactura.value*1)-(document.FORMA.Desvio.value*1);
							VrEvaluar=frames.TotMovimientos.document.FORMA.TotDef.value*1; 
							if(Factor1<VrEvaluar || Factor2>VrEvaluar){alert("Debe ajustarse al valor de la Factura!");return false;}
									}
					}
							if(frames.TotMovimientos.document.FORMA.CantElem.value=="")
							{
								alert("No hay elementos en la lista de movimientos");return false;
							}
					}


				function VerSolPendientes()
				{
					frames.FrameOpener.location.href="/Informes/Almacen/Reportes/SolPendxEntrega.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+document.FORMA.Cedula.value+"&TMPCOD=<? echo $TMPCOD?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio="+document.FORMA.Anio.value+"&Fecha="+document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value;
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='50px';
					document.getElementById('FrameOpener').style.left='15px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='690';
					document.getElementById('FrameOpener').style.height='390';
				}

				function AbrirSolicitudes()
				{
					frames.FrameOpener.location.href="SolAprobadas.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+document.FORMA.Cedula.value+"&TMPCOD=<? echo $TMPCOD?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio="+document.FORMA.Anio.value+"&Fecha="+document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value;
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='50px';
					document.getElementById('FrameOpener').style.left='15px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='690';
					document.getElementById('FrameOpener').style.height='390';
				}

				function AbrirRemisiones()
				{
					frames.FrameOpener.location.href="Remisiones.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+document.FORMA.Cedula.value+"&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&TMPCOD=<? echo $TMPCOD?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio="+document.FORMA.Anio.value+"&Fecha="+document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value;
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='50px';
					document.getElementById('FrameOpener').style.left='15px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='690';
					document.getElementById('FrameOpener').style.height='390';
				}

				function AbrirOrdenCompra()
				{
					frames.FrameOpener.location.href="OrdenesCompra.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Cedula="+document.FORMA.Cedula.value+"&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&TMPCOD=<? echo $TMPCOD?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio="+document.FORMA.Anio.value+"&Fecha="+document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='50px';
					document.getElementById('FrameOpener').style.left='15px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='690';
					document.getElementById('FrameOpener').style.height='390';
				}
				function AbrirContratos()
				{
					frames.FrameOpener.location.href="VerContratos.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+document.FORMA.Cedula.value+"&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&TMPCOD=<? echo $TMPCOD?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio="+document.FORMA.Anio.value+"&Fecha="+document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='50px';
					document.getElementById('FrameOpener').style.left='15px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='690';
					document.getElementById('FrameOpener').style.height='390';
				}
					function AbrirPresupuesto()
					{
						frames.FrameOpener.location.href="VerPresupuesto.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+document.FORMA.Cedula.value+"&Comprobante=<? echo $ComprobantePresup?>&Numero=<? echo $Numero?>&TMPCOD=<? echo $TMPCOD?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio="+document.FORMA.Anio.value+"&Fecha="+document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='50px';
						document.getElementById('FrameOpener').style.left='15px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='690';
						document.getElementById('FrameOpener').style.height='390';
					}
					function AbrirLotes(AlmacenPpal,AutoId,Cantidad,Tipo)
				{
					frames.FrameOpener.location.href='Lotes.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal='+AlmacenPpal+'&AutoId='+AutoId+'&Cantidad='+Cantidad+'&Tipo='+Tipo+'&Numero=<? echo $Numero?>&TMPCOD=<? echo $TMPCOD?>';
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='150px';
					document.getElementById('FrameOpener').style.left='8px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='690';
					document.getElementById('FrameOpener').style.height='290';
				}
			</script> 
			<script language='javascript' src="/Calendario/popcalendar.js"></script> 

				<form name="FORMA" onSubmit="return Validar()">
					<input type="hidden" name="totprods" value="0" />
					<input type="hidden" name="totchecks" value="0" />
					<input type="hidden" name="NoEliminar">
					
					<table border="0">
						<tr>
							<td>
								<table  width="700px" class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
									<tr>
										<td colspan="4" class="encabezado2Horizontal">
											<?echo strtoupper($Comprobante)?>
										</td>
									</tr>
									<tr>
										<td class="encabezado2VerticalInvertido">FECHA</td>
										<td>
											<input type="Text" name="Anio" style="width:40px;" onFocus="Ocultar()" readonly="yes" value="<? echo $Anio?>">
												<?
													$cons="Select * from Central.UsuariosxModulos where Usuario='$usuario[1]' and Modulo='Administrador'";
													$res=ExQuery($cons);
													if(ExNumRows($res)==1){
														?>
														<select name="Mes" style="width:40px" onFocus="Ocultar()" onChange="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=RevisaCierre&Anio='+document.FORMA.Anio.value+'&Mes='+this.value">
															<?
															for($i=1;$i<=12;$i++){
																if($i==$Mes){echo "<option selected value='$i'>$i</option>";}
																else{echo "<option value='$i'>$i</option>";}
															}
															?>
														</select>
														<?
													}
													else{
														?>
														<input type="Text" name="Mes" readonly="yes" style="width:20px" maxlength="2" onFocus="Ocultar()" value="<? echo $Mes?>">
														<?
													}
												if(!$Dia){$Dia=$ND[mday];}
												if($Dia<10 && !$Edit){$Dia="0".$Dia;}
												if(!$FechaDocumento){$FechaDocumento="$Anio-$Mes-$Dia";}
												?>
											<input type="Text" name="Dia" maxlength="2" onFocus="Ocultar()" style="width:20px;" value="<? echo $Dia?>">
										</td>
										<td class="encabezado2VerticalInvertido">N&Uacute;MERO</td>
										<td>
											<input type="Text" name="Numero" onFocus="Ocultar()" readonly="yes" style="width:170px;font-size:16px;color:blue;border:0px;font-weight:bold" value="<?echo $Numero?>">
										</td>
									</tr>	
									<tr>
										<? if($Tipo!="Traslados"){ 
											?>
												<td class="encabezado2VerticalInvertido">TERCERO</td>
												<td>
													<input type="Text" name="Tercero" value="<? echo $Tercero?>" style="width:250px;" onKeyUp="xLetra(this);Mostrar();Cedula.value='';frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value" onKeyDown="xLetra(this)"/>
													<? 
														if($Tipo=="Salidas"){
															?>
															<img src="/Imgs/b_help.png" title="Ver terceros pendientes por solicitudes" style="cursor:hand" onClick="VerSolPendientes()"><? 
														}
													?>
												</td>
												<td class="encabezado2VerticalInvertido">IDENTIFICACI&Oacute;N</td>
												<td>
													<input type="Text" value="<? echo $Cedula?>" style="width:230px;" name="Cedula" onchange="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cedula&Cedula='+this.value"	onKeyUp="xLetra(this)" onKeyDown="xLetra(this)">
												</td>
												<? 
										} ?>
									</tr>	
									<tr>
										<td class="encabezado2VerticalInvertido">DETALLE</td>
										<td colspan="3">
											<input type="Text" value="<? echo $Detalle?>" name="Detalle" onfocus="Ocultar()" style="width:100%;" onblur="FORMA.Detalle.value=this.value" onfocus="frames.NuevoMovimiento.document.FORMA.Tercero.value=Cedula.value" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" />
										</td>
									</tr>
									<?
									if($Presupuesto){
										?>
										<tr>
											<td class="encabezado2VerticalInvertido">PRESUPUESTO</td>
											<td><input type="text" name="CompPresupuesto"></td>
											<td class="encabezado2VerticalInvertido">N&Uacute;MERO</td>
											<td><input type="text" name="NumeroCP"></td>
										</tr>
										<?
									}
								?>
					
								<?
									if($Tipo=="Salidas"){
										?>
										<tr>
											<td class="encabezado2VerticalInvertido">TARIFARIO</td>
											<td colspan="3">
												<select name="Tarifario" style="width:100%">
													<?
													$cons="Select Tarifario,xDefecto from Consumo.TarifariosVenta where Compania='$Compania[0]' 
													and AlmacenPpal='$AlmacenPpal' and Estado='AC'";
													$res=ExQuery($cons);
													while($fila=ExFetch($res)){
														if($fila[1]=="SI"){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
														else{echo "<option value='$fila[0]'>$fila[0]</option>";}
													}
													?>
												</select>
											</td>
										</tr>
										<?
									}
									else{
										echo "<input type='hidden' name='Tarifario'>";
									}
									?>
									
									<tr>
										<td colspan="4" style="text-align:left;vertical-align:middle;" class="encabezado2VerticalInvertido">
											<? 
											if($Tipo=="Orden de Compra"){
												?>
												<input type="button" value="Contratos" onClick="AbrirContratos()"/>
												<?if($Presupuesto){
													?><input type="button" value="Presupuesto" onClick="AbrirPresupuesto()"/><?
												}
											}
											
											if($Tipo=="Salidas"){
												?>
												<div align="center"><input type="button" value="Cargar de Solicitud" class="boton2Envio" onClick="AbrirSolicitudes()"/></div>
												<?php
											}
					
											if($Tipo=="Orden de Compra"){
												?>
												<input type="button" value="Cargar Solicitudes Aprobadas" onClick="AbrirSolicitudes()" />
												<? 
											}
											
											if($Tipo=="Remisiones"){
												?>
												<input type="button" value="Orden de Compra" onClick="AbrirOrdenCompra()" />
												<?
											}
											
											if($Tipo=="Entradas"){
												?>
												<input type="button"  class="boton2Envio" value="Traer Remisiones"  onClick="AbrirRemisiones()" />
												<input type="button"  class="boton2Envio" value="Orden de Compra" onClick="AbrirOrdenCompra()" />
												VALOR FACTURA	<input type="text" name="TotFactura" style="width:70px;" value="<? echo $TotFactura?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
												NO. FACTURA  <input type="text" name="NoFactura" style="width:70px;" value="<? echo $NoFactura?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" />
												<?
											}?>
										</td>
									</tr>
								</table>
									<? if($Tipo!="Traslados"){
										?>
										<iframe id="NuevoMovimiento" frameborder="0"  height="350px" width="700px" src="DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal?>&DocSoporte=<? echo $DS?>&Comprobante=<? echo $Comprobante?>&TMPCOD=<? echo $TMPCOD ?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>&Editar=<? echo $Editar?>"></iframe>
										<br>
										<iframe id="TotMovimientos" frameborder="0" width="700px" height="160px" scrolling="no" src="TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>" ></iframe><br>
										<?
									}
										else{
											?><iframe frameborder="0" width="700px" id="Productos" name="Productos" height="400" src="DetNuevoTraslado.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&TMPCOD=<? echo $TMPCOD ?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>&Anio=<? echo $Anio?>" ></iframe><br>
											<?
										}

										if($Edit){
											?>
											<script language="JavaScript">
												frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<?echo $AlmacenPpal?>&DocSoporte=<? echo $DS?>&Guardar=1&NoInsert=1&TMPCOD=<?echo $TMPCOD?>&Comprobante=<? echo $Comprobante?>&Detalle=<?echo $Detalle?>&Tercero=<? echo $Cedula?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>';
											</script>
											<?	
										}
										?>
										

				
							</td>
							<script language="JavaScript">
								function Mostrar()
								{
									document.getElementById('Busquedas').style.position='absolute';
									document.getElementById('Busquedas').style.top='50px';
									document.getElementById('Busquedas').style.right='10px';
									document.getElementById('Busquedas').style.display='';
								}
								function Ocultar()
								{
									document.getElementById('Busquedas').style.display='none';
								}
							</script>
						</tr>
						<tr>
							<td colspan="6" style="text-align:center;">
								<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
								<input type="Hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
								<input type="Hidden" name="Comprobante" value="<? echo $Comprobante?>">
								<input type="Hidden" name="Tipo" value="<? echo $Tipo?>">
								<input type="Hidden" name="Edit" value="<? echo $Edit?>">
								<input type="Submit" name="Guardar" class="boton2Envio" value="Guardar Registro" style="width:150px;"  onClick="NoEliminar.value='1'" />
								<input type="Button" name="Cancelar" class="boton2Envio" value="Cancelar" style="width:150px;" title="Cancela la operacion Actual"	onClick="location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&AnioI=<? echo $Anio?>&MesI=<? echo $Mes?>'">
								<input type="Hidden" name="NoMaxDias" value="<?echo $NoMaxDias?>">
								<input type="Hidden" name="MesInvalido" value="0">
								<input type="hidden" name="Desvio" value="<? echo $Desvio?>">
								<input type="hidden" name="Editar" value="<? echo $Editar?>">
								<input type="Hidden" name="EditCompContable" value="<? echo $EditCompContable?>">
								<input type="Hidden" name="EditNumCompContable" value="<? echo $EditNumCompContable?>">
								<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal;?>">
							
							</td>
						</tr>
					</table>
					
						
					
			</form>
			<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
			<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
		</body>
	</html>