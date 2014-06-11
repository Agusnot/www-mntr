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
				</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php $anchoTabla = "900px";?>
				<div align="center">
					<p align="center">
					  <?
					$cons="select nombre,fechacrea,fechareporte,sucesos.cedula,formato,ambito,pabellon,correo from pacienteseguro.sucesos,central.usuarios 
					where compania='$Compania[0]' and idsuceso=$IdSuceso and sucesos.usuario=usuarios.usuario";
					$res=ExQuery($cons);
					$DatosSuceso=ExFetch($res);
					$cons="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$DatosSuceso[3]'";
					$res=ExQuery($cons);
					$NombrePac=ExFetch($res);
					$cons="select * from pacienteseguro.regprotocolo where compania='$Compania[0]' and idsuceso=$IdSuceso";
					$res=ExQuery($cons);
					$DatosReg=ExFetchArray($res);?>
					</p>
				
					<table width="<?php echo $anchoTabla ;?>" class="tabla2"  border="0" <?php  echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
					  <tr align="center">
						<td width="132" rowspan="4"><img src="/Imgs/Logo.jpg" width="88" > </td>
						<td width="471" rowspan="4"><?
						echo "<font size='4' >".$Compania[0]."</font>";
						echo "<br>CODIGO ".$Compania[17]."";
						echo "<br>".$Compania[1]."</br>";
						echo "".$Compania[2]." - ";
						echo " TELEFONOS ".$Compania[3]."";	
						?>    </td>
						<td width="132" rowspan="4"><table border="0">
						  <tr>
							<td style="text-align:center;"><span class="Estilo1">CONSECUTIVO DEL SUCESO </span></td>
						  </tr>
						  <tr>
							<td><div align="center" class="Estilo2"><? echo $IdSuceso?></div></td>
						  </tr>
						</table></td>
					  </tr>
					</table>
					
					<table width="<?php echo $anchoTabla ;?>"  class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
						<tr>
							<td class="encabezado2Horizontal" colspan="11">DATOS DEL REPORTE DEL SUCESO</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">FECHA REPORTE</td><td><? echo $DatosSuceso[0]?></td>
							<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">REPORTA</td><td><? echo $DatosSuceso[1]?></td>
							<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%"> FECHA AN&Aacute;LISIS</td>
							<td ><? echo $DatosSuceso[2]?>&nbsp;</td>
						</tr>
						
					<?	if($DatosSuceso[7]){?>
							<tr>
							<td  class="mensaje1" colspan="6">Suceso no ligado a un paciente</td></tr>
					<?	} 
						else{?>
							<tr>
								<td class="encabezado2VerticalInvertido" >PACIENTE</td>
								<td colspan="3"><? echo "$NombrePac[0] $NombrePac[1] $NombrePac[2] $NombrePac[3]"?></td>
								<td class="encabezado2VerticalInvertido" >IDENTIFICACI&Oacute;N</td><td><? echo $DatosSuceso[3]?></td>           
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" >PROCESO</td>
								<td><? echo $DatosSuceso[5]?></td>
								<td class="encabezado2VerticalInvertido" >SERVICIO</td>
								<td><? echo $DatosSuceso[6]?></td>
								<td class="encabezado2VerticalInvertido" >FORMATO</td><td><? echo $DatosSuceso[4]?></td>
							</tr>
					<?	}?>
					</table>
					
					
				<br />
						<table  width="<?php echo $anchoTabla ;?>"  class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
							<tr>
								<td class="encabezado2Horizontal" colspan="4">DATOS AN&Aacute;LISIS</td></tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">REPORTA</td>
								<td><? echo $DatosReg['reporta']?>&nbsp;</td>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" >CARGO</td>
								<td><? echo $DatosReg['cargo']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">EQUIPO</td>
								<td colspan="3"><? echo $DatosReg['equipo']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">PROCEDIMIENTO</td>
								<td colspan="3"><? echo $DatosReg['procedimientos']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">AMBITO LABORAL - SOCIAL</td>
								<td colspan="3"><? echo $DatosReg['amblabsocial']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">COLABORADOR</td>
								<td colspan="3"><? echo $DatosReg['colaborador']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">PACIENTE</td>
								<td colspan="3"><? echo $DatosReg['paciente']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">AN&Aacute;LISIS</td>
								<td colspan="3"><? echo $DatosReg['analisis']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">CAUSAS</td>
								<td colspan="3"><? echo $DatosReg['causas']?>&nbsp;</td>
							</tr>
						<?	if($DatosReg['resultado']=="NoAplica"){$DatosReg['resultado']="No Aplica";}
							if($DatosReg['resultado']=="Error"){$DatosReg['resultado']="Error Sin Da&ntilde;o";}
							if($DatosReg['resultado']=="Incidente"){$DatosReg['resultado']="Incidente (Accion Insegura)";}
							if($DatosReg['resultado']=="EventoAdverso"){$DatosReg['resultado']="Evento Adverso";}?>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">RESULTADO</td>
								<td colspan="3"><? echo $DatosReg['resultado']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">FECHA SUCESO</td>
								<td><? echo $DatosReg['fechasuceso']?>&nbsp;</td>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">HORA SUCESO</td>
								<td><? echo $DatosReg['horasuceso'].":".$DatosReg[minssuceso]?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">BARRERAS Y DEFENSAS</td>
								<td colspan="3"><? echo $DatosReg['barrerasdefensa']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">DESCRIPCI&Oacute;N DE LAS BARRERAS <br> Y/O DEFENSAS QUE FALLARON</td>
								<td colspan="3"><? echo $DatosReg['descripbarrerasdefs']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">ACCIONES INSEGURAS</td>
								<td colspan="3"><? echo $DatosReg['accionesinseguras']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">OTRAS ACCIONES INSEGURAS</td>
								<td colspan="3"><? echo $DatosReg['otrasaccinsegs1'].", ".$DatosReg['otrasaccinsegs2'].", ".$DatosReg['otrasaccinsegs3'];?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">TIPO EVENTO</td>
								<td colspan="3"><? echo $DatosReg['tipoevento']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">DESCRIPCI&Oacute;N EVENTO ADVERSO</td>
								<td colspan="3"><? echo $DatosReg['descripeventoadverso']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">ORIGEN CONTRIBUTIVO</td>
								<td><? echo $DatosReg['origencotributivo']?>&nbsp;</td>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">FACTOR CONTRIBUTIVO</td>
								<td><? echo $DatosReg['factorcontributivo']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">OTROS FACTORES CONTRIBUTIVOS</td>
								<td colspan="3"><? echo $DatosReg['otrosfactscontribs']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%"><p> DESCRIPCI&Oacute;N DE LAS ACCIONES INSEGURAS <br> Y SU RELACI&Oacute;N CON LOS FACTORES CONTRIBUTIVOS </p></td>
								<td colspan="3"><? echo $DatosReg['descripaccsinsegs']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">DESCRIPCI&Oacute;N DE LAS FALLAS LATENTES</td>
								<td colspan="3"><? echo $DatosReg['descripsfallaslatentes']?>&nbsp;</td>
							</tr>
							 <tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%"> ORGANIZACI&Oacute;N Y CULTURA</td>
								<td><? echo $DatosReg['orgycultura']?>&nbsp;</td>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">ESPECIALIDAD</td>
								<td><? echo $DatosReg['espcialidad']?>&nbsp;</td>
							</tr>
							 <tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">SERVICIO</td>
								<td><? echo $DatosReg['ambito']?>&nbsp;</td>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">UNIDAD DE ATENCI&Oacute;N</td>
								<td><? echo $DatosReg['pabellon']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">CONCLUSI&Oacute;</td>
								<td colspan="3"><? echo $DatosReg['conclusion']?>&nbsp;</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">
									<p>M&Eacute;TODOS UTILIZADOS PARA <br>OBTENER LA INFORMACI&Oacute;N</p>
								</td>
								<td colspan="3"><? if($DatosReg['metodo1']=="Si"){echo "- Analisis de la Historia Clinica, Protocolos, Procedimientos ";}
									if($DatosReg['metodo2']=="Si"){echo "<br> - Entrivistas a las personas que intervienen en el procesos ";}
									if($DatosReg['metodo3']=="Si"){echo "<br> - Otros mecanismos: Declaraciones, Observaciones, etc. ";}?></td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">EVENTO ADVERSO PREVENIBLE</td>
								<td colspan="3"><? echo $DatosReg['prevenible']?>&nbsp;</td>
							</tr>
							<tr>
							  <td height="10" colspan="4" align="center" bgcolor="#FFFFFF" style="font-weight:bold">&nbsp;</td>
							</tr>
							<tr>
							  <td colspan="4" class="encabezado2Horizontal" >ACCIONES PROPUESTAS </td>
							</tr>
							<tr>
							  <td height="10" colspan="4" align="center" bgcolor="#FFFFFF" style="font-weight:bold">&nbsp;</td>
							</tr>
								  <? $cons2="select accion,tipoaccion,responsable,fechauno,fechaaplaza,observacion,fechaaprueba,fecharealizado,fechacierra from pacienteseguro.accionespropuestas where idsuceso='$IdSuceso'";
							  $res2=ExQuery($cons2);
							  $i=1;
							  while($fila2=Exfetch($res2)){
							   ?>
							<tr>
							  <td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">ACCI&Oacute;N NO. <?php echo"$i"; ?></td>
							  <td colspan="3"><?php echo"$fila2[0]"; ?></td>
							</tr>
							<tr>
							  <td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">TIPO DE ACCIÓN </td>
							  <td colspan="3"><?php echo"$fila2[1]"; ?></td>
							</tr>
							<tr>
							  <td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">RESPONSABLE </td>
							  <td colspan="3"><?php echo"$fila2[2]"; ?></td>
							</tr>
							<tr>
							  <td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">FECHA INICIAL</td>
							  <td colspan="3"><?php echo"$fila2[3]&nbsp;"; ?></td>
							</tr>
							<tr>
							  <td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">FECHA APLAZAMIENTO</td>
							  <td colspan="3"><?php echo"$fila2[4]&nbsp;"; ?></td>
							</tr>
							<tr>
							  <td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">OBSERVACI&Oacute;N</td>
							  <td colspan="3"><?php echo"$fila2[5]&nbsp;"; ?></td>
							</tr>
							<tr>
							  <td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">FECHA APROBACI&Oacute;N</td>
							  <td colspan="3"><?php echo"$fila2[6]&nbsp;"; ?></td>
							</tr>
							<tr>
							  <td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">FECHA REALIZACI&Oacute;N</td>
							  <td colspan="3"><?php echo"$fila2[7]&nbsp;"; ?></td>
							</tr>
							<tr>
							  <td class="encabezado2VerticalInvertido" style="padding-top:7px;padding-bottom:7px;" width="30%">FECHA DE CIERRE DEL CASO </td>
							  <td colspan="3"><?php echo"$fila2[8]&nbsp;"; ?></td>
							</tr>
							<tr>
							  <td colspan="4" align="center" bgcolor="#FFFFFF" style="font-weight:bold">&nbsp;</td>
							</tr>
							<?php $i++; } ?>
						</table>
				
					<input type="button" class="boton2Envio" name="imprimir" value="Imprimir" onclick="window.print();">
				</div>
			</body>
		</html>