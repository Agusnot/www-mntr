		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
			$cons="select aprobador from pacienteseguro.aprobador where compania='$Compania[0]' and aprobador='$usuario[1]'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){$BanReg=1;}
			
			$cons="select usucomite from pacienteseguro.comite where compania='$Compania[0]' and usucomite='$usuario[1]'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){$BanReg=1;}
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
				<script language="javascript">
					function Enviar(e,Num)
					{
						y = e.clientY; 
						st = document.body.scrollTop;
						frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/RegSeguimientoCaso.php?DatNameSID=<? echo $DatNameSID?>&IdSuceso="+Num;
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
						frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/VerFormatosAcciones.php?DatNameSID=<? echo $DatNameSID?>&Caso=2&NumRep="+Num;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=y-10+st;
						document.getElementById('FrameOpener').style.left=x;
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='240';
						document.getElementById('FrameOpener').style.height='130';
					}
				</script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "PACIENTE SEGURO";
					$rutaarchivo[2] = "SEGUIMIENTO DE CASO";
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
						<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
								<tr>
									<td class="encabezado2Horizontal" colspan="12" >SEGUIMIENTO DE CASO</td>
								</tr>
								<tr>
									<td class="encabezado2HorizontalInvertido">FECHA REPORTE</td>
									<td class="encabezado2HorizontalInvertido">REPORTA</td>
									<td class="encabezado2HorizontalInvertido">PACIENTE</td>
									<td class="encabezado2HorizontalInvertido">IDENTIFICACI&Oacute;N</td>
									<td class="encabezado2HorizontalInvertido">PROCESO</td>
									<td class="encabezado2HorizontalInvertido">SERVICIO</td>
									<td class="encabezado2HorizontalInvertido">FORMATO</td>
									<td class="encabezado2HorizontalInvertido">FECHA AN&Aacute;LISIS</td>
									<td class="encabezado2HorizontalInvertido">AN&Aacute;LISIS HALLAZGO</td>
									<td class="encabezado2HorizontalInvertido">&nbsp;</td>
								</tr><?
								$cons="select sucesos.fechacrea,nombre,sucesos.cedula,sucesos.cedula,ambito,pabellon,tipoformato,formato,idhistoria,sucesos.idsuceso,correo,accion 
								from pacienteseguro.sucesos,central.usuarios where sucesos.compania='$Compania[0]' and usuarios.usuario=sucesos.usuario and sucesos.estado='AC'
								and accionespropuestas=1
								order by fechacrea";
								$res=ExQuery($cons);

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
										<td align="center">
											<button type="button" <? if($BanReg){?> onClick="Enviar(event,'<? echo $fila[9]?>')"<? }else{?> 
											onClick="alert('Usted no ha sido configurado como un usuario aprobador o de comite de Paciente Seguro!!!')"<? }?> 
											style="cursor:hand" title="Realizar el seguimiento a las Acciones Propuestas">
												<img src="/Imgs/b_check.png" />
											</button>
										</td>
									</tr><?
								}?>
						</table>
						<input type="hidden" name="Guardar" value=""/>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
				<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
			</div>
		</body>
	</html>
