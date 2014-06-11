		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
			
			if($Profecional==""){
				$Profecional = $usuario[1];
			}
				
			if($Especialidad==""){
				$consesp = "SELECT especialidad FROM salud.medicos WHERE usuario='$Profecional'";
				$resesp = ExQuery($consesp);
				$filaesp = ExFetchAssoc($resesp);
				$Especialidad = $filaesp['especialidad'];
			}
			
			function DispoDia($Med,$F){
				global $Compania;
				$ban=0;
				$cons="select horaini,minsinicio,horasfin,minsfin,idhorario from salud.dispoconsexterna
				where usuario='$Med' and fecha='$F' and compania='$Compania[0]' order by idhorario";
				$res=ExQuery($cons);
				$tim=0;		
				while($fila=ExFetch($res)){
					$tim=$tim+(((($fila[2]-$fila[0])*60)-$fila[1])+$fila[3]);		
					$ban=1;	
					//echo $tim;
				}
				$cons="select hrsini,minsini,hrsfin,minsfin from salud.agenda where medico='$Med' and fecha='$F' and compania='$Compania[0]' and estado!='Cancelada'";
				$res=ExQuery($cons);		
				while($fila=ExFetch($res)){
					$tim=$tim-(((($fila[2]-$fila[0])*60)-$fila[1])+$fila[3]);	
					//echo "tim=$tim";					
				}
				if($ban==1){			
					if($tim==0){
						return 1;
					}
					else{
						return 2;
					}
				}
				else{
					return 0;
				}		
			}	
			$ND=getdate();
			if(!$MesCalend)
			{
				$MesCalend=$ND[mon];	
			}
			/*$cons="select numdias from central.meses where numero='$MesCalend'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$totaldias=$fila[0];*/
			if(!$AnioCalend)
			{
				$AnioCalend=$ND[year];
			}
			$first_of_month = mktime (0,0,0, $MesCalend, 1, $AnioCalend); 
			$totaldias = date('t', $first_of_month); 
			
			//echo "totaldias=$totaldias,totdias=$totdias";
			if(!$DiaCalend)
			{
				$DiaCalend=$ND[mday];
			}	
			else{
				$first_of_month = mktime (0,0,0, $MesCalend, 1, $AnioCalend); 
				$LastDay = date('t', $first_of_month); 
				if($DiaCalend>$LastDay){
					$DiaCalend=$LastDay;
				}
			}	
			$Fecha="$AnioCalend-$MesCalend-$DiaCalend";	
		?>
		
		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
				<script language='javascript' src="/calendario/popcalendar.js"></script>
				<script language='javascript' src="/Funciones.js"></script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "CONSULTA EXTERNA";
					$rutaarchivo[2] = "VER AGENDA";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
					<table align="center">
						<tr>
							<td>
							<table class="tabla1"    <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>> 
								<tr>
									<td class="encabezado2Horizontal" colspan="2"> AGENDA PROFESIONALES</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">ESPECIALIDAD</td>
									<td>
										<?php
											$cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
											$res=ExQuery($cons);
										?>
										<select name="Especialidad" onChange="document.FORMA.submit()">
											<option></option>
											<?php while($fila=ExFetch($res))
														{
																if($fila[0]==$Especialidad){?>
																		<option value="<? echo $fila[0]?>" selected><? echo $fila[0]?></option>
														<?php	}
																else{?>
																		<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
														<?php	}				
														}
												?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">PROFESIONAL</td>
									<td>
										<?php
											$res=ExQuery("Select nombre,Medicos.usuario as usu from Salud.Medicos,central.usuarios 
													where Especialidad='$Especialidad' and Medicos.usuario=usuarios.usuario and Compania='$Compania[0]' and estadomed='Activo'
													order by usu");?>
										<select name="Profecional" onChange="document.FORMA.submit()">
											<option></option>
										<?php
											if($Especialidad!=''){
												while($fila=ExFetch($res)){
													if($fila[1]==$Profecional){
										?>
														<option value="<? echo $fila[1]?>" selected><? echo $fila[1]?></option>
										<?php
													}else{
										?>
														<option value="<? echo $fila[1]?>"><? echo $fila[1]?></option>
									<?php
													}
												}
									}
										?>
										</select>
								   </td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido"> A&Ntilde;O</td>
									<td>
									<?php $cons="select anio from central.anios where compania='$Compania[0]'";
									$res=ExQuery($cons);?>
									<select name="AnioCalend" onChange="document.FORMA.submit()">
									<?php 	while($fila=ExFetch($res)){
											if($AnioCalend==$fila[0]){?>
												<option value="<? echo $fila[0]?>" selected><? echo $fila[0]?></option>
										<?php	}
											else{?>
												<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
										<?php	}
									}?>	
									</select>        
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">MES</td>
									<td>
									<?php $cons="select numero,mes from central.meses";
									$res=ExQuery($cons);?>
									<select name="MesCalend" onChange="document.FORMA.submit()">
									<?php 	while($fila=ExFetch($res)){
											if($MesCalend==$fila[0]){?>
												<option value="<? echo $fila[0]?>" selected><? echo $fila[1]?></option>
										<?php	}
											else{?>
												<option value="<? echo $fila[0]?>"><? echo $fila[1]?></option>
										<?php	}
									}?>
									</select>
									</td>
								</tr>
							</table>
						</td>
					<td>
							<table class="tabla1" cellpadding="4" cellspacing="0"   <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; ?>>
								<tr>
									<td class="encabezado1Horizontal" >D</td>
									<td class="encabezado1Horizontal">L</td>
									<td class="encabezado1Horizontal">M</td>
									<td class="encabezado1Horizontal">M</td>
									<td class="encabezado1Horizontal">J</td>
									<td class="encabezado1Horizontal">V</td>
									<td class="encabezado1Horizontal">S</td>
								</tr>
								<tr>
									<?php
									$nd=0;
									$fec = $MesCalend . '/01/' . $AnioCalend;
									$hora = getdate(strtotime($fec));	
									for($i=0;$i<=$hora[wday]-1;$i++)
									{
										echo "<td>.</td>";
									}
									
									for($dia=1;$dia<=7-$i;$dia++)
									{	
										$FecCal="$AnioCalend-$MesCalend-$dia";					
										$D=DispoDia($Profecional,$FecCal);
										if($D==0){
											$Color='000000';
										}
										else{
											if($D==1){$Color='FF0000';}else{$Color='66CC00';}
										}
										if($DiaCalend==$dia){$BgColor="#CAD0D0";}else{$BgColor="white";}?>
										<td style="cursor:hand" onclick='document.FORMA.DiaCalend.value=<? echo $dia?>;document.FORMA.submit()' bgcolor='<? echo $BgColor?>' onMouseOver="this.bgColor='#AAD4FF'" 
										onMouseOut="this.bgColor='<? echo $BgColor?>'" ><div align='right'><font color='<? echo $Color?>'><? echo $dia?></div></td>
								<?php	}
									$nd=$dia-1;
								echo "</tr>";
									while($nd<=$totaldias)
									{
										echo "<tr>";	
										for($n=0;$n<=6;$n++)
										{
											$nd=$nd+1;
											if($nd>$totaldias)
											{
												echo "<td>.</td>";
											}
											else
											{
												$FecCal="$AnioCalend-$MesCalend-$nd";				
												$D=DispoDia($Profecional,$FecCal);				
												if($D==0){
													$Color='000000'; $Astr="";$Title="";
												}
												else{
													if($D==1){
																			$Color='FF0000';$Astr="*"; $Title="Cupo de Agenda Completo";
																		}
																		else{
																			$Color='66CC00';$Astr="";$Title="";
																			
																			$consBlock = "select * from salud.bloqconsexterna where compania_bloqconsexterna='$Compania[0]' and medico_bloqconsexterna='$Profecional' and date_trunc('day', fechaini_bloqconsexterna)<='$FecCal' and date_trunc('day', fechafin_bloqconsexterna)>='$FecCal'";
																			$resBlock = ExQuery($consBlock);

																			if(ExNumRows($resBlock)>0){
																				$Color="CC6666";
																				$Title="Dia Bloqueado por el administrador";
																			}
																		}
												}
																
												$consBlock="select motivo from salud.bloqueoxdia where compania='$Compania[0]' and dia='$FecCal'";
												$resBlock=ExQuery($consBlock);
												if(ExNumRows($resBlock)>0){
																	$Color="FF0000";
																	$Title="Dia Bloqueado por el administrador";
																}elseif($Title!="Cupo de Agenda Completo"){
																	$Title="";    
																}
																
																/*$consBlock = "select * from salud.bloqconsexterna where compania_bloqconsexterna='$Compania[0]' and medico_bloqconsexterna='$Profecional' and date_trunc('day', fechaini_bloqconsexterna)<='$FecCal' and date_trunc('day', fechafin_bloqconsexterna)>='$FecCal'";
												$resBlock = ExQuery($consBlock);
																
																if(ExNumRows($resBlock)>0){
																	$Color="CC6666";
																	$Title="Dia Bloqueado por el administrador";
																}*/
																
																/*while($filaBlock = ExFetchAssoc($resBlock)){
																	$diab = date("Y-n-d", strtotime($filaBlock['fechaini_bloqconsexterna']));
																	//echo $FecCal;
																	if($FecCal==$diab){
																		$Color="CC6666";
																		$Title="Dia Bloqueado por el administrador";
																	}
																}*/
																
												if($n==0)
												{				
													//echo $FecCal;					
													$D=DispoDia($Profecional,$FecCal);	?>
													<td style="cursor:hand" title="<? echo $Title?>" onclick='document.FORMA.DiaCalend.value=<? echo $nd?>;document.FORMA.submit()' onMouseOver="this.bgColor='#AAD4FF'" 
										onMouseOut="this.bgColor='<? echo $BgColor?>'" valign='top' align='right'><strong><font color='<? echo $Color ?>'><? echo $nd.$Astr?></strong></td><?php  //Este es el dia actual
												}
												else
												{
													if($DiaCalend==$nd)
													{				
														$BgColor="#CAD0D0";				
													}
													else
													{
														$BgColor="white";
													}
												?>				
													<td style="cursor:hand" title="<? echo $Title?>" onclick='document.FORMA.DiaCalend.value=<? echo $nd?>;document.FORMA.submit()' bgcolor='<? echo $BgColor?>' onMouseOver="this.bgColor='#AAD4FF'" 
										onMouseOut="this.bgColor='<? echo $BgColor?>'" valign='top' align='right'><div align='right'><font color='<? echo $Color?>'><? echo $nd.$Astr?></div></td>
											<?php	}
											}
										}
										echo "</tr>";
									}
								?>
								</table>
							</td>
						</tr>
					</table>
					<input type="hidden" name="Ver" value="">
					<input type="hidden" name="DiaCalend" value="<? echo $DiaCalend?>">
					<input type="hidden" name="Fecha" value="<? echo $Fecha?>">
				</form>
				
					<iframe frameborder="0" id="VerConfAgendMed" src="VerConfAgendMed.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $Especialidad?>&Fecha=<? echo $Fecha?>&Profecional=<? echo $Profecional?>" width="100%" height="85%"></iframe>
				
				<?php
					if($Ver)
					{
						?><script language="javascript">
							frames.VerConfAgendMed.location.href="VerConfAgendMed.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $Especialidad?>&Fecha=<? echo $Fecha?>&Profecional=<? echo $Profecional?>";
						</script><?php
					}
				?>
		</body>
		</html>
