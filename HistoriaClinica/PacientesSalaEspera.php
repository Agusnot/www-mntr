		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($ND[mon]<10){$Mes="0".$ND[mon];}else{$Mes=$ND[mon];}
			if($ND[mday]<10){$Dia="0".$ND[mday];}{$Dia=$ND[mday];}
			$ban=0;
			$ban2=0;
			if($Salida){
				$cons="select numservicio from salud.agenda where compania='$Compania[0]' and numservicio=$NumServSalida";
				$res=ExQuery($cons);
				//echo $cons;
				if(ExNumRows($res)==1){
					$cons="update salud.servicios set estado='AN',fechaegr='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',usuegreso='$usuario[1]' where compania='$Compania[0]' 
					and numservicio=$NumServSalida";
					//echo $cons;
					$res=ExQuery($cons);
				}
				else{
					//echo ExNumRows($res);
					$Atends[$NumServSalida]=$NumServSalida;
					session_register('Atends');
					//echo $Atends[$NumServSalida];
				}
			}
			if($Id!='')
			{
				$cons="update salud.agenda set estado='Atendida',usumodif='$usuario[1]' 
				where compania='$Compania[0]' and hrsini=$HrIni and minsini=$MinIni and cedula='$Ced' and numservicio=$NumServ and id=$Id";
				$res=ExQuery($cons);
				if(!$Medico){
					?><script language="javascript">location.href="ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $Ced?>&Buscar=1";</script><?
				}
				else{
					?><script language="javascript">opener.location.href="ResultBuscarHC.php?DatNameSID<? echo $DatNameSID?>&Cedula=<? echo $Ced?>&Buscar=1";
					window.close();</script><?
				}
			}
			//$cons="select numservicio,compcontablerecaudo,recaudo from facturacion.liquidacion where compania='$Compania[0]' and estado='AC' and recaudo=1 and compcontablerecaudo is not null";
			$cons="select numservicio,compcontablerecaudo,recaudo from facturacion.liquidacion where compania='$Compania[0]' and estado='AC' and compcontablerecaudo is not null";
			//echo $cons;
			$res=ExQuery($cons);
			while($fila=ExFetch($res)){
				$Copagos[$fila[0]]=array($fila[0],$fila[1],$fila[2]);
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
					
					<meta http-equiv="refresh" content="30" />
					<script language="javascript">
						function Atender(Ced,NumS,HI,MI,ID)
						{		
							document.FORMA.Ced.value=Ced;
							document.FORMA.NumServ.value=NumS;
							document.FORMA.HrIni.value=HI;
							document.FORMA.MinIni.value=MI;
							document.FORMA.Id.value=ID;
							document.FORMA.submit();
							//location.href="PacientesSalaEspera.php?Ced="+Ced+"&NumServ="+NumS+"&HrIni="+HI+"MI="+MI;
							
						}
					</script>
				</head>

				<body <?php echo $backgroundBodyMentor; ?>>
						<?php
							$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
							$rutaarchivo[1] = "CONSULTA EXTERNA";
							$rutaarchivo[2] = "PACIENTES EN SALA DE ESPERA";
							mostrarRutaNavegacionEstatica($rutaarchivo);
						?>
					<div <?php echo $alignDiv3Mentor; ?> class="div3">	
						<form name="FORMA" method="post">
								<table class="tabla3"    <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>   
								<?
									$cons="select primnom,segnom,primape,segape,agenda.cedula,hrsini,minsini,tiempocons,hrsfin,minsfin,agenda.entidad,agenda.contrato,agenda.nocontrato,agenda.numservicio,agenda.id,agenda.estado,servicios.estado
									from salud.agenda,central.terceros,salud.servicios
									where agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.identificacion=agenda.cedula 
									and servicios.cedula=agenda.cedula and agenda.medico='$usuario[1]' and servicios.estado='AC' and  agenda.fecha='$ND[year]-$Mes-$Dia' and agenda.numservicio=servicios.numservicio
									group by primnom,segnom,primape,segape,agenda.cedula,hrsini,minsini,tiempocons,hrsfin,minsfin,agenda.entidad,agenda.contrato,agenda.nocontrato,agenda.numservicio,agenda.id,agenda.estado,servicios.estado
									order by hrsini,minsini";
									$res=ExQuery($cons);
									while($fila=ExFetch($res))
									{
										//echo "$fila[4] $fila[13] $fila[15] $fila[16]<br>";
										if($fila[15]=='Atendida'&&!$Atends[$fila[13]]){
											$ban=1;
										}
									}	
									$cons="select primnom,segnom,primape,segape,agenda.cedula,hrsini,minsini,tiempocons,hrsfin,minsfin,agenda.entidad,agenda.contrato,agenda.nocontrato,agenda.numservicio,agenda.id,agenda.estado,cup,cups.nombre
									from salud.agenda,central.terceros,salud.servicios,contratacionsalud.cups
									where agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.identificacion=agenda.cedula and 
									servicios.cedula=agenda.cedula and agenda.medico='$usuario[1]' and servicios.estado='AC' and  agenda.fecha='$ND[year]-$Mes-$Dia' and agenda.numservicio=servicios.numservicio and cups.compania='$Compania[0]' and cups.codigo=cup
									group by primnom,segnom,primape,segape,agenda.cedula,hrsini,minsini,tiempocons,hrsfin,minsfin,agenda.entidad,agenda.contrato,agenda.nocontrato,agenda.numservicio,agenda.id,agenda.estado,cup,cups.nombre
									order by hrsini,minsini";
										
									$res=ExQuery($cons);
									//echo "<br>".$cons;
									if(ExNumRows($res)>0){?>
										<tr>  
											<td class="encabezado2Horizontal" colspan="11">PACIENTES EN SALA DE ESPERA</td>
										</tr> 
										<tr> 
											<td class="encabezado2HorizontalInvertido">NOMBRE</td>
											<td class="encabezado2HorizontalInvertido">IDENTIFICACI&Oacute;N</td>
											<td class="encabezado2HorizontalInvertido">HORA INICIO</td>
											<td class="encabezado2HorizontalInvertido">TIEMPO</td>
											<td class="encabezado2HorizontalInvertido">HORA FIN</td>
											<td class="encabezado2HorizontalInvertido">ENTIDAD</td>
											<td class="encabezado2HorizontalInvertido">CONTRATO</td>
											<td class="encabezado2HorizontalInvertido">NO. CONTRATO</td>
											<td class="encabezado2HorizontalInvertido">CUP</td>
											<td class="encabezado2HorizontalInvertido">ATENDER</td>
										</tr> 
									<?	while($fila=ExFetch($res))
										{				
											//if($Copagos[$fila[13]][2]==1){			
											if(1==1){			
												$cons2="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila[10]'";				
												//echo "$fila[13] ".$Copagos[$fila[13]];			
												$res2=ExQuery($cons2); $fila2=ExFetch($res2);
												if(!$Atends[$fila[13]]){	
													if($fila[15]=='Atendida'){
														if($ban2==0){?>
															<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="text-transform:uppercase; font:12px Tahoma">
												<?			if($fila[6]==0){$cero='0';}else{$cero='';}
															if($fila[9]==0){$cero2='0';}else{$cero2='';}?>	
															<td onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[4]?>&Buscar=1'" title="Abrir Historia Clinica"
															style="cursor:hand">
																<? echo "$fila[2] $fila[3] $fila[0] $fila[1]";?></td>
															<td align='center' onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[4]?>&Buscar=1'" 
																title="Abrir Historia Clinica" style="cursor:hand">
																<? echo $fila[4]?></td>
															<td align='center'><? echo "$fila[5]:$fila[6]$cero";?></td>
															<td align='center' ><? echo $fila[7];?></td><td align='center' ><? echo "$fila[8]:$fila[9]$cero2";?></td>
															<td align='center'><? echo "$fila2[0] $fila2[1] $fila2[2] $fila2[3]";?></td><td><? echo $fila[11];?></td><td><? echo $fila[12];?></td>
															<td><? echo "$fila[16] - $fila[17]";?></td>
															<td align="center">
															<img src="/Imgs/b_check.png" style="cursor:hand" title="Dar Salida" 
															onClick="location.href='PacientesSalaEspera.php?DatNameSID=<? echo $DatNameSID?>&Salida=1&NumServSalida=<? echo $fila[13]?>'">	
												<?			$ban2=1;
														}
													}
													else{//echo "$fila[4] $fila[15]";
														if($ban==0&&$ban2==0){?>
															<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="text-transform:uppercase; font:12px Tahoma; cursor:hand"
															title="Atender" onClick="Atender('<? echo $fila[4]?>','<? echo $fila[13]?>','<? echo $fila[5]?>','<? echo $fila[6]?>','<? echo $fila[14]?>')">
												<?			if($fila[6]==0){$cero='0';}else{$cero='';}
															if($fila[9]==0){$cero2='0';}else{$cero2='';}?>	
															<td><? echo "$fila[2] $fila[3] $fila[0] $fila[1]";?></td><td align='center'><? echo $fila[4]?></td><td align='center'><? echo "$fila[5]:$fila[6]$cero";?></td>
															<td align='center' ><? echo $fila[7];?></td><td align='center' ><? echo "$fila[8]:$fila[9]$cero2";?></td>
															<td align='center'><? echo "$fila2[0] $fila2[1] $fila2[2] $fila2[3]";?></td><td><? echo $fila[11];?></td><td><? echo $fila[12];?></td>
															 <td><? echo "$fila[16] - $fila[17]";?></td>
															<td align="center">
															<img src="/Imgs/s_process.png" title="Atender" style="cursor:hand"
															onClick="Atender('<? echo $fila[4]?>','<? echo $fila[13]?>','<? echo $fila[5]?>','<? echo $fila[6]?>','<? echo $fila[14]?>')" title="Atender">				
												<?      	//$ban=1;
														}
														else{?>
															<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="text-transform:uppercase; font:12px Tahoma; cursor:hand"
															onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[4]?>&Buscar=1'" title="Abrir Historia Clinica">
												<?			if($fila[6]==0){$cero='0';}else{$cero='';}
															if($fila[9]==0){$cero2='0';}else{$cero2='';}?>	
															<td><? echo "$fila[2] $fila[3] $fila[0] $fila[1]";?></td><td align='center'><? echo $fila[4]?></td><td align='center'><? echo "$fila[5]:$fila[6]$cero";?></td>
															<td align='center' ><? echo $fila[7];?></td><td align='center' ><? echo "$fila[8]:$fila[9]$cero2";?></td>
															<td align='center'><? echo "$fila2[0] $fila2[1] $fila2[2] $fila2[3]";?></td><td><? echo $fila[11];?></td><td><? echo $fila[12];?></td>
															 <td><? echo "$fila[16] - $fila[17]";?></td>
															<td align="center">
													<?	}						
													}?>&nbsp;
												</td>          	
								<?				}
											}
											else{
												if($Copagos[$fila[13]][1]!=''){	
													$cons2="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila[10]'";
													//echo $cons2;			
													$res2=ExQuery($cons2); $fila2=ExFetch($res2);?>
													<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="text-transform:uppercase; font:12px Tahoma">
									<?		/*		if($fila[6]==0){$cero='0';}else{$cero='';}
													if($fila[9]==0){$cero2='0';}else{$cero2='';}	
													echo "<td>$fila[2] $fila[3] $fila[0] $fila[1]</td><td align='center'>$fila[4]</td><td align='center'>$fila[5]:$fila[6]$cero</td><td align='center'>$fila[7]</td>
													<td align='center'>$fila[8]:$fila[9]$cero2</td><td align='center'>$fila2[0] $fila2[1] $fila2[2] $fila2[3]</td><td>$fila[11]</td><td>$fila[12]</td>";?>
													<td align="center">
													<input title="Atender" type="checkbox" name="<? echo $fila[4]?>" 
														onClick="Atender('<? echo $fila[4]?>','<? echo $fila[13]?>','<? echo $fila[5]?>','<? echo $fila[6]?>','<? echo $fila[14]?>')">
													</td>		                    	
										<?			//______________________________________________________*/?>
													
													<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="text-transform:uppercase; font:12px Tahoma">
										<?			if($fila[6]==0){$cero='0';}else{$cero='';}
													if($fila[9]==0){$cero2='0';}else{$cero2='';}?>	
													<td>
														<? echo "$fila[2] $fila[3] $fila[0] $fila[1]";?>
													</td>
													<td align='center'>
														<? echo $fila[4]?>
													</td>
													<td align='center'>
														<? echo "$fila[5]:$fila[6]$cero";?>
													</td>
													<td align='center' >
														<? echo $fila[7];?>
													</td>
													<td align='center'>
														<? echo "$fila[8]:$fila[9]$cero2";?>
													</td>
													<td align='center' >
														<? echo "$fila2[0] $fila2[1] $fila2[2] $fila2[3]";?>
													</td>
													<td>
														<? echo $fila[11];?>
													</td>
													<td>
														<? echo $fila[12];?>
													</td>
													<td align="center">
													<?	if($fila[15]=='Atendida'){
															if($ban2==0){?>
																<img src="/Imgs/b_check.png" style="cursor:hand" title="Dar Salida"
																onClick="location.href='PacientesSalaEspera.php?DatNameSID=<? echo $DatNameSID?>&Salida=1&NumServSalida=<? echo $fila[13]?>'">
													<?			$ban2=1;
															}
														}
														else{
															if($ban==0&&$ban2==0){?>
																<img src="/Imgs/s_process.png" title="Atender" style="cursor:hand"
															onClick="Atender('<? echo $fila[4]?>','<? echo $fila[13]?>','<? echo $fila[5]?>','<? echo $fila[6]?>','<? echo $fila[14]?>')" title="Atender">
															<?	//$ban=1;
															}
														}?>
														&nbsp;
													</td>  
											<?	}
											}
										}?>
								<?	}
									else{?>
										<tr>
											<td class="mensaje1" >No hay pacientes en sala de espera</td></tr>
								<?	}?>
								</table>
								
								<table class="tabla3" style="margin-top:25px;margin-bottom:25px;"   <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>> 
								<?
									
									$cons="select primnom,segnom,primape,segape,agenda.cedula,hrsini,minsini,tiempocons,hrsfin,minsfin,agenda.entidad,agenda.contrato,agenda.nocontrato,agenda.numservicio,agenda.id
									from salud.agenda,central.terceros,salud.servicios
									where agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.identificacion=agenda.cedula and 
									servicios.cedula=agenda.cedula and agenda.medico='$usuario[1]' and agenda.estado='Atendida' and  servicios.estado='AN' and  agenda.fecha='$ND[year]-$Mes-$Dia' 
									and agenda.numservicio=servicios.numservicio
									group by primnom,segnom,primape,segape,agenda.cedula,hrsini,minsini,tiempocons,hrsfin,minsfin,agenda.entidad,agenda.contrato,agenda.nocontrato,agenda.numservicio,agenda.id
									order by hrsini,minsini";
									$res=ExQuery($cons);
									//echo $cons;
									if(ExNumRows($res)>0){
										$BanMost=1;?>
										<tr>   
											<td class="encabezado2Horizontal" colspan="8">PACIENTES ATENDIDOS</td>
										</tr>
										<tr> 
											<td class="encabezado2HorizontalInvertido">NOMBRE</td>
											<td class="encabezado2HorizontalInvertido">IDENTIFICACI&Oacute;N</td>
											<td class="encabezado2HorizontalInvertido">HORA INICIO</td>
											<td class="encabezado2HorizontalInvertido">TIEMPO</td>
											<td class="encabezado2HorizontalInvertido">HORA FIN</td>
											<td class="encabezado2HorizontalInvertido">ENTIDAD</td>
											<td class="encabezado2HorizontalInvertido">CONTRATO</td>
											<td class="encabezado2HorizontalInvertido">NO. CONTRATO</td>
										</tr>
									<?	while($fila=ExFetch($res))
										{
											$cons2="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila[10]'";
											//echo $cons2;			
											$res2=ExQuery($cons2); $fila2=ExFetch($res2);?>
											<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="text-transform:uppercase; font:12px Tahoma; cursor:hand" title="Abrir Historia Clinica"
											onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[4]?>&Buscar=1'">
								<?			if($fila[6]==0){$cero='0';}else{$cero='';}
											if($fila[9]==0){$cero2='0';}else{$cero2='';}	
											echo "<td>$fila[0] $fila[1] $fila[2] $fila[3]</td><td align='center'>$fila[4]</td><td align='center'>$fila[5]:$fila[6]$cero</td><td align='center'>$fila[7]</td>
											<td align='center'>$fila[8]:$fila[9]$cero2</td><td align='center'>$fila2[0] $fila2[1] $fila2[2] $fila2[3]</td><td>$fila[11]</td><td>$fila[12]</td>";
										}		
									}
									if($Atends){
										foreach($Atends as $Ats){
											$cons="select primnom,segnom,primape,segape,agenda.cedula,hrsini,minsini,tiempocons,hrsfin,minsfin,agenda.entidad,agenda.contrato,agenda.nocontrato,agenda.numservicio,agenda.id
											from salud.agenda,central.terceros,salud.servicios
											where agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.identificacion=agenda.cedula and 
											servicios.cedula=agenda.cedula and agenda.medico='$usuario[1]' and agenda.estado='Atendida' and  servicios.numservicio=$Ats and  agenda.fecha='$ND[year]-$Mes-$Dia' 
											and agenda.numservicio=servicios.numservicio
											group by primnom,segnom,primape,segape,agenda.cedula,hrsini,minsini,tiempocons,hrsfin,minsfin,agenda.entidad,agenda.contrato,agenda.nocontrato,agenda.numservicio,agenda.id
											order by hrsini,minsini";
											//echo $cons;
											$res=ExQuery($cons);
											if(ExNumRows($res)>0)
											{
												if(!$BanMost){
													$BanMost=1;?>
													<tr>   
														<td class="encabezado2Horizontal" colspan="8">PACIENTES ATENDIDOS</td>
													</tr>
													<tr> 
														<td class="encabezado2HorizontalInvertido">NOMBRE</td>
														<td class="encabezado2HorizontalInvertido">IDENTIFICACI&Oacute;N</td>
														<td class="encabezado2HorizontalInvertido">HORA INICIO</td>
														<td class="encabezado2HorizontalInvertido">TIEMPO</td>
														<td class="encabezado2HorizontalInvertido">HORA FIN</td>
														<td class="encabezado2HorizontalInvertido">ENTIDAD</td>
														<td class="encabezado2HorizontalInvertido">CONTRATO</td>
														<td class="encabezado2HorizontalInvertido">NO. CONTRATO</td>
													</tr>
										<?		}
												$fila=Exfetch($res);
												$cons2="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila[10]'";
												//echo $cons2;			
												$res2=ExQuery($cons2); $fila2=ExFetch($res2);?>
												<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="text-transform:uppercase; font:12px Tahoma; cursor:hand" title="Abrir Historia Clinica"
												onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[4]?>&Buscar=1'">
									<?			if($fila[6]==0){$cero='0';}else{$cero='';}
												if($fila[9]==0){$cero2='0';}else{$cero2='';}	
												echo "<td>$fila[0] $fila[1] $fila[2] $fila[3]</td><td align='center'>$fila[4]</td><td align='center'>$fila[5]:$fila[6]$cero</td><td align='center'>$fila[7]</td>
												<td align='center'>$fila[8]:$fila[9]$cero2</td><td align='center'>$fila2[0] $fila2[1] $fila2[2] $fila2[3]</td><td>$fila[11]</td><td>$fila[12]</td>";
											}
										}
									}
								?>
								</table> 
								<input type="hidden" name="Ced" value="">
								<input type="hidden" name="NumServ" value="">
								<input type="hidden" name="HrIni" value="">
								<input type="hidden" name="MinIni" value="">
								<input type="hidden" name="Id" value="">
								<input type="hidden" name="Medico" value="<? echo $Medico?>"> 
								<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						</form>
					</div>	
				</body>
		</html>
