		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");			
			$ND = getdate();
			if($Guardar)
			{
				if($Metodo1){$M1="Si";}else{$M1="No";}
				if($Metodo2){$M2="Si";}else{$M2="No";}
				if($Metodo3){$M3="Si";}else{$M3="No";}
				if($FecSuce){$FecS1=",FechaSuceso ";$FecS2=",'$FecSuce'";}
				$cons="insert into pacienteseguro.regprotocolo (IdSuceso,Compania,Reporta,Cargo,Equipo,Procedimientos,Amblabsocial,Colaborador,Paciente,Analisis
				,Causas,Resultado,HoraSuceso,MinsSuceso,BarrerasDefensa,DescripBarrerasDefs,AccionesInseguras,OtrasAccInsegs,TipoEvento,DescripEventoAdverso
				,OrigenCotributivo,FactorContributivo,OtrosFactsContribs,DescripAccsInsegs,DescripsFallasLatentes,OrgyCultura,Espcialidad,Ambito,Pabellon,Conclusion
				,Metodo1,Metodo2,Metodo3,Prevenible,usucrea,fechacrea $FecS1) values ($IdSuceso,'$Compania[0]','$NomReporta','$CargoReporta','$Equipo','$Procedimeintos'
				,'$AmbLaboralSocial','$Colaborador','$Pac','$Analisis','$Causas','$Resultado','$HoraSuceso','$MinSuceso','$BarrDef','$DescBarrDefs','$AccsInseg'
				,'$OtrasAccsInsegs','$TipoEvento','$DescEventAdv','$OrgContributivo','$FacContributivo','$OtrosFactsContribs','$DescripAccsInsegs','$DescFallasLatentes' 
				,'$OrgyCult','$EspClinica','$Ambito','$Pabellon','$Conclusion','$M1','$M2','$M3','$Prevenible','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] 
				$ND[hours]:$ND[minutes]:$ND[seconds]' $FecS2)";	
				//echo $cons;
				$res=ExQuery($cons);
				if($Resultado=="NoAplica"||$Resultado=="Error"){$Est="AN";}else{$Est="AC";}
				$cons="update pacienteseguro.sucesos set accion='$Resultado',estado='$Est',fechareporte='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
				,usuarioreporte='$usuario[1]' where compania='$Compania[0]' and idsuceso=$IdSuceso";
				$res=ExQuery($cons);?>
				<script language="javascript">
					location.href='RepHallasgos.php?DatNameSID=<? echo $DatNameSID?>';
				</script><?
			}
			$cons="select sucesos.usuario,nombre,fechacrea,sucesos.cedula,ambito,pabellon from pacienteseguro.sucesos,central.usuarios where compania='$Compania[0]'
			and usuarios.usuario=sucesos.usuario and idsuceso=$IdSuceso";
			$res=ExQuery($cons); $DatosSuceso=ExFetch($res);
			$cons="select cargo from salud.medicos where usuario='$DatosSuceso[0]'"; 
			$res=ExQuery($cons); $Cargo=ExFetch($res);
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
				<script language="javascript">
					function Validar()
					{
						if(document.FORMA.Resultado.value!="NoAplica"&&document.FORMA.Resultado.value!="Error"){
							if(document.FORMA.FecSuce.value==""){alert("Debe seleccionar la fecha del suceso!!!");return false;}	
							if(document.FORMA.Prevenible.value==""){alert("Debe indicar si el evento adeverso era prevenible o no!!!");return false;}
						}
					}
				</script>			
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "PACIENTE SEGURO";
					$rutaarchivo[2] = "REPORTE DE SUCESOS";
					$rutaarchivo[3] = "DILIGENCIAR";
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post" onSubmit="return Validar()">
						<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal" colspan="11">SEGUIMIENTO DEL REPORTE</td></tr>
							<tr>
								<td class="encabezado2VerticalInvertido">REPORTA</td>
								<td>
								<?	if(!$NomReporta&&$DatosSuceso[1]){$NomReporta=$DatosSuceso[1];}?>
									<input type="text" name="NomReporta" value="<? echo $NomReporta?>" style="width:250;">
								</td>
								<td class="encabezado2VerticalInvertido">CARGO</td>
								<td>
								<?	if(!$CargoReporta&&$Cargo[0]){$CargoReporta=$Cargo[0];}?>
									<input type="text" name="CargoReporta" value="<? echo $CargoReporta?>" style="width:210;">
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;">
									<p>EQUIPO<br>(Maquinas,Monitores,Ambulancias,Camillas,Sondas)</p>
								</td>
								 <td colspan="3"><textarea name="Equipo" style="width:100%" rows="2"><? echo $Equipo?></textarea></td>
							</tr>
							<tr>
								 <td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;">
									<p>PROCEDIMIENTOS<br>(Legislaci&oacute;n,Reglamento Interno,Señalizaci&oacute;n ,<br>Protocolos,Guias,Procesos,Historia Cl&iacute;nica)</p>
								 </td>
								 <td colspan="3"><textarea name="Procedimeintos" style="width:100%" rows="2"><? echo $Procedimeintos?></textarea></td>
							</tr>
							<tr>
								 <td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;">
									<p>AMBIENTE LABORAL - SOCIAL <br>(Ambiente Hospitalario,Clima Organizaci&oacute;n ,  <br>Ambiente Politico,Intersecci&oacute;n de Culturas) </p>
								 </td>
								 <td colspan="3"><textarea name="AmbLaboralSocial" style="width:100%" rows="2"><? echo $AmbLaboralSocial?></textarea></td>
							</tr>
							<tr>
								 <td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;">
									<p>COLABORADOR<br>(Medicos,Enfermeras,Trabajadores Sociales, <br>Terapeutas Ocupacionales,Gerente,Administrador,Auditores)</p>
								</td>
								 <td colspan="3"><textarea name="Colaborador" style="width:100%" rows="2"><? echo $Colaborador?></textarea></td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>PACIENTE </p></td>
								<td colspan="3"><input type="text" name="Pac" value="<? echo $Pac?>"></td>
							</tr>    
							<tr>
								 <td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>AN&Aacute;LISIS</p></td>
								 <td colspan="3"><textarea name="Analisis" style="width:100%" rows="2"><? echo $Analisis?></textarea></td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>CAUSAS </p></td>
								<td colspan="3"><textarea name="Causas" style="width:100%" rows="2"><? echo $Causas?></textarea></td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p> RESULTADO </p></td>
								<td colspan="3">
									<select name="Resultado" onChange="document.FORMA.submit()">
										<option></option>
										<option value="EventoAdverso" <? if($Resultado=="EventoAdverso"){?> selected<? }?>>Evento Adverso</option>
										<option value="Incidente" <? if($Resultado=="Incidente"){?> selected<? }?>>Incidente (Accion Insegura)</option>
										<option value="Error" <? if($Resultado=="Error"){?> selected<? }?>>Error Sin Da&ntilde;o</option>
										<option value="NoAplica" <? if($Resultado=="NoAplica"){?> selected<? }?>>No Aplica</option>
									</select>
								</td>
							</tr>
								
						<?	if($Resultado=="EventoAdverso"||$Resultado=="Incidente")
							{?>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>FECHA SUCESO </p></td>
									<td>
										<input type="text" name="FecSuce" value="<? echo $FecSuce?>" readonly onClick="popUpCalendar(this, FORMA.FecSuce, 'yyyy-mm-dd')" style="width:90">
									</td>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>HORA SUCESO</p></td>
									<td>
										<select name="HoraSuceso">
										<?	for($i=0;$i<=23;$i++)
											{
												if($i==$HoraSuceso){	echo "<option value='$i' selected>$Ch$i</option>";}
												else{echo "<option value='$i'>$Ch$i</option>";}
											}?>
										</select>:
										<select name="MinSuceso">
										<?	for($i=0;$i<=59;$i++)
											{
												if($i<10){$Ch="0";}else{$Ch="";}
												if($i==$MinSuceso){	echo "<option value='$i' selected>$Ch$i</option>";}
												else{echo "<option value='$i'>$Ch$i</option>";}
											}?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>BARRERAS Y DEFENSAS</p></td>
								<?	$cons="select barreraodef from pacienteseguro.barrerasydef order by barreraodef";
									$res=ExQuery($cons);?>
									<td colspan="3">
										<select name="BarrDef">
									<?	while($fila=ExFetch($res))
										{
											if($BarrDef==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
										}?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>DESCRIPCI&Oacute;N DE LAS BARRERAS<br> Y/O DEFENSAS QUE FALLARON</p></td>
									<td colspan="3"><textarea name="DescBarrDefs" style="width:100%" rows="2"><? echo $DescBarrDefs?></textarea></td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>ACCIONES INSEGURAS</p></td>
										<?	$cons="select accinsegura from pacienteseguro.accionesinseguras order by accinsegura";
											$res=ExQuery($cons);?>
											<td colspan="3">
												<select name="AccsInseg">
												<option></option>
											<?	while($fila=ExFetch($res))
												{
													if($AccsInseg==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
													else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
												}?>
												</select>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>OTRAS ACCIONES INSEGURAS</p></td>
									<td colspan="3"><textarea name="OtrasAccsInsegs" style="width:100%" rows="4"><? echo $OtrasAccsInsegs?></textarea></td>
								</tr>       
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>TIPO DE EVENTO</p></td>
								<?	$cons="select tipoenvento from pacienteseguro.tiposenventos order by tipoenvento";
									$res=ExQuery($cons);?>
									<td colspan="3">
										<select name="TipoEvento">
										<option></option>
									<?	while($fila=ExFetch($res))
										{
											if($TipoEvento==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
										}?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>DESCRIPCI&Oacute;N EVENTO ADVERSO</p></td>
									<td colspan="3"><textarea name="DescEventAdv" style="width:100%" rows="2"><? echo $DescEventAdv?></textarea></td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>ORIGEN CONTRIBUTIVO</p></td>
								<?	$cons="select orgcontributivo from pacienteseguro.origenescontributivos order by orgcontributivo";
									$res=ExQuery($cons);?>
									<td>
										<select name="OrgContributivo" onChange="document.FORMA.submit()">
										<option></option>
									<?	while($fila=ExFetch($res))
										{
											if($OrgContributivo==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
										}?>
										</select>
									</td>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>FACTOR CONTRIBUTIVO</p></td>
								<?	$cons="select factcontrib from pacienteseguro.factorescontributivos where orgcontrib='$OrgContributivo' order by factcontrib";
									$res=ExQuery($cons);?>
									<td>
										<select name="FacContributivo">
										<option></option>
									<?	while($fila=ExFetch($res))
										{
											if($OrgContributivo==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
										}?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>OTROS FACTORES CONTRIBUTIVO</p></td>
									<td colspan="3"><textarea name="OtrosFactsContribs"style="width:100%" rows="4"><? echo $OtrosFactsContribs?></textarea></td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>DESCRIPCI&Oacute;N DE LAS ACCIONES INSEGURAS<br> Y SU RELACI&Oacute;N CON LOS FACTORES CONTRIBUTIVOS</p></td>
									<td colspan="3"><textarea name="DescripAccsInsegs" style="width:100%" rows="2"><? echo $DescripAccsInsegs?></textarea></td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p> DESCRIPCI&Oacute;N DE LAS FALLAS LATENTES</p></td>
									<td colspan="3"><textarea name="DescFallasLatentes" style="width:100%" rows="2"><? echo $DescFallasLatentes?></textarea></td>
								</tr>
								 <tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>ORGANIZACI&Oacute;N Y CULTURA</p></td>
								<td>
									<select name="OrgyCult">
										<option></option>
										<option value="Decisiones gerenciales" <? if($OrgyCult=="Decisiones gerenciales"){?> selected<? }?>>Decisiones gerenciales</option>
										<option value="Procesos organizacionales" <? if($OrgyCult=="Procesos organizacionales"){?> selected<? }?>>Procesos organizacionales</option>
										<option value="NoAplica" <? if($OrgyCult=="NoAplica"){?> selected<? }?>>No Aplica</option>
									</select>
								</td>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>ESPECIALIDAD (Cl&iacute;nicas</p></td>
							<?	$cons="select clinica from salud.clinicashc where compania='$Compania[0]' order by clinica";
								$res=ExQuery($cons);?>
								<td>
									<select name="EspClinica">
									<option></option>
								<?	while($fila=ExFetch($res))
									{
										if($EspClinica==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
									}?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;">SERVICIO (Ambito)</td>
							<?	if(!$Ambito&&$DatosSuceso[4]){$Ambito=$DatosSuceso[4];}
								$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
								$res=ExQuery($cons);?>
								<td>
									<select name="Ambito">
									<option></option>
								<?	while($fila=ExFetch($res))
									{
										if($Ambito==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
									}?>
									</select>
								</td>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>UNIDAD DE ATENCI&Oacute;N</p></td>
							<?	if(!$Pabellon&&$DatosSuceso[5]){$Pabellon=$DatosSuceso[5];}
								$cons="select pabellon from salud.pabellones where compania='$Compania[0]' order by pabellon";
								$res=ExQuery($cons);?>
								<td>
									<select name="Pabellon">
									<option></option>
								<?	while($fila=ExFetch($res))
									{
										if($Pabellon==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
									}?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>CONCLUSI&Oacute;N</p></td>
								<td colspan="3"><textarea name="Conclusion" style="width:100%" rows="2"><? echo $Conclusion?></textarea></td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>M&Eacute;TODOS UTILIZADOS PARA<br>OBTENER LA INFORMACI&Oacute;N</td>  
								<td colspan="3">
									<table width="100%" cellpadding="2" cellspacing="0" border="0" style="font-size:12px;">
										<tr>
											<td width="55%"> <label for="Metodo1"><span style="color:#002147;font-weight:bold;">1.</span> Analisis de la Historia Clinica, Protocolos, Procedimientos </label></td>
											<td style="text-align:left;"> <input type="checkbox" id="Metodo1" name="Metodo1" <? if($Metodo1){?> checked<? }?>> </td>
										</tr>
										<tr>
											<td width="55%"><label for="Metodo2"> <span style="color:#002147;font-weight:bold;"> 2. </span>Entrevistas a las personas que intervienen en el procesos </label></td>
											<td style="text-align:left;"> <input type="checkbox" id="Metodo2" name="Metodo2" <? if($Metodo2){?> checked<? }?>> </td>
										</tr>
										<tr>
											<td width="55%"><label for="Metodo3"><span style="color:#002147;font-weight:bold;"> 3. </span> Otros mecanismos: Declaraciones, Observaciones, etc. </label></td>
											<td style="text-align:left;"> <input type="checkbox" id="Metodo3" name="Metodo3" <? if($Metodo3){?> checked<? }?>> </td>
										</tr>
									</table>	
									
									
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:10px;padding-bottom:10px;"><p>EVENTO ADVERSO PREVENIBLE </p></td>    
								<td>
									<select name="Prevenible">
										<option></option>
										<option value="No" <? if($Prevenible=="No"){?> selected<? }?>>No</option>
										<option value="Si" <? if($Prevenible=="Si"){?> selected<? }?>>Si</option>
									</select>
								</td>
							</tr>
						<?	}?>    
							<tr align="center">
								<td colspan="11">
									<input type="submit" class="boton2Envio" name="Guardar" value="Guardar"<? if(!$Resultado){?> disabled<? }?>>
									<input type="button" class="boton2Envio" value="Cancelar" onClick="location.href='RepHallasgos.php?DatNameSID=<? echo $DatNameSID?>'">
								</td>
							</tr>
						</table>
						<input type="hidden" value="<? echo $DatNameSID?>" name="DatNameSID">
						<input type="hidden" name="IdSuceso" value="<? echo $IdSuceso?>">
				</form>
			</div>	
		</body>
	</html>