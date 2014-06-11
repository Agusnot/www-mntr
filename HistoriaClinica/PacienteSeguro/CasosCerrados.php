		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();	
		?>	
		
		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
				<script language="javascript" src="/Funciones.js"></script>
				<script language='javascript' src="/calendario/popcalendar.js"></script>
				<script language="javascript">
					function Enviar(e,Num)
					{
						y = e.clientY; 
						st = document.body.scrollTop;
						frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/CerrarCaso.php?DatNameSID=<? echo $DatNameSID?>&NumRep="+Num;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=y-40+st;
						document.getElementById('FrameOpener').style.left='5%';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='950';
						document.getElementById('FrameOpener').style.height='500';
					}
					function VerDatos(e,Num)
					{
						y = e.clientY; 
						x = e.clientX; 
						st = document.body.scrollTop;
						frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/VerFormatosAcciones.php?DatNameSID=<? echo $DatNameSID?>&Caso=3&NumRep="+Num;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=y-10+st;
						document.getElementById('FrameOpener').style.left=x;
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='260';
						document.getElementById('FrameOpener').style.height='165';
					}
				</script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "PACIENTE SEGURO";
				$rutaarchivo[2] = "CASOS CERRADOS";
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
						<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
							<tr>
								<td class="encabezado2Horizontal" colspan="12" >CASOS CERRADOS</td>
							</tr>
							<tr>
								<td class='encabezado2HorizontalInvertido' colspan="2">PERIODO</td>
								<td class='encabezado2HorizontalInvertido'>PROCESO</td>
								<td class='encabezado2HorizontalInvertido'>SERVICIO</td>
								<td class='encabezado2HorizontalInvertido'>TIPO</td>
								<td class='encabezado2HorizontalInvertido'>VER ACCIONES</td>
							</tr>
							<tr style="text-align:center;">
							<?	if(!$FechaIni){$FechaIni="$ND[year]-$ND[mon]-01";}
								if(!$FechaFin){$FechaFin="$ND[year]-$ND[mon]-$ND[mday]";}?>
								<td><strong>De </strong><input type="text" readonly name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" 
									style="width:70px" value="<? echo $FechaIni?>"></td>
								<td><strong>a </strong><input type="text" readonly name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" 
									style="width:70px" value="<? echo $FechaFin?>"></td>        
								<td>
								<?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
									$res=ExQuery($cons);?>
									<select name="Ambito" onChange="document.FORMA.submit()">
										<option></option>
									<?	while($fila=ExFetch($res))
										{
											if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}?>
									</select>
								</td>
							  
								<td>
								<?	$cons="select pabellon from salud.pabellones where compania='$Compania[0]' and ambito='$Ambito' order by pabellon";
									$res=ExQuery($cons);?>
									<select name="Und" onChange="document.FORMA.submit()">
										<option></option>
									<?	while($fila=ExFetch($res))
										{
											if($fila[0]==$Und){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}?>
									</select>
								</td>
								<td>
									<select name="Tipo" onChange="document.FORMA.submit()">
										<option value="Todas" <? if($Tipo=="Todas"){?> selected<? }?>>Todas</option>
										<option value="Ligadas a Paciente" <? if($Tipo=="Ligadas a Paciente"){?> selected<? }?>>Ligadas a Paciente</option>
										<option value="No Ligadas a Paciente" <? if($Tipo=="No Ligadas a Paciente"){?> selected<? }?>>No Ligadas a Paciente</option>
									</select>
								</td>
								<td><input type="checkbox" name="VerAccs" <? if($VerAccs){?> checked<? }?> onClick="document.FORMA.submit()"></td>
							</tr>
							<tr align="center">
								<td colspan="12"><input type="submit" class="boton2Envio" name="Ver" value="Ver"></td>
							</tr>
						</table>
						<?	
							if($Ambito){$Amb=" and ambito='$Ambito'";}
							if($Und){$UN="and pabellon='$Und'";}
							if($Tipo=="Ligadas a Paciente"){$Ti="and correo is null";}
							if($Tipo=="No Ligadas a Paciente"){$Ti="and correo is not null";}
							
							$cons="select sucesos.fechacrea,nombre,sucesos.cedula,sucesos.cedula,ambito,pabellon,tipoformato,formato,idhistoria,sucesos.idsuceso,correo,accion 
							from pacienteseguro.sucesos,central.usuarios where sucesos.compania='$Compania[0]' and usuarios.usuario=sucesos.usuario and usucierrasuceso is not null
							and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' $Amb $UN $Ti
							order by fechacrea"; 
							$res=ExQuery($cons);?>
						<br>    
						<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
							<tr>
								<td class="encabezado2Horizontal">FECHA REPORTE</td>
								<td class="encabezado2Horizontal">REPORTA</td>
								<td class="encabezado2Horizontal">PACIENTE</td>
								<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
								<td class="encabezado2Horizontal">PROCESO</td>
								<td class="encabezado2Horizontal">SERVICIO</td>
								<td class="encabezado2Horizontal">FORMATO</td>
								<td class="encabezado2Horizontal">FECHA AN&Aacute;LISIS</td>
								<td class="encabezado2Horizontal">AN&Aacute;LISIS HALLAZGO</td>
							</tr>
								<?
								while($fila=ExFetch($res)){?>
									<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
									<?	echo "<td>$fila[0]</td><td>$fila[1]</td>";	
										if($fila[10]){?>
											<td style="cursor:hand" title="Ver Correo" colspan="5"
											onClick="open('/Correo/VerCorroInd.php?DatNameSID=<? echo $DatNameSID?>&IdCorreo=<? echo $fila[10]?>','','')">
												Suceso no ligado a un paciente
											</td>
									<?	}
										else
										{
											$cons2="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) from central.terceros where compania='$Compania[0]' and 
											identificacion='$fila[3]'";
											$res2=ExQuery($cons2); $fila2=ExFetch($res2);?>
											<td style="cursor:hand" title="Abrir Historia"
											onClick="location.href='/HistoriaClinica/ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[3]?>&Buscar=1'">
											<? echo $fila2[0]?></td>
											<td style="cursor:hand" title="Abrir Historia"
											onClick="location.href='/HistoriaClinica/ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[3]?>&Buscar=1'">
											<? echo $fila[3]?></td>
											<td><? echo "$fila[4]&nbsp;"?></td><td><? echo "$fila[5]&nbsp;"?></td>
											<td style="cursor:hand" title="Ver Formato" onClick="open('/HistoriaClinica/ImpHistoria.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $fila[7]?>&TipoFormato=<? echo $fila[6]?>&IdHistoria=<? echo $fila[8]?>&CedImpMasv=<? echo $fila[2]?>&Opc=1','','')">
											<? echo $fila[7]?></td>
									<?	}		
										$cons2="select fechacrea from pacienteseguro.regprotocolo where compania='$Compania[0]' and idsuceso=$fila[9]";
										//echo $cons2;
										$res2=ExQuery($cons2); $fila2=ExFetch($res2);?>
										<td><? echo $fila2[0]?>&nbsp;</td>
									<?	if($fila[11]=="EventoAdverso"){$fila[11]="Evento Adverso";}
										if($fila[11]=="Incidente"){$fila[11]="Incidente (Accion Insegura)";}?>
										<td style="cursor:hand" title="Ver Formato de Registro de Hallasgos"
										onClick="open('VerRepRegHallasgos.php?DatNameSID=<? echo $DatNameSID?>&IdSuceso=<? echo $fila[9]?>','','')"><? echo $fila[11]?>&nbsp;</td>
							</tr>
						<? 	if($VerAccs)
							{?>
								<tr>        	
									<td colspan="9">
										<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
											<tr>
												<td class="encabezado2Horizontal">ACCI&Oacute;N</td>
												<td class="encabezado2Horizontal">RESPONSABLE</td>
												<td class="encabezado2Horizontal">FECHA CREACI&Oacute;N</td>
												<td class="encabezado2Horizontal">FECHA LIMITE</td>
												<td class="encabezado2Horizontal">FECHA LIM. APLAZADA</td>
												<td class="encabezado2Horizontal">FECHA CIERRE</td>
												<td class="encabezado2Horizontal">DIAS DE ATRASO</td>
											</tr>
										<?	$cons2="select accion,responsable,fechacrea,fechauno,fechaaplaza,fechacierra from pacienteseguro.accionespropuestas
											where idsuceso=$fila[9] order by idaccion,accion";
											$res2=ExQuery($cons2);
											while($fila2=ExFetch($res2))
											{
												if($fila2[4]){$FecLmt=explode("-",$fila2[4]);}else{$FecLmt=explode("-",$fila2[3]);}
												$timestamp1 = mktime(0,0,0,$ND[mon],$ND[mday],$ND[year]); 
												$timestamp2 = mktime(4,12,0,$FecLmt[1],$FecLmt[2],$FecLmt[0]); 					
												$segundos_diferencia = $timestamp1 - $timestamp2; 					
												$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
												//$dias_diferencia = abs($dias_diferencia); 
												$dias_diferencia = (floor($dias_diferencia)+1);
												if($dias_diferencia>0){$dias_diferencia="<font color=#FF0000'>$dias_diferencia</font>";}
												echo "<tr align='center'><td>$fila2[0]</td><td>$fila2[1]</td><td>$fila2[2]</td><td>$fila2[3]</td><td>$fila2[4]&nbsp;</td>
												<td>$fila2[5]&nbsp</td><td>$dias_diferencia</td></tr>";
											}?>	
										</table>
									</td>
								</tr>
								<tr><td colspan="9">&nbsp;</td></tr>
						<?	}	
						}?>
						</table>
						<input type="hidden" name="Guardar" value=""/>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
				<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
			</div>
		</body>
	</html>
