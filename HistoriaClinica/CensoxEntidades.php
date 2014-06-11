		<?
			if($DatNameSID){session_name("$DatNameSID");}
			else{$Compania[0]='Clinica San Juan de Dios';}
			session_start();
			include("../Funciones.php");
			include_once("../General/Configuracion/Configuracion.php");
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
				
				<script type="text/JavaScript">
				<!--
				function Valida()
				{
				document.FORMA.FechaIni.value=''+document.FORMA.fi1.value+'-'+document.FORMA.fi2.value+'-'+document.FORMA.fi3.value+'';
				document.FORMA.FechaFin.value=''+document.FORMA.ff1.value+'-'+document.FORMA.ff2.value+'-'+document.FORMA.ff3.value+'';
				//alert('Fecha1: '+document.FORMA.FechaIni.value+' Fecha2: '+document.FORMA.FechaFin.value+'');
				if(document.FORMA.FechaIni.value=="")
					{
						alert("Debes seleccionar la fecha inicial.");
					}
				else{
						if(document.FORMA.FechaFin.value=="")
							{
								alert("Debes seleccionar la fecha final.");
							}
						else{
								if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value)
									{
										alert("La fecha inicial debe ser menor a la fecha final.");
									}
								else{
										location.href='?DatNameSID=<? echo $DatNameSID?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&ambito='+document.FORMA.ambito.value+'&entidad='+document.FORMA.entidad.value+'&int_fechas='+document.FORMA.int_fechas.status+'';
									}
							}
					}
				}
				//-->
				</script>
			</head>
		<?php
				
				$entidad=$_GET['entidad'];
				$qe="where entidad='$entidad'";
				if($entidad=="TODAS"){$qe="";}
			
				$ambito=$_GET['ambito'];
				$qa="ambito='$ambito'";
				if(($ambito==NULL)||($ambito=="0")){$qa="ambito is not null";}
				
				$FechaIni=$_GET['FechaIni'];
				$FechaFin=$_GET['FechaFin'];
						
				if($FechaIni==NULL){
					if($ND[mon]<10){$C1="0";}else{$C1="";}
					if($ND[mday]<10){$C2="0";}else{$C2="";}			
					$FechaIni="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
				}
				
				if($FechaFin==NULL){
					if($ND[mon]<10){$C1="0";}else{$C1="";}
					if($ND[mday]<10){$C2="0";}else{$C2="";}
					$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
				}
				
				$aaaa1=substr("$FechaIni", -10,4);
				$mm1=substr("$FechaIni", -5,2);
				if($mm1<10){$mm1=str_replace("0","","$mm1");}					
				$dd1=substr("$FechaIni", -2);
				if($dd1<10){$dd1=str_replace("0","","$dd1");}
				//echo "$aaaa1,$mm1,$dd1";
				
				$aaaa2=substr("$FechaFin", -10,4);
				$mm2=substr("$FechaFin", -5,2);
				if($mm2<10){$mm2=str_replace("0","","$mm2");}					
				$dd2=substr("$FechaFin", -2);
				if($dd2<10){$dd2=str_replace("0","","$dd2");}
				//echo "$aaaa2,$mm2,$dd2";	
				
				$qf="and estado='AC'";
				$int_fechas=$_GET['int_fechas'];
				if($int_fechas=="true"){
					$qf="and fechaing between '$FechaIni' and '$FechaFin'";
				}
				
				
		?>
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "UTILIDADES";
				$rutaarchivo[2] = "CENSO POR ENTIDADES";
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
						
			<div <?php echo $alignDiv2Mentor; ?> class="div2">	
				<form id="FORMA" name="FORMA" method="post" action="">			  
					
							<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
									<tr>
									  <td class="encabezado2Horizontal" >CENSO POR ENTIDADES    </td>
									</tr>									
									<tr>
									  <td class="encabezadoGrisaceo" style="font-size:12px;padding-left:10px;" >ENTIDAD</td>
									</tr>
									<tr>
									  <td>									  
											<select name="entidad" id="entidad">
												<option value="TODAS">TODAS</option>
													<?php
																	
												  $consE="select primape,identificacion from central.terceros where tipo='Asegurador' order by primape asc";
												  $resE=ExQuery($consE);
												  while($filaE=ExFetch($resE)){
												   ?>
													  <option value="<? echo"$filaE[1]";?>"<? if($filaE[1]==$entidad){echo" selected";}?>><? echo"$filaE[0]";?></option>
														<? } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="encabezadoGrisaceo" style="font-size:12px;padding-left:10px;">FECHA DE INGRESO <input name="int_fechas" type="checkbox" id="int_fechas" value="1" <?php if($int_fechas=="true"){echo'checked="checked"';} ?>/> </td>
									</tr>									
									<tr>
										<td style="text-align:center;">
											<table cellpadding="3" cellspacing="0" border="0">
												<tr>
													<td style="font-size:12px;font-weight:bold;">Desde </td>
													<td>
														<select name="fi1" id="fi1">
															<?php
																for($i=2010;$i<=2020;$i++){
																	echo'<option value="'.$i.'"';
																		if($aaaa1==$i)	{
																				echo'selected="selected"';
																			}
																		echo'>'.$i.'</option>';
																}
															?>
														</select>
													</td>
													<td>		
														<select name="fi2" id="fi2">
															<option value="01"<?php if("$mm1"=='01'){echo'selected="selected"';}?>>Enero</option>
															<option value="02"<?php if("$mm1"=='02'){echo'selected="selected"';}?>>Febrero</option>
															<option value="03"<?php if("$mm1"=='03'){echo'selected="selected"';}?>>Marzo</option>
															<option value="04"<?php if("$mm1"=='04'){echo'selected="selected"';}?>>Abril</option>
															<option value="05"<?php if("$mm1"=='05'){echo'selected="selected"';}?>>Mayo</option>
															<option value="06"<?php if("$mm1"=='06'){echo'selected="selected"';}?>>Junio</option>
															<option value="07"<?php if("$mm1"=='07'){echo'selected="selected"';}?>>Julio</option>
															<option value="08"<?php if("$mm1"=='08'){echo'selected="selected"';}?>>Agosto</option>
															<option value="09"<?php if("$mm1"=='09'){echo'selected="selected"';}?>>Septiembre</option>
															<option value="10"<?php if("$mm1"=='10'){echo'selected="selected"';}?>>Octubre</option>
															<option value="11"<?php if("$mm1"=='11'){echo'selected="selected"';}?>>Noviembre</option>
															<option value="12"<?php if("$mm1"=='12'){echo'selected="selected"';}?>>Diciembre</option>
														</select>
													</td>
													<td>
															<select name="fi3" id="fi3">
																<?php
																for($i=1;$i<=31;$i++){
																	if($i<10){$cnb="0";}else{$cnb="";}
																	echo'<option value="'.$cnb.$i.'"';
																	if($dd1==$i)
																		{
																			echo'selected="selected"';
																		}
																	echo'>'.$i.'</option>';
																}
																?>
															</select>
															<input name="FechaIni" type="hidden" id="FechaIni" />
													</td>		
												</tr>
										
												<tr>
													<td style="font-size:12px;font-weight:bold;">Hasta</td>
													<td>
														<select name="ff1" id="ff1">
															<?php
																for($i=2010;$i<=2020;$i++)	{
																		echo'<option value="'.$i.'"';
																		if($aaaa2==$i)
																			{
																				echo'selected="selected"';
																			}
																		echo'>'.$i.'</option>';
																}
															?>
														</select>
													</td>
													<td>
														  <select name="ff2" id="ff2">
															<option value="01"<?php if("$mm2"=='01'){echo'selected="selected"';}?>>Enero</option>
															<option value="02"<?php if("$mm2"=='02'){echo'selected="selected"';}?>>Febrero</option>
															<option value="03"<?php if("$mm2"=='03'){echo'selected="selected"';}?>>Marzo</option>
															<option value="04"<?php if("$mm2"=='04'){echo'selected="selected"';}?>>Abril</option>
															<option value="05"<?php if("$mm2"=='05'){echo'selected="selected"';}?>>Mayo</option>
															<option value="06"<?php if("$mm2"=='06'){echo'selected="selected"';}?>>Junio</option>
															<option value="07"<?php if("$mm2"=='07'){echo'selected="selected"';}?>>Julio</option>
															<option value="08"<?php if("$mm2"=='08'){echo'selected="selected"';}?>>Agosto</option>
															<option value="09"<?php if("$mm2"=='09'){echo'selected="selected"';}?>>Septiembre</option>
															<option value="10"<?php if("$mm2"=='10'){echo'selected="selected"';}?>>Octubre</option>
															<option value="11"<?php if("$mm2"=='11'){echo'selected="selected"';}?>>Noviembre</option>
															<option value="12"<?php if("$mm2"=='12'){echo'selected="selected"';}?>>Diciembre</option>
														  </select>
													</td>
													<td>		
														<select name="ff3" id="ff3">
															<?php
																for($i=1;$i<=31;$i++){
																	if($i<10){$cnb="0";}else{$cnb="";}
																	echo'<option value="'.$cnb.$i.'"';
																	if($dd2==$i)
																		{
																			echo'selected="selected"';
																		}
																	echo'>'.$i.'</option>';
																}
															?>
														</select>
														<input name="FechaFin" type="hidden" id="FechaFin" />
													</td>
												</tr>
											</table>	
										</td>
									</tr>									
									
									<tr>
										<td class="encabezadoGrisaceo" style="font-size:12px;padding-left:10px;">PROCESO</td>
									</tr>
									<tr>
										<td style="text-align:center;">										
											<select name="ambito" id="ambito">
												<option value="0">TODOS</option>
													<?php 
														$consa="select ambito from salud.ambitos order by ambito";
														$resa=ExQuery($consa);
														while($filaa=ExFetch($resa)){ 
															?>
															<option value="<? echo"$filaa[0]"; ?>"<?php if($filaa[0]==$ambito){echo' selected="selected"';}; ?>><? echo"$filaa[0]"; ?></option>
															<?php 
														}
													?>
											</select>
										</td>
									</tr>
									
									<tr>
										<td style="text-align:center;">										
											<input name="Enviar" type="button" id="Enviar" onclick="Valida()" value="Consultar" class="boton2Envio"/>
										</td>
									</tr>	
									
							</table>
						  
							<table class="tabla2" style="margin-top:25px;margin-bottom:25px;font-size:11px;"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							  <tr>
								<td class="encabezado2Horizontal" style="font-size:11px;">NO.</td>
								<td class="encabezado2Horizontal" style="font-size:11px;">C&Egrave;DULA</td>
								<td class="encabezado2Horizontal" style="font-size:11px;">NOMBRE</td>
								<td class="encabezado2Horizontal" style="font-size:11px;">FECHA INGRESO </td>
								<td class="encabezado2Horizontal" style="font-size:11px;">FECHA EGRESO </td>
								<td class="encabezado2Horizontal" style="font-size:11px;">TIPO USUARIO</td>
								<td class="encabezado2Horizontal" style="font-size:11px;">NIVEL USUARIO </td>
								<td class="encabezado2Horizontal" style="font-size:11px;">AUTORIZACI&Oacute;N</td>
								<td class="encabezado2Horizontal" style="font-size:11px;">NO. CARNET </td>
								<td class="encabezado2Horizontal" style="font-size:11px;">M&Eacute;DICO TRATANTE </td>
								<td class="encabezado2Horizontal" style="font-size:11px;">ORDEN SALIDA </td>
								<td class="encabezado2Horizontal" style="font-size:11px;">QUIEN REALIZA LA SALIDA </td>
								<td class="encabezado2Horizontal" style="font-size:11px;">DIAGN&Oacute;STICO</td>
								<td class="encabezado2Horizontal" style="font-size:11px;">D&Iacute;AS ESTANCIA </td>
							  </tr>
									<?php
										$ref=0;
										$i=1;

										$cons2="select a.cedula, a.fechaing, a.fechaegr, a.tipousu,  a.nivelusu,  a.autorizac1, a.nocarnet, a.medicotte, a.ordensalida,	a.usuordensalida, a.dxserv,	  a.diasestancia,
										  b.primnom,  b.segnom, b.primape,  b.segape, c.nombre, a.usuegreso from salud.servicios as a,  central.terceros as b, central.usuarios as c  where a.tiposervicio='$ambito'  
										  and b.identificacion = a.cedula  and c.usuario = a.medicotte $qf ";
							 
										$res2=ExQuery($cons2);
								
										while($fila2=ExFetch($res2)){							
											?>
											<tr>
												<td><?php echo"$i";if($i==null){echo"-";}?></td>
												<td ><?php echo"$fila2[0]";if($fila2[0]==null){echo"-";}?></td>
												<td><?php echo"$fila2[13] $fila2[14] $fila2[15] $fila2[16]";if($fila2[14]==null){echo"-";}?></td>
												<td><?php echo"$fila2[1]";if($i==null){echo"-";}?></td>
												<td><?php echo"$fila2[2]";if($fila2[2]==null){echo"-";}?></td>
												<td><?php echo"$fila2[3]";if($fila2[3]==null){echo"-";}?></td>
												<td><?php echo"$fila2[4]";if($fila2[4]==null){echo"-";}?></td>
												<td><?php echo"$fila2[17]";if($fila2[17]==null){echo"-";}?></td>
												<td><?php echo"$fila2[6]";if($fila2[6]==null){echo"-";}?></td>
												<td ><?php echo"$fila2[7]";if($fila2[7]==null){echo"-";}?></td>
												<td><?php echo"$fila2[8]";if($fila2[8]==null){echo"-";}?></td>
												<td><?php echo"$fila2[9]";if($fila2[9]==null){echo"-";}?></td>
												<td width="300"><?php echo"$fila2[10]";if($fila2[10]==null){echo"-";}?></td>
												<td><?php echo"$fila2[11]";if($fila2[11]==null){echo"-";}?></td>
											</tr>
											<?
											$i++; 
										}

									?>
							</table>			
					</div>
				</form>				
		</body>
	</html>