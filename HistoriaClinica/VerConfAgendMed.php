		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			global $ban;
			global $ban2;
				
			if($Profecional==""){
				$Profecional = $usuario[1];
			}
				
			if(!isset($Fecha))
				$Fecha = date("Y-m-d");
				
			$cons="select tiempo from salud.tiemposintervalocitas where compania='$Compania[0]'";
			$res=ExQuery($cons);
			$fila=ExFetch($res); if($fila[0]){$TiempInt=$fila[0];}else{$TiempInt="10";}
			
			$cons="select intervaloagenda from salud.medicos where compania='$Compania[0]' and usuario='$Profecional'";
			$res=ExQuery($cons);
			$fila=ExFetch($res); if($fila[0]){$TiempInt=$fila[0];}
			
			$ban=0;
			$ban2=0;
			$cons="select nombre from central.usuarios where usuario='$Profecional'";
			$res=ExQuery($cons);
			$F=ExFetch($res);	
			$cons="select codigo,nombre from contratacionsalud.cups where compania='$Compania[0]'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$Cups[$fila[0]]=$fila[1];	
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
			<div align="center">
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
						<table class="tabla3"    <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?> > 
						<?php
						if($Fecha!=''&&$Profecional!=''){
							$cons="select dia,motivo from salud.bloqueoxdia where dia='$Fecha' and compania='$Compania[0]'";
							$res=ExQuery($cons);echo ExError();
							if(ExNumRows($res)>0){
								$fila=ExFetch($res);?>
								 <tr bgcolor="#e5e5e5" style=" font-weight:bold" align="center">
									<td>El dia <? echo $fila[0]?> por motivo de <? echo $fila[1]?></td>
								</tr>
						<?php	}
							else{
								$cons="select horaini,minsinicio,horasfin,minsfin,idhorario,cuppermitido from salud.dispoconsexterna 
								where usuario='$Profecional' and fecha='$Fecha' and compania='$Compania[0]' order by idhorario";
								$res=ExQuery($cons);echo ExError();
										//echo $cons;
								if(ExNumRows($res)>0){?>     	
									<tr>
										<td class="encabezado2Horizontal" colspan="8"><? echo "$F[0]-$Especialidad";?></td>            
										</tr>
										<tr>
											<td class="encabezadoGrisaceo" style="text-align:center;" colspan="8"> <? echo "$Fecha";?></td></tr>
											<tr>
												<td class="encabezado2HorizontalInvertido">HORA</td>
												<td class="encabezado2HorizontalInvertido">IDENTIFICACI&Oacute;N</td>
												<td class="encabezado2HorizontalInvertido">NOMBRE</td>
												<td class="encabezado2HorizontalInvertido">TEL&Eacute;FONO</td>
												<td class="encabezado2HorizontalInvertido">ENTIDAD</td>
												<td class="encabezado2HorizontalInvertido">TIPO CONSULTA</td>
												<td class="encabezado2HorizontalInvertido">CONFIRMADA</td>
												<td class="encabezado2HorizontalInvertido">ESTADO</td> 
											</tr>             
								<?php
									while($fila=ExFetch($res))
									{
										$tim=((($fila[2]-$fila[0])*60)-$fila[1])+$fila[3];
										//echo "tim=$tim";
										$HI=$fila[0];$MI=$fila[1];
										if($MI==(60-$TiempInt)){$HF=$HI+1;$MF=0;}else{$HF=$HI;$MF=$MI+$TiempInt;}
										for($i=0;$i<$tim;$i=$i+$TiempInt){
											if($MI<$TiempInt){$cero='0';}else{$cero='';}			
											$cons2="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons,fecha,id,cup,usuconfirma
											from central.terceros,salud.agenda 
											where terceros.identificacion=agenda.cedula and medico='$Profecional' and fecha='$Fecha' 
											and (estado='Pendiente' or estado='Activa' or estado='Atendida') and hrsini=$HI and minsini=$MI 
											and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' order by hrsini,minsini,fecha";
											/*$cons2="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons,fecha,id,cup,usuconfirma
											from central.terceros,salud.agenda 
											where terceros.identificacion=agenda.cedula and medico='$Profecional' and fecha='$Fecha' 
											and (estado='Pendiente' or estado='Activa' or estado='Atendida') and hrsini=$HI and minsini=$MI 
											and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' order by hrsini";*/
											//echo $cons2;
											$res2=ExQuery($cons2);echo ExError();
											if(ExNumRows($res2)>0)
											{					
												$azul=1;
												while($fila2=ExFetch($res2))
												{
													if($fila2[16]){$Confirm="Si";}else{$Confirm="No";}
													if($fila2[3]<$TiempInt){$cero2='0';}else{$cero2='';}	
													$cons5="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros where  identificacion='$fila2[10]' and 
													Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
													$res5=ExQuery($cons5);echo ExError();//consulta de la agenda
													$fila5=ExFetchArray($res5);
													if($fila2[11]=='Atendida'){?>
																					<!--<tr onMouseOver="this.bgColor='#AAD4FF'"  onMouseOut="this.bgColor=''" align="center">-->
																					<tr onMouseOver="this.bgColor='#AAD4FF'"  onMouseOut="this.bgColor=''" style="cursor:hand; background-color: '#c4ffc4';" align="center" title="Abrir Historia Clinica" onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila2[4]?>&Buscar=1'">
											<?php		}
													else{
														if($fila2[11]=='Activa'){?>
															<!--<tr onMouseOver="this.bgColor='#AAD4FF'" style="cursor:hand" onMouseOut="this.bgColor=''" align="center" title="Agregar Sobrecupo" onClick=	"parent.location.href='NewEstadoAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $fila2[14]?>&Especialidad=<? echo $Especialidad?>&Fecha=<? echo $Fecha?>&Profecional=<? echo $Profecional?>&HrIni=<? echo $fila2[0]?>&MinIni=<? echo $fila2[1]?>&TimConsulta=<? echo $fila2[12]?>&Horario=<? echo $fila[4]?>&Tiempo=<? echo $tim?>&HrFin=<? echo $fila[2]?>&MinFin=<? echo $fila[3]?>&CedRev=<? echo $fila2[4]?>&Activa=1&CupBloq=<? echo $fila[5]?>'">-->
																								<tr onMouseOver="this.bgColor='#AAD4FF'" style="cursor:hand; background-color: '#FFFF99';" onMouseOut="this.bgColor=''" align="center" title="Abrir Historia Clinica" onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila2[4]?>&Buscar=1'">
													<?php	}
														else{?>
																								<!--<tr title="Cambiar Estado" style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center" onClick="parent.location.href='NewEstadoAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $fila2[14]?>&Especialidad=<? echo $Especialidad?>&Fecha=<? echo $Fecha?>&Profecional=<? echo $Profecional?>&HrIni=<? echo $fila2[0]?>&MinIni=<? echo $fila2[1]?>&TimConsulta=<? echo $fila2[12]?>&Horario=<? echo $fila[4]?>&Tiempo=<? echo $tim?>&HrFin=<? echo $fila[2]?>&MinFin=<? echo $fila[3]?>&CupBloq=<? echo $fila[5]?>'">-->
																								<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center" title="Abrir Historia Clinica" onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila2[4]?>&Buscar=1'">
													<?php	}
													}
																				
													if($azul==1){			
														echo "<td>$HI:$cero$MI-$fila2[2]:$cero2$fila2[3]</td><td>$fila2[4]</td><td>$fila2[5] $fila2[6] $fila2[7] $fila2[8]</td><td>$fila2[9]</td><td>$fila5[0]</td>
														<td>".$Cups[$fila2[15]]."</td><td>$Confirm</td><td>$fila2[11]&nbsp;</td></tr>";  							
														$HIAux=$fila2[2];$MIAux=$fila2[3];												
														$iAux=$i+$fila2[12]-$TiempInt;
														//echo "<tr><td>$iAux</td><tr>";
														$azul=0;
													}
													else{                        	
														echo "<td><font color='#0000FF'>$HI:$cero$MI-$fila2[2]:$cero2$fila2[3]</font></td><td><font color='#0000FF'>$fila2[4]</font></td><td><font color='#0000FF'>
														$fila2[5] $fila2[6] $fila2[7] $fila2[8]*</font></td><td><font color='#0000FF'>$fila2[9]</font></td><td><font color='#0000FF'>$fila5[0]</font></td>
														<td color='#0000FF'>".$Cups[$fila2[15]]."</td><td><font color='#0000FF'>$Confirm</font></td>
														<td><font color='#0000FF'>$fila2[11]</font>&nbsp;</td></tr>"; 
													}
												}									
											}
											else
											{
												$consBlock = "select * from salud.bloqconsexterna where compania_bloqconsexterna='$Compania[0]' and medico_bloqconsexterna='$Profecional' and fechaini_bloqconsexterna<='$Fecha $HI:$cero$MI:00' and fechafin_bloqconsexterna>='$Fecha $HI:$cero$MI:00'";
																		//$consBlock = "select * from salud.bloqconsexterna where compania_bloqconsexterna='$Compania[0]' and medico_bloqconsexterna='$Profecional'";
																		$resBlock = ExQuery($consBlock);
																		$filaBlock = ExFetchAssoc($resBlock);
																		
																		if(!(ExNumRows($resBlock)>0)){
																			//echo "$Fecha $HI:$cero$MI:00"." : ".$feca."<br>";
																			if($MF<$TiempInt){$cero2='0';}else{$cero2='';}?>
																			<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
																		<?php
																			echo "<td>$HI:$cero$MI-$HF:$cero2$MF</td><td colspan='7' align='center'> - Sin Asignar - </td></td></tr>";	
																		}
																		else{
																			if($MF<$TiempInt){$cero2='0';}else{$cero2='';}?>	
																			<tr title="No se puede asignar" style="cursor:hand" onMouseOver="this.bgColor='#AA3333'" onMouseOut="this.bgColor=''" align="center">
																		<?php
																			echo "<td>$HI:$cero$MI-$HF:$cero2$MF</td><td colspan='7' align='center'> - ".$filaBlock['motivo_bloqconsexterna']." - </td></td></tr>";	
																		}
																	}	
											//if($HIAux!=''){$HI=$HIAux;$MI=$MIAux;}					
											if($HIAux!=''){
												$HI=$HIAux; $HIAux='';
												$MI=$MIAux; $MIAux='';
												$i=$iAux;	$iAux='';	
												$HF=$HI;
												$MF=$MI;		
											}
											else{	
												$MI=$MI+$TiempInt;
											}				
											if($MI==(60-$TiempInt)){
												$HF++;$MF=0;
											}else{
												if($MI==60){
													$HI++;$MI=0;
												}
												$MF=$MF+$TiempInt;
											}
											//echo "HI=$HI,MI=$MI";
										}
									} 
										
							//-----------------------------------------------------------------------------Citas Canceladas-----------------------------------------------------------------------------
									/*$cons4="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons,cup,usuconfirma
									from central.terceros,salud.agenda where
									terceros.identificacion=agenda.cedula and medico='$Profecional' and fecha='$Fecha' and (estado='Cancelada') and agenda.compania='$Compania[0]' 
									and terceros.compania='$Compania[0]'";*/
									$cons4="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons,cup,usuconfirma
									from central.terceros,salud.agenda where
									terceros.identificacion=agenda.cedula and medico='$Profecional' and fecha='$Fecha' and (estado='Cancelada') and agenda.compania='$Compania[0]' 
									and terceros.compania='$Compania[0]' order by hrsini";
									$res4=ExQuery($cons4);echo ExError();
									if(ExNumRows($res4)>0){?> 		
										<tr>
											<td  class="encabezado2Horizontal" colspan="8">CITAS CANCELADAS</td>    
										</tr>
										<tr>
											<td class="encabezado2HorizontalInvertido">HORA</td>
											<td class="encabezado2HorizontalInvertido">IDENTIFICACI&Oacute;N</td>
											<td class="encabezado2HorizontalInvertido">NOMBRE</td>
											<td class="encabezado2HorizontalInvertido">TEL&Eacute;FONO</td>
											<td class="encabezado2HorizontalInvertido">ENTIDAD</td>
											<td class="encabezado2HorizontalInvertido">TIPO CONSULTA</td>
											<td class="encabezado2HorizontalInvertido">CONFIRMADA</td>
											<td class="encabezado2HorizontalInvertido">ESTADO</td>        
										</tr>
								 <?php 	while($fila4 = ExFetchArray($res4)){ 
											if($fila4[14]){$Confirm="Si";}else{$Confirm="No";}
											$cons6="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros 
											where  identificacion='$fila4[10]' and Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
											$res6=ExQuery($cons6);echo ExError();//consulta de la agenda
											$fila6=ExFetchArray($res6);
											if($fila4[3]==0){$cero5='0';}else{$cero5='';}
											if($fila4[1]==0){$cero4='0';}else{$cero4='';}?>  
											<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
									<?php  	echo "<td>$fila4[0]:$fila4[1]$cero4-$fila4[2]:$fila4[3]$cero5</td><td>$fila4[4]</td><td>$fila4[5] $fila4[6] $fila4[7] $fila4[8]</td><td>$fila4[9]</td>
											<td>$fila6[0]</td><td>".$Cups[$fila4[13]]."</td><td>$Confirm</td><td>$fila4[11]</td></tr>";        
										}		
									}
								//cierra if de disponibilidad para el dia seleccionado
								}
								else{?>
									<tr>
										<td class="encabezado2Horizontal"> <? echo "$F[0]-$Especialidad ";?></td>
									</tr>
									<tr>
										<td class="encabezadoGrisaceo" style="text-align:center;" > <? echo "$Fecha";?></td>
									</tr>
									<tr>
										<td class="mensaje1">No existe disponibilidad para este dia</td>
									</tr>
						<?		}	
							}
						}?>
						</table>
				</form>
			</div>	
		</body>
	</html>
