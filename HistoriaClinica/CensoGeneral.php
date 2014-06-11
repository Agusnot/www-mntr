		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
		?>
		
		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
				<script language='javascript' src="/calendario/popcalendar.js"></script>
			</head>

				<body <?php echo $backgroundBodyMentor; ?>>
						<?php
							$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
							$rutaarchivo[1] = "UTILIDADES";
							$rutaarchivo[2] = "CENSO";
							mostrarRutaNavegacionEstatica($rutaarchivo);
						?>
					<div <?php echo $alignDiv2Mentor; ?> class="div2">	
						<form name="FORMA" method="post">
							<table class="tabla2"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>   
								<tr>
									<td class="encabezado2Horizontal" colspan="7">CENSO</td>
								</tr>
								<tr> 
									<td class='encabezado2VerticalInvertido'>TIPO DE CENSO</td>
									<td>
										<select name="TipoCenso" onChange="document.FORMA.submit()">
											<option></option>
											<option value="CensoGeneral" <? if($TipoCenso=="CensoGeneral"){?> selected<? }?>>Censo General</option>
											<option value="CensoxEdades" <? if($TipoCenso=="CensoxEdades"){?> selected<? }?>>Censo x Edades</option>
										</select>
									</td>
									<td class='encabezado2VerticalInvertido'>DESDE</td>
										<?	if(!$FechaIni){$FechaIni="$ND[year]-$ND[mon]-01";}?>    
									<td>
										<input type="Text" name="FechaIni"  readonly onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" value="<? echo $FechaIni?>" style="width:80">
									</td>
									 <td class='encabezado2VerticalInvertido'>HASTA</td>
										<?	if(!$FechaFin){$FechaFin="$ND[year]-$ND[mon]-$ND[mday]";}?>    
									<td>
										<input type="Text" name="FechaFin"  readonly onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" value="<? echo $FechaFin?>" style="width:80">
									</td>
									<td rowspan="2">
										<input type="submit" class="boton2Envio" value="Ver">
									</td>
								</tr>
								<tr>    
								<?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and hospitalizacion=1 and ambito!='Sin Ambito' and hospitaldia=0
									order by ambito";
									$res=ExQuery($cons);?>    
									<td class='encabezado2VerticalInvertido'>PROCESO</td>
									<td>
										<select name="Ambito" onChange="document.FORMA.submit()">
											<option></option>
										<?	while($fila=ExFetch($res))
											{?>
												<option value="<? echo $fila[0]?>" <? if($fila[0]==$Ambito){?> selected<? }?>><? echo $fila[0]?></option>
										<?	}?>
										</select>
									</td>
								<?	$cons="select pabellon from salud.pabellones where compania='$Compania[0]' and ambito='$Ambito' order by pabellon";
									$res=ExQuery($cons);?>    
									<td class='encabezado2VerticalInvertido'>SERVICIO</td>
									<td colspan="3">
										<select name="Pabellon" onChange="document.FORMA.submit()">
											<option></option>
										<?	while($fila=ExFetch($res))
											{
												if($fila[0]==$Pabellon){?>            	
													<option value="<? echo $fila[0]?>" selected><? echo $fila[0]?></option>
											<?	}
												else
												{?>
													<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
										<?		}
											}?>
										</select>
									</td>    
								   
								</tr>
								</tr>
								</table>
								<?
								if($TipoCenso)
								{?>
									<br>
									<table class="tabla2"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>       
								<?	if($Ambito)
									{
										$Amb="and ambito='$Ambito'";
										if($Pabellon){$Pab="and pabellon='$Pabellon'"; $Pab2="and unidad='$Pabellon'";}
									}
									else{
										$Pab="";
									}		
									$cons="select ambito from salud.ambitos where compania='$Compania[0]' and hospitalizacion=1 and ambito!='Sin Ambito' and hospitaldia=0 $Amb
									order by ambito";	
									$res=ExQuery($cons);
									while($fila=ExFetch($res))
									{
										$Ambitos[$fila[0]]=$fila[0];
									}
									
									$cons="select pabellon,ambito from salud.pabellones where compania='$Compania[0]' $Amb $Pab order by pabellon";
									$res=ExQuery($cons);
										
									while($fila=ExFetch($res))
									{
										$Pabellones[$fila[1]][$fila[0]]=$fila[0];		
									}
									$cons="select ambito,unidad,dia,numpacientes,numcamas,numcamasdispo from salud.censogeneral where compania='$Compania[0]'
									and dia>='$FechaIni' and dia<='$FechaFin' $Amb $Pab2 order by dia";
									$res=ExQuery($cons);
									while($fila=ExFetch($res))
									{
										$CensoG[$fila[0]][$fila[1]][$fila[2]]=array($fila[2],$fila[3],$fila[4],$fila[5]);
									}
									if($TipoCenso=="CensoGeneral"){
										$cons="select ambito,unidad,dia,numpacientes,numcamas,numcamasdispo from salud.censogeneral where compania='$Compania[0]'
										and dia>='$FechaIni' and dia<='$FechaFin' $Amb $Pab2 order by dia";
										//echo $cons;		
										$res=ExQuery($cons);
										if(ExNumRows($res)>0){
											while($fila=ExFetch($res))
											{
												$CensoG[$fila[0]][$fila[1]][$fila[2]]=array($fila[2],$fila[3],$fila[4],$fila[5]);
											}
										   foreach($Ambitos as $AMB)
										   {?>
												
												<tr> 
													<td class="encabezado2Horizontal" colspan="5"><? echo $AMB?></td>
												</tr><?
												foreach($Pabellones[$AMB] as $PAB)
												{?>
													<tr>
														<td class="encabezadoGrisaceo" style="text-align:center;" colspan="5"><? echo strtoupper($PAB)?></td>
													</tr>
													<tr>
														<td class='encabezado2HorizontalInvertido'>D&Iacute;A</td>
														<td class='encabezado2HorizontalInvertido'>PACIENTES</td>
														<td class='encabezado2HorizontalInvertido'>CAMAS</td>
														<td class='encabezado2HorizontalInvertido'>CAMAS DISPONIBLES</td>
													</tr>
											<?		foreach($CensoG[$AMB][$PAB] as $Datos)
													{?>
														<tr align="center">
															<td><? echo $Datos[0]?></td><td><? echo $Datos[1]?></td><td><? echo $Datos[2]?></td><td><? echo $Datos[3]?></td>
														</tr>	
												<?	}
												}
											}
										}
										else
										{?>
											<tr>
												<td class="mensaje1">No se encontraron registros con esos par&aacute;metros de b&uacute;squeda</td>
											</tr>
									<?	}
									}
									else
									{
										$cons="select codigo,desde,hasta from salud.grupoetareo where compania='$Compania[0]'";
										$res=ExQuery($cons);
										while($fila=ExFetch($res))
										{
											$GrupEt[$fila[0]]=array($fila[1],$fila[2],$fila[0]);
										}
										$cons="select ambito,unidad,dia,grupoetareo,pacientes from salud.censoxedades where compania='$Compania[0]'
										and dia>='$FechaIni' and dia<='$FechaFin' $Amb $Pab2 order by dia";
										$res=ExQuery($cons);
										if(ExNumRows($res)>0){
											while($fila=ExFetch($res))
											{
												$CensoxE[$fila[0]][$fila[1]][$fila[3]][$fila[2]]=array($fila[2],$fila[4]);
											}
										   foreach($Ambitos as $AMB)
										   {?>        	
												<tr> 
													<td class="encabezado2Horizontal" colspan="5"><? echo $AMB?></td>
												</tr><?
												foreach($Pabellones[$AMB] as $PAB)
												{?>
													<tr>
														<td colspan="5"><? echo $PAB?></td>
													</tr>
													
												<?	foreach($GrupEt as $GrpE)
													{?>
														<tr>
															<td style="font-weight:bold;" colspan="2">DE <? echo $GrpE[0]?> A <? echo $GrpE[1]?> A&Ntilde;OS</strong></td>
														</tr>
														<tr >
															<td style="font-weight:bold;">D&Iacute;A</td>
															<td style="font-weight:bold;">PACIENTES</td>
														</tr>	
													<?	foreach($CensoxE[$AMB][$PAB][$GrpE[2]] as $Datos)
														{?>
															<tr align="center">
																<td><? echo $Datos[0]?></td><td><? echo $Datos[1]?></td>
															</tr>	
													<?	}
													}
												}
											}	
										}	
										else{?>
											<tr>
												<td class="mensaje1">No se encontraron registros con esos parametros de b&uacute;squeda</td>
											</tr>
									<?	}	
									}
								}
								?>
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						</form>
					</div>	
				</body>
		</html>