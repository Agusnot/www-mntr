<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
	$ND=getdate();
	$Corte="$Anio-$MesFin-$DiaFin";
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
					<div class="divInformeContable" <?php echo $alignDivInformeContable;?>>
						<?php
	
								if(!$Generar){
									?>
									<form name="FORMA">
										<table border="1">
												<?
												$cons="Select UsuarioCre from Contabilidad.Movimiento where CierrexCajero='$Corte' and Estado='AC' Group By UsuarioCre";
												$res=ExQuery($cons);
												while($fila=ExFetch($res))
												{
													echo "<tr><td><em>$fila[0]</td><td><input type='Checkbox' name='Seleccion[$fila[0]]'></td></tr>";
												}
											?>
											<tr>
												<td colspan="2"><center><input type="Submit" class="boton2Envio" value="Generar" name="Generar"></td>
											</tr>
											<input type="Hidden" name="Anio" value="<?echo $Anio?>">
											<input type="Hidden" name="DiaFin" value="<?echo $DiaFin?>">
											<input type="Hidden" name="MesFin" value="<?echo $MesFin?>">
											<input type="Hidden" name="Corte" value="<?echo $Corte?>">
											<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
										</table>
									</form>

							<?	
								}
								else
								{
									$condAdc=" and (";
									while (list($val,$cad) = each ($Seleccion)) 
									{

										$condAdc=$condAdc." UsuarioCre='$val' Or ";
									}
									$condAdc=substr($condAdc,1,strlen($condAdc)-5);
									$condAdc=$condAdc.")";

								
							$informe = "CIERRE DE CAJA";
							$caracteristicas = " ";
							$fechaimpresion = "FECHA DE IMPRESION : "."$ND[year]-$ND[mon]-$ND[mday]";
							encabezadoInformeContable($Compania[0], $Compania[1], $informe, $caracteristicas,$fechaimpresion);
								?>
								

								<table  rules="groups"  width="90%" class="tablaInformeContable" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
									<tr>
										<td class='encabezado2HorizontalInfCont'>FECHA</td>
										<td class='encabezado2HorizontalInfCont'>N&Uacute;MERO</td>
										<td class='encabezado2HorizontalInfCont'>CONCEPTO</td>
										<td class='encabezado2HorizontalInfCont'>VALOR</td>
									</tr>
									<?
										$consCta="Select Cuenta from Contabilidad.PlanCuentas where Cuenta like '110505%' and Compania='$Compania[0]' and Tipo='Detalle' and Anio=$Anio";
										$resCta=ExQuery($consCta);
										while($filaCta=ExFetch($resCta))
										{

												$cons2="Select Nombre from Contabilidad.PlanCuentas where Cuenta='$fila[5]' and Anio='$Anio' and Compania='$Compania[0]'";
												$res2=ExQuery($cons2);
												$fila2=ExFetch($res2);

									$cons="Select Fecha,Numero,sum(Debe),FechaCre,Detalle,Cuenta from Contabilidad.Movimiento where Cuenta='$filaCta[0]' 
											and CierrexCajero='$Corte' and Estado='AC' $condAdc and Compania='$Compania[0]' and Debe>0 Group By Numero,Fecha,FechaCre,Detalle,Cuenta Order By Numero";

											$res=ExQuery($cons);echo mysql_error();
											while($fila=ExFetch($res))
											{
												echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[4]</td><td align='right'>".number_format($fila[2],2)."</td></tr>";
												//echo "<tr><td>$fila[0]</td></tr>";			
												$Total=$Total+$fila[2];
											}
											if($Total>0){
											echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td colspan=3>TOTAL</td><td align='right'>".number_format($Total,2)."</td></tr>";}
											//echo"</table>";
										?>
										<br><br>
									   <?
										}
									?>
									<!-- ____________________________________<br> -->

									<? //echo $usuario[0];
										}
									?>