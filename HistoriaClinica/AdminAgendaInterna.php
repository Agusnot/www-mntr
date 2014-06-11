		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Guardar){
				$cons="select agendainterna.cedula,servicios.numservicio 
				from salud.agendainterna,salud.servicios 
				where agendainterna.compania='$Compania[0]' and profecional='$Profecional' and estado='AC' and agendainterna.numservicio=servicios.numservicio 
				and servicios.compania='$Compania[0]'";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($FecUlt[$fila[0]])
					{
						$cons2="update salud.agendainterna set fecproxima='".$FecUlt[$fila[0]]."' where agendainterna.compania='$Compania[0]' 
						and profecional='$Profecional' and agendainterna.numservicio=$fila[1]";
						$res2=ExQuery($cons2);
						//echo $cons2;
					}
				}
			}
			if($MedicoNuevo)
			{    
				if($FechaUlitima){$FecU="and fecultima='$FechaUlitima'";}
				$cons="update salud.agendainterna set profecional='$MedicoNuevo' where profecional='$Profecional' and cedula='$PacAgenda' and numservicio=$NumServ 
				and especialidad='$Especialidad'  and fecproxima='$FechaProx' $FecU";	
				//echo $cons;
				$res=ExQuery($cons);
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
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
				$rutaarchivo[2] = "ADMINISTRACI&Oacute;N AGENDA INTERNA";
				mostrarRutaNavegacionEstatica($rutaarchivo);
					
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
						<tr>
							<td colspan="6" class="encabezado2Horizontal">ADMINISTRAR AGENDA INTERNA</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">ESPECIALIDAD</td>
							<td>
								<select name="Especialidad" onChange="document.FORMA.submit()"><option></option>
									<? $cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
									$res=ExQuery($cons);
								//echo $cons;
									while($fila=ExFetch($res)){	
										$cargos[$fila[0]]=$fila[1];
										if($fila[0]==$Especialidad){					
											echo "<option value='$fila[0]' selected>$fila[0]</option>";
										}
										else{
											echo "<option value='$fila[0]'>$fila[0]</option>";
										}
									}?>	
								</select>
							</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">PROFESIONAL</td>
							<td>
								<select name="Profecional" onChange="document.FORMA.submit()"><option></option>
									<?	$cons="select nombre,medicos.usuario from salud.medicos,central.usuarios where compania='$Compania[0]' 
									and usuarios.usuario=medicos.usuario and especialidad='$Especialidad' order by nombre";
									$res=ExQuery($cons);
									//echo $cons;
									while($fila=ExFetch($res)){	
										if($fila[1]==$Profecional){					
											echo "<option value='$fila[1]' selected>$fila[0]</option>";
										}
										else{
											echo "<option value='$fila[1]'>$fila[0]</option>";
										}
									}?>	
								</select>
							</td>
						</tr>
					</table>
					<?
					if($Especialidad&&$Profecional)	{	
						$cons2="select nombre,medicos.usuario from salud.medicos,central.usuarios where compania='$Compania[0]' 
						and usuarios.usuario=medicos.usuario and especialidad='$Especialidad' and medicos.usuario!='$Profecional' order by nombre";
						$res2=ExQuery($cons2);
						//echo $cons2;
						while($fila2=ExFetch($res2)){	
							$MedsCamb[$fila2[1]]=array($fila2[0],$fila2[1]);
						}
						$cons="select agendainterna.cedula,primape,segape,primnom,segnom,fecultima,fecproxima,servicios.numservicio 
						from salud.agendainterna,salud.servicios,central.terceros 
						where agendainterna.compania='$Compania[0]' and profecional='$Profecional' and terceros.compania='$Compania[0]' and servicios.cedula=identificacion
						and estado='AC' and agendainterna.numservicio=servicios.numservicio and servicios.compania='$Compania[0]'
						order by primape,segape,primnom,segnom";
						$res=ExQuery($cons);
						//echo $cons;
						if(ExNumRows($res)>0){?>
								<br>
								<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
								<tr>
									<td colspan="8" style="text-align:center;">
										<input type="submit" name="Guardar" value="Guardar" class="boton2Envio">
									</td>
								</tr>
								<tr>
									<td class="encabezado2Horizontal">&nbsp;</td>
									<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
									<td class="encabezado2Horizontal">NOMBRE</td>
									<td class="encabezado2Horizontal">&Uacute;LTIMA</td>
									<td class="encabezado2Horizontal">PR&Oacute;XIMA</td>
									<td class="encabezado2Horizontal">CAMBIAR A</td>
									<td class="encabezado2Horizontal">&nbsp;</td>
								</tr>
							<?	$Cont=1;
								while($fila=ExFetch($res))
								{?>
									<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
										<td><? echo $Cont?></td><td><? echo $fila[0]?></td><td><? echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></td><td><? echo $fila[5]?>&nbsp;</td>
										<td><input type="text" name="FecUlt[<? echo $fila[0]?>]"  value="<? echo $fila[6]?>" style="width:80"></td>
										<td><select name="MedCambio_<? echo $fila[0]?>">
												<option></option>
											<?	foreach($MedsCamb as $MC)
												{
													if($MedCambio==$MC){echo "<option value='$MC[1]' selected>$MC[0]</option>";}
													else{echo "<option value='$MC[1]'>$MC[0]</option>";}
												}?>
											</select>
										</td>
										<td>
											<button title="Reasignar Medico" onClick="if(confirm('Esta Seguro de cambiar al paciente con este profesional?')){
											location.href='AdminAgendaInterna.php?DatNameSID=<? echo $DatNameSID?>&FechaUlitima=<? echo $fila[5]?>&FechaProx=<? echo $fila[6]?>&Profecional=<? echo $Profecional?>&Especialidad=<? echo $Especialidad?>&PacAgenda=<? echo $fila[0]?>&NumServ=<? echo $fila[7]?>&MedicoNuevo='+document.FORMA.MedCambio_<? echo $fila[0]?>.value;}"><img src="/Imgs/b_check.png"></button>
										</td>
									</tr>
									<? $Cont++;
							}?>
							<tr>
								<td colspan="5" style="text-align:center">
									<input type="submit" name="Guardar" class="boton2Envio" value="Guardar">
								</td>
							</tr>
							</table>
						<?	}
						}
						?>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<input type="hidden" name="MedicoNuevo" value="">
				</form>
			</div>	
		</body>
	</html>