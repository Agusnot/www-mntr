		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include ("Funciones.php");
			include("Consumo/ObtenerSaldosAjustes.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
			if(!$Anio){$Anio = $ND[year];}
			if(!$MesIni){$MesIni = $ND[mon];}
			if(!$MesFin){$MesFin = $ND[mon];}

			if($ComprobanteCont)
			{
				$Numero=ConsecutivoComp($ComprobanteCont,$Anio,"Contabilidad");
			}
			function CargarArchivo($AutoId,$Fecha,$ComprobanteCont,$Numero,$Identificacion,$Detalle,$Cuenta,$Debe,$Haber,$CC,$DocSoporte,$Compania)
			{
				global $usuario;global $ND;global $NoRegistro;global $FechaIni;global $FechaFin;global $AlmacenPpal;global $Anio;global $FechaIni;global $FechaFin;
				$Detalle="Salidas de $AlmacenPpal Periodo: $FechaIni a $FechaFin";
				if(!$ComprobanteCont){echo "No es posible contabilizar sin seleccionar el comprobante contable";exit;}
				if(!$NoRegistro)
				{
					$cons1="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,Compania,UsuarioCre,FechaCre,FechaDocumento,Anio)
					values($AutoId,'$Fecha','$ComprobanteCont',$Numero,'$Identificacion','$Detalle','$Cuenta','$Debe','$Haber','$CC','$DocSoporte','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$Fecha',2011)";
					$res1=ExQuery($cons1);
					$cons98="Update Consumo.Movimiento set CompContable='$ComprobanteCont',numcompcont='$Numero' where
					TipoComprobante='Salidas' and Movimiento.Compania='$Compania[0]'
					and Movimiento.AlmacenPpal='$AlmacenPpal' 
					and Movimiento.Anio=$Anio 
					and  Movimiento.Estado='AC'
					and Fecha>='$FechaIni' and Fecha<='$FechaFin'
					and (compcontable is NULL or compcontable = '')";
					$res98=ExQuery($cons98);
				}
			}			
			?>
	
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">	
			</head>
			
			<body <?php echo $backgroundBodyMentor; ?>>
				<?php	
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "PROCESOS DE CONSUMO";
					$rutaarchivo[2] = "CONTABILIZAR SALIDAS";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>	
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
					<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="6" class="encabezado2Horizontal"> CONTABILIZAR SALIDAS </td>
						</tr>
						<tr>
							<td colspan="2" class="encabezado2HorizontalInvertido">PERIODO INICIAL</td>
							<td class="encabezado2HorizontalInvertido">PERIODO FINAL</td>
							<td class="encabezado2HorizontalInvertido">ALMAC&Eacute;N PRINCIPAL</td>
							<td rowspan="2">
								<input type="submit" name="Ver" class="boton2Envio" Value="Ver" />
							</td>
							<td rowspan="2">
								<input type="submit" name="Contabilizar" class="boton2Envio" value="Contabilizar" />
							</td>
						</tr>
						<tr>
							<td style="text-align:center;">
								<select name="Anio">
									<?
									$cons = "Select Anio from Central.Anios Where Compania='$Compania[0]' order by Anio";
									$res = ExQuery($cons);
									while($fila = ExFetch($res)){
										if($fila[0]==$Anio){$Selected = " Selected ";}else{$Selected = "";}
										echo "<option $Selected value='$fila[0]'>$fila[0]</option>";
									}
									?>
								</select>
							</td>
							<td style="text-align:center;">
								<select name="MesIni">
									<? for($i=1;$i<=12;$i++){
										if($MesIni==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
										else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
									}
									?>
								</select>
								<input type='Text' name='DiaIni' style='width:20px;' maxlength="2" value='01'>
							</td>
							<td style="text-align:center;">
								<select name="MesFin">
									<? for($i=1;$i<=12;$i++){
										if($MesFin==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
										else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
									}
									?>
								</select>
								<input type='Text' name='DiaFin' style='width:20px;' maxlength="2"  value='<?if($DiaFin){echo $DiaFin;}else{echo $ND[mday];}?>'>
							</td>
							<td style="text-align:center;">
								<select name="AlmacenPpal">
									<option value="">&nbsp;</option>
									<?
									$cons = "Select AlmacenesPpales.AlmacenPpal from Consumo.UsuariosxAlmacenes,Consumo.AlmacenesPpales
									where Usuario='$usuario[1]' and AlmacenesPpales.Compania='$Compania[0]' and UsuariosxAlmacenes.Compania='$Compania[0]'
									and UsuariosxAlmacenes.AlmacenPpal=AlmacenesPpales.AlmacenPpal";
									$res = ExQuery($cons);
									while($fila = ExFetch($res)){
										if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
									}
									?>
								</select>
							</td>
						</tr>
					</table>
					<?
					if($Ver || $Contabilizar)
					{
						$cons = "Select Grupo,CtaContable from consumo.Grupos Where Compania='$Compania[0]'
						And Anio = $Anio and AlmacenPpal='$AlmacenPpal'";
						$res = ExQuery($cons);
						while($fila = ExFetch($res))
						{
							$CtaContableGrupo[$fila[0]] = $fila[1];
						}
						$cons = "Select Grupo,CentroCostos,Cuenta from Consumo.CuentasxCC
						Where Compania = '$Compania[0]' and AlmacenPpal = '$AlmacenPpal'
						And Anio = $Anio order by Grupo,CentroCostos";
						$res = ExQuery($cons);
						while($fila = ExFetch($res))
						{
							$CtaContableCC[$fila[0]][$fila[1]] = $fila[2];
						}
						$FechaIni="$Anio-$MesIni-$DiaIni";
						$FechaFin="$Anio-$MesFin-$DiaFin";
						$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
						$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
						$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
						$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
						?>
						
						<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal">GRUPO</td>
								<td class="encabezado2Horizontal">CONCEPTO</td>
								<td class="encabezado2Horizontal">SALDO INICIAL</td>
								<td class="encabezado2Horizontal">ENTRADAS</td>
								<td class="encabezado2Horizontal">SALIDAS</td>
								<td class="encabezado2Horizontal">SALDO FINAL</td>
							</tr>
						<?	


								$cons="Select AutoId,Grupo from Consumo.CodProductos where Compania='$Compania[0]'
							and AlmacenPpal='$AlmacenPpal' and Anio=$Anio order by Grupo";
							$res=ExQuery($cons);
							$TotVrSaldoIni1=0;$TotVrEntradas1=0;$TotVrSalidas1=0;$TotSaldoFinal=0;
							while($fila=ExFetch($res)){
								//$SaldoFinal=$VrSaldoIni[$fila[4]][1]+$VrEntradas[$fila[4]][1]-$VrSalidas[$fila[4]][1];
								$SIxGR[$fila[1]]=$SIxGR[$fila[1]]+$VrSaldoIni[$fila[0]][1];
								$ENTxGR[$fila[1]]=$ENTxGR[$fila[1]]+$VrEntradas[$fila[0]][1];
										if(!$VrDevoluciones[$fila[0]][1]){$VrDevoluciones[$fila[0]][1] = 0;}
										$TotDevoluciones = $TotDevoluciones + $VrDevoluciones[$fila[0]][1];
										$SALxGR[$fila[1]]=$SALxGR[$fila[1]]+$VrSalidas[$fila[0]][1]-$VrDevoluciones[$fila[0]][1];
										
							}


							$cons2="Select sum(Movimiento.TotCosto),Movimiento.Grupo,CentroCosto from Consumo.Movimiento,Consumo.CodProductos
							where TipoComprobante='Devoluciones' and CodProductos.AutoId=Movimiento.AutoId and Movimiento.Compania='$Compania[0]'
							and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.Compania='$Compania[0]'
							and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio
							and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.Estado='AC'
							and Fecha>='$FechaIni' and Fecha<='$FechaFin'
							Group By Movimiento.Grupo,CentroCosto";
							$res2=ExQuery($cons2);
							while($fila2=ExFetch($res2)){
								//$cons3="Select centrocostos from Central.CentrosCosto where Codigo='$fila2[2]' and Compania='$Compania[0]' and Anio=$Anio";
								//$res3=ExQuery($cons3);
								//$fila3=ExFetch($res3);
								$DevolucionesxGrp[$fila2[1]][$fila2[2]]=$DevolucionesxGrp[$fila2[1]][$fila2[2]] + $fila2[0];
								//$i++;
							}

								$cons2="Select sum(Movimiento.TotCosto),Movimiento.Grupo,CentroCosto from Consumo.Movimiento,Consumo.CodProductos
							where TipoComprobante='Salidas' and CodProductos.AutoId=Movimiento.AutoId and Movimiento.Compania='$Compania[0]'
							and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.Compania='$Compania[0]'
							and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio
							and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.Estado='AC'
							and Fecha>='$FechaIni' and Fecha<='$FechaFin'
							Group By Movimiento.Grupo,CentroCosto";
							$res2=ExQuery($cons2);
							while($fila2=ExFetch($res2)){
									$fila2[0] = $fila2[0] - $DevolucionesxGrp[$fila2[1]][$fila2[2]];
									$cons3="Select centrocostos from Central.CentrosCosto where Codigo='$fila2[2]' and Compania='$Compania[0]' and Anio=$Anio";
									$res3=ExQuery($cons3);
									$fila3=ExFetch($res3);
									$SalidasxGrp[$fila2[1]][$i]=array($fila2[0],$fila3[0],$fila2[2]);
									$i++;
							}

							$cons2="Select sum(Movimiento.TotCosto),Movimiento.Grupo from Consumo.Movimiento,Consumo.CodProductos
							where TipoComprobante='Salida Ajuste' and CodProductos.AutoId=Movimiento.AutoId and Movimiento.Compania='$Compania[0]'
							and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.Compania='$Compania[0]'
							and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio
							and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.Estado='AC'
							and Fecha>='$FechaIni' and Fecha<='$FechaFin'
							Group By Movimiento.Grupo";
							$res2=ExQuery($cons2);
							while($fila2=ExFetch($res2)){
								$SalidasxAjuste[$fila2[1]][$i]=array($fila2[0],"AJUSTE DE INVENTARIO","");
								$i++;
							}

							$cons="Select Grupo from Consumo.CodProductos where Estado='AC' and Compania='$Compania[0]'
							and AlmacenPpal='$AlmacenPpal' and Anio=$Anio Group by Grupo Order By Grupo";
							$res=ExQuery($cons);
							$TotVrSaldoIni1=0;$TotVrEntradas1=0;$TotVrSalidas1=0;$TotSaldoFinal=0;
							while($fila=ExFetch($res))
							{
								echo "<tr><td>$fila[0]: &nbsp;".$CtaContableGrupo[$fila[0]]."</td>";
										$CuentaContable=$CtaContableGrupo[$fila[0]];

										$n++;
										$MovimientoCont[$n]=array($CuentaContable,'000',0,$SALxGR[$fila[0]]);

										echo "<td>&nbsp;</td>";
								echo "<td style='text-align:center;'>".number_format($SIxGR[$fila[0]],2)."&nbsp;</td>";
								echo "<td style='text-align:center;'>".number_format($ENTxGR[$fila[0]],2)."&nbsp;</td>";
								echo "<td style='text-align:center;'>".number_format($SALxGR[$fila[0]],2)."&nbsp;</td>";
								$SaldoF[$fila[0]]=$SIxGR[$fila[0]]+$ENTxGR[$fila[0]]-$SALxGR[$fila[0]];
								echo "<td style='text-align:center;'>".number_format($SaldoF[$fila[0]],2)."&nbsp;</td>";
								echo "</tr>";

								if(count($SalidasxGrp[$fila[0]])>0){
								foreach($SalidasxGrp[$fila[0]] as $SalxCC)
								{
												if(!$CtaContableCC[$fila[0]][$SalxCC[2]])
												{
													$NoCont = 1;
													$bg = " style='color: #FF0000; font-weight:bold; text-align:center;' ";
												}
												else{unset($bg);}
												echo "<tr $bg>
													<td> &nbsp;</td>
													<td style='text-align:center;'>".$SalxCC[1] . " " . $SalxCC[2]." (".$CtaContableCC[$fila[0]][$SalxCC[2]].") &nbsp; </td>
													<td> &nbsp;</td>
													<td> &nbsp;</td>
													<td style='text-align:center;'>".number_format($SalxCC[0],2)."&nbsp;</td></tr>";
												/////////////////ESTA ES LA MATRIZ PARA REALIZAR EL MOVIMIENTO CONTABLE///////////////

												if(!$SalxCC[3])
												{
													$CuentaContable=$CtaContableCC[$fila[0]][$SalxCC[2]];
													$n++;
													$MovimientoCont[$n]=array($CuentaContable,$SalxCC[2],$SalxCC[0],0);
												}
												$CuentaContable="";
												//echo "<tr><td><b><i>".$CtaContableGrupo[$fila[0]]."--".$CtaContableCC[$fila[0]][$SalxCC[2]]."</i></b></td></tr>";
								}}
								if(count($SalidasxAjuste[$fila[0]])>0){
								foreach($SalidasxAjuste[$fila[0]] as $SalxCC)
								{
									echo "<tr><td></td><td>".$SalxCC[1] . " " . $SalxCC[2]."</td><td></td><td></td><td style='text-align:right;padding-right:10px;'>".number_format($SalxCC[0],2)."</td></tr>";
								}}

								$TotSI=$TotSI+$SIxGR[$fila[0]];
								$TotEnt=$TotEnt+$ENTxGR[$fila[0]];
								$TotSal=$TotSal+$SALxGR[$fila[0]];
								$TotSF=$TotSF+$SaldoF[$fila[0]];
							}
							echo "<tr>";
								echo "<td colspan='2' class='filaTotales' style='text-align:right;padding-right:10px;'>TOTALES</td>";
								echo "<td class='filaTotales' style='text-align:center;'>".number_format($TotSI,2)."</td>";
								echo "<td class='filaTotales' style='text-align:center;'>".number_format($TotEnt,2)."</td>";
								echo "<td class='filaTotales' style='text-align:center;'>".number_format($TotSal,2)."</td>";
								echo "<td class='filaTotales' style='text-align:center;'>".number_format($TotSF,2)."</td>";
							echo "</tr>";
						?>
						</table>
				<?
					}
					
					?>
					<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="4" class="encabezado2Horizontal"> RESUMEN CONTABLE </td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">COMPROBANTE</td>
							<td colspan="3">
								<select name='ComprobanteCont'>
									<option value="">&nbsp; </option>
									<?php
										$cons11="Select Comprobante from Contabilidad.Comprobantes where Compania='$Compania[0]'";
										$res11=ExQuery($cons11);
										while($fila11=ExFetch($res11)){
											echo "<option value='$fila11[0]'>$fila11[0]</option>";
										}
									?>	
								</select>

							</td>
						</tr>
					
					<tr>
						<td class="encabezado2HorizontalInvertido">CUENTA</td>
						<td class="encabezado2HorizontalInvertido">CC</td>
						<td class="encabezado2HorizontalInvertido">D&Eacute;BITOS</td>
						<td class="encabezado2HorizontalInvertido">CR&Eacute;DITOS</td>
					</tr>

					<?php
					if(count($MovimientoCont)>0){
						foreach($MovimientoCont as $ListaMovimientos)
						{
							$AutoId++;
							echo "<tr>";
								echo "<td style='text-align:center;'>".$ListaMovimientos[0]."&nbsp;</td>";
								echo "<td>".$ListaMovimientos[1]."&nbsp;</td>";
								echo "<td style='text-align:right;padding-right:10px;'>".number_format($ListaMovimientos[2],0)."</td>";
								echo "<td style='text-align:right;padding-right:10px;'>".number_format($ListaMovimientos[3],0)."</td>";
							echo "</tr>";
							if($Contabilizar){
								CargarArchivo($AutoId,$FechaFin,$ComprobanteCont,$Numero,$Identificacion,$Detalle,$ListaMovimientos[0],$ListaMovimientos[2],$ListaMovimientos[3],$ListaMovimientos[1],'0',$Compania);
							}
							$TotDebitos=$TotDebitos+$ListaMovimientos[2];
							$TotCreditos=$TotCreditos+$ListaMovimientos[3];
						}
						//$TotDebitos=$TotDebitos-$TotDevoluciones;
						echo "<tr>";
							echo "<td colspan='2' class='filaTotales' style='text-align:right;padding-right:10px;'>SUMAS</td>";
							echo "<td class='filaTotales' style='text-align:right;padding-right:10px;'>".number_format($TotDebitos,2)."</td>";
							echo "<td class='filaTotales' style='text-align:right;padding-right:10px;'>".number_format($TotCreditos,2)."</td>";
						echo "</tr>";

						if(round($TotCreditos)!=round($TotDebitos)){$NoCont=1;}
					}
					if($NoCont || count($MovimientoCont)==0)
					{
						?>
						<script language="Javascript">
							document.FORMA.Contabilizar.disabled = true;
						</script>
						<?
					}

					echo "</table>";

					if($Contabilizar){  
						?> <script language='javascript'>
							location.href='ContabilizarSalidas.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>&MesIni=<? echo $MesIni?>&MesFin=<? echo $MesFin?>&DiaIni=<? echo $DiaIni?>&DiaFin=<? echo $DiaFin?>&Ver=1';
							</script><?
					}
					?>
				</form>
			</div>
		</body>
	</html>		